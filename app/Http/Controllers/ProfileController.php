<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit(Request $request){ return view('profile.edit',['user'=>$request->user()->load('children','parents')]); }
    public function update(Request $request)
    {
        $user=$request->user();
        $data=$request->validate(['name'=>'required|string|max:100','email'=>['required','email',Rule::unique('users')->ignore($user)],'phone'=>'nullable|string|max:30','photo'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:2048','nik'=>['nullable','string','max:32',Rule::unique('users')->ignore($user)],'nis'=>['nullable','string','max:32',Rule::unique('users')->ignore($user)],'nisn'=>['nullable','string','max:32',Rule::unique('users')->ignore($user)],'nip'=>['nullable','string','max:32',Rule::unique('users')->ignore($user)],'gender'=>['nullable',Rule::in(['Laki-laki','Perempuan'])],'birth_place'=>'nullable|string|max:80','birth_date'=>'nullable|date|before:today','address'=>'nullable|string|max:1000','bio'=>'nullable|string|max:1000','occupation'=>'nullable|string|max:100','emergency_contact_name'=>'nullable|string|max:100','emergency_contact_phone'=>'nullable|string|max:30']);
        if($user->role==='student') { $data['occupation']=null; $data['nip']=null; }
        if($user->role==='teacher') { $data['nis']=null; $data['nisn']=null; }
        if($request->hasFile('photo')){ if($user->photo)Storage::disk('public')->delete($user->photo); $data['photo']=$request->file('photo')->store('profile-photos','public'); }
        $user->update($data);
        return back()->with('status','Profil berhasil diperbarui.');
    }
    public function password(Request $request)
    {
        $data=$request->validate(['current_password'=>'required|current_password','password'=>'required|string|min:8|confirmed']);
        $request->user()->update(['password'=>Hash::make($data['password'])]);
        return back()->with('status','Kata sandi berhasil diperbarui.');
    }
}
