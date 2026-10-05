<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
}
