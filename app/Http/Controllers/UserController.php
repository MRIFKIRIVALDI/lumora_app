<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    public function index(){ return view('users.index',['users'=>User::latest()->paginate(10),'roleCounts'=>User::select('role',DB::raw('count(*) as total'))->groupBy('role')->pluck('total','role')]); }
    public function store(Request $request){ $data=$request->validate(['name'=>'required|max:100','email'=>'required|email|unique:users','role'=>['required',Rule::in(['admin','teacher','student','parent'])],'phone'=>'nullable|max:30','password'=>'required|min:8']); User::create($data); return back()->with('status','Akun berhasil dibuat. Pengguna kini dapat login.'); }
    public function toggle(User $user){ abort_if(auth()->id()===$user->id,422,'Akun sendiri tidak dapat dinonaktifkan.'); $user->update(['is_active'=>!$user->is_active]); return back()->with('status','Status akun diperbarui.'); }
    public function edit(User $user)
    {
        $user->load('children','parents');
        return view('users.edit',['managedUser'=>$user,'students'=>User::where('role','student')->where('is_active',true)->orderBy('name')->get(),'parents'=>User::where('role','parent')->where('is_active',true)->orderBy('name')->get()]);
    }
    public function update(Request $request, User $user)
    {
        $data=$request->validate(['name'=>'required|string|max:100','email'=>['required','email',Rule::unique('users')->ignore($user)],'phone'=>'nullable|string|max:30','role'=>['required',Rule::in(['admin','teacher','student','parent'])],'is_active'=>'required|boolean']);
        if(auth()->id()===$user->id && ($data['role']!=='admin' || !$data['is_active'])) return back()->with('error','Admin tidak dapat mengubah role atau menonaktifkan akunnya sendiri.')->withInput();
        DB::transaction(function() use($user,$data){ $oldRole=$user->role; $user->update($data); if($oldRole!==$data['role']){ if($oldRole==='parent')$user->children()->detach(); if($oldRole==='student')$user->parents()->detach(); } });
        return redirect()->route('users.edit',$user)->with('status','Data dan role pengguna berhasil diperbarui.');
    }
    public function relations(Request $request, User $user)
    {
        if($user->role==='parent'){
            $ids=$request->validate(['student_ids'=>'array','student_ids.*'=>['integer',Rule::exists('users','id')->where(fn($q)=>$q->where('role','student')->where('is_active',true))]])['student_ids']??[];
            DB::transaction(function() use($user,$ids){ DB::table('parent_student')->whereIn('student_id',$ids)->where('parent_id','!=',$user->id)->delete(); $user->children()->sync($ids); });
        } elseif($user->role==='student'){
            $parentId=$request->validate(['parent_id'=>['nullable','integer',Rule::exists('users','id')->where(fn($q)=>$q->where('role','parent')->where('is_active',true))]])['parent_id']??null;
            $user->parents()->sync($parentId?[$parentId]:[]);
        } else return back()->with('error','Relasi hanya tersedia untuk akun Wali atau Murid.');
        return back()->with('status','Relasi Wali–Murid berhasil diperbarui.');
    }
}
