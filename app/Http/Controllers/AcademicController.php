<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AcademicController extends Controller
{
    public function storeClass(Request $request){ $d=$request->validate(['name'=>'required|max:80','grade_level'=>'required|integer|between:1,12','academic_year_id'=>'required|exists:academic_years,id','major_id'=>'nullable|exists:majors,id','homeroom_teacher_id'=>['nullable',Rule::exists('users','id')->where(fn($q)=>$q->where('role','teacher'))]]); DB::table('school_classes')->insert($d+['created_at'=>now(),'updated_at'=>now()]); return back()->with('status','Kelas/rombel berhasil dibuat.'); }
    public function storeSubject(Request $request){ $d=$request->validate(['name'=>'required|max:100','code'=>'required|max:20|unique:subjects,code']); DB::table('subjects')->insert($d+['created_at'=>now(),'updated_at'=>now()]); return back()->with('status','Mata pelajaran berhasil dibuat.'); }
    public function storeSession(Request $request){ $d=$request->validate(['school_class_id'=>'required|exists:school_classes,id','subject_id'=>'required|exists:subjects,id','teacher_id'=>['required',Rule::exists('users','id')->where(fn($q)=>$q->where('role','teacher')->where('is_active',true))],'room_name'=>'nullable|max:80','day'=>['required',Rule::in(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'])],'start_time'=>'required|date_format:H:i','end_time'=>'required|date_format:H:i|after:start_time']); DB::table('class_sessions')->insert($d+['created_at'=>now(),'updated_at'=>now()]); return back()->with('status','Pelajaran berhasil dimasukkan ke jadwal kelas.'); }
    public function storeCalendar(Request $request){ $d=$request->validate(['title'=>'required|max:120','type'=>['required',Rule::in(['libur','ujian','agenda'])],'date_start'=>'required|date','date_end'=>'required|date|after_or_equal:date_start','target_role'=>['nullable',Rule::in(['admin','teacher','student','parent'])]]); DB::table('school_calendar')->insert($d+['created_by'=>$request->user()->id,'created_at'=>now(),'updated_at'=>now()]); return back()->with('status','Agenda kalender akademik berhasil ditambahkan.'); }
}
