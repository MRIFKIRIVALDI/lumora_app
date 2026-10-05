<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Support\UserDataScope;
use Illuminate\Support\Str;

class FinanceController extends Controller
{
    public function store(Request $request)
    {
        $data=$request->validate(['student_id'=>'nullable|exists:users,id','month'=>'required|integer|between:1,12','year'=>'required|integer|between:2020,2100','amount'=>'required|numeric|min:1000']);
        $studentIds=$data['student_id']?collect([(int)$data['student_id']]):DB::table('users')->where('role','student')->where('is_active',true)->pluck('id');
        foreach($studentIds as $studentId) DB::table('spp_bills')->updateOrInsert(['student_id'=>$studentId,'month'=>$data['month'],'year'=>$data['year']],['amount'=>$data['amount'],'status'=>'pending','paid_at'=>null,'created_at'=>now(),'updated_at'=>now()]);
        return back()->with('status',$studentIds->count().' tagihan berhasil diterbitkan.');
    }

    public function status(Request $request,int $bill)
    {
        $data=$request->validate(['status'=>['required',Rule::in(['pending','paid'])]]);
        abort_unless(DB::table('spp_bills')->where('id',$bill)->exists(),404);
        DB::table('spp_bills')->where('id',$bill)->update(['status'=>$data['status'],'paid_at'=>$data['status']==='paid'?now():null,'updated_at'=>now()]);
        return back()->with('status',$data['status']==='paid'?'Tagihan ditandai lunas.':'Status tagihan dikembalikan menjadi menunggu.');
    }

    public function checkout(Request $request, int $bill)
    {
        $invoice=$this->accessibleBill($request,$bill);
        return view('modules.payment-checkout',['bill'=>$invoice,'user'=>$request->user()]);
    }

    public function pay(Request $request, int $bill)
    {
        $data=$request->validate(['method'=>['required',Rule::in(['qris','virtual_account','ewallet'])]]);
        $invoice=$this->accessibleBill($request,$bill);
        if($invoice->status==='paid') return redirect()->route('finance')->with('status','Tagihan ini sudah lunas.');

        $reference='SIM-'.now()->format('YmdHis').'-'.strtoupper(Str::random(6));
        DB::transaction(function() use($request,$invoice,$data,$reference){
            DB::table('payment_transactions')->insert([
                'spp_bill_id'=>$invoice->id,'payer_id'=>$request->user()->id,
                'reference'=>$reference,'method'=>$data['method'],'amount'=>$invoice->amount,
                'status'=>'success','paid_at'=>now(),
                'gateway_payload'=>json_encode(['mode'=>'sandbox','message'=>'Pembayaran simulasi berhasil']),
                'created_at'=>now(),'updated_at'=>now(),
            ]);
            DB::table('spp_bills')->where('id',$invoice->id)->where('status','pending')->update(['status'=>'paid','paid_at'=>now(),'updated_at'=>now()]);
        });
        return redirect()->route('finance')->with('status','Pembayaran simulasi berhasil. Referensi: '.$reference);
    }

    private function accessibleBill(Request $request, int $bill): object
    {
        $user=$request->user();
        abort_unless($user->isRole('student','parent'),403);
        $invoice=DB::table('spp_bills')->join('users','users.id','=','spp_bills.student_id')
            ->where('spp_bills.id',$bill)->whereIn('spp_bills.student_id',UserDataScope::studentIds($user))
            ->select('spp_bills.*','users.name as student_name')->first();
        abort_unless($invoice,404);
        return $invoice;
    }
}
