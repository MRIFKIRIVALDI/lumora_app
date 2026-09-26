<?php
namespace App\Http\Controllers;
use App\Support\UserDataScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ModuleController extends Controller
{
    public function attendance(Request $request){
        $user=$request->user(); $date=$request->validate(['date'=>'nullable|date'])['date']??today()->toDateString(); $studentIds=UserDataScope::studentIds($user);
        $visibleIds=$user->role==='admin'?DB::table('users')->whereIn('role',['student','teacher'])->pluck('id'):$studentIds->push($user->id)->unique();
        $people=DB::table('users')->whereIn('id',$visibleIds)->orderByRaw("CASE role WHEN 'teacher' THEN 1 ELSE 2 END")->orderBy('name')->get();
        $records=DB::table('attendances')->leftJoin('qr_stations','qr_stations.id','=','attendances.station_id')->whereIn('student_id',$visibleIds)->whereDate('date',$date)->select('attendances.*','qr_stations.label as station')->get()->keyBy('student_id');
        $rows=$people->map(function($person)use($records,$date){$a=$records->get($person->id);return(object)['student_id'=>$person->id,'name'=>$person->name,'role'=>$person->role,'date'=>$date,'check_in'=>$a?->check_in,'check_out'=>$a?->check_out,'status'=>$a?->status??'alpa','station'=>$a?->station,'latitude'=>$a?->latitude];});
        $summary=['hadir'=>$rows->whereIn('status',['hadir','terlambat'])->count(),'sakit'=>$rows->where('status','sakit')->count(),'izin'=>$rows->where('status','izin')->count(),'alpa'=>$rows->where('status','alpa')->count()];
        $stations=DB::table('qr_stations')->where('status','active')->when($user->role==='teacher',fn($q)=>$q->where('audience','student'))->latest('opened_at')->get();
        return view('modules.attendance',compact('rows','stations','date','summary'));
    }
    public function academics(Request $request){ $user=$request->user(); return view('modules.academics',['sessions'=>DB::table('class_sessions')->join('subjects','subjects.id','=','class_sessions.subject_id')->join('school_classes','school_classes.id','=','class_sessions.school_class_id')->join('users','users.id','=','class_sessions.teacher_id')->whereIn('class_sessions.id',UserDataScope::sessionIds($user))->select('class_sessions.*','subjects.name as subject','school_classes.id as class_id','school_classes.name as class_name','users.name as teacher')->orderBy('school_classes.grade_level')->orderByRaw("CASE day WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 ELSE 6 END")->orderBy('start_time')->get(),'classes'=>DB::table('school_classes')->whereIn('id',UserDataScope::classIds($user))->orderBy('grade_level')->orderBy('name')->get(),'subjects'=>DB::table('subjects')->orderBy('name')->get(),'teachers'=>DB::table('users')->where('role','teacher')->where('is_active',true)->orderBy('name')->get(),'years'=>DB::table('academic_years')->orderByDesc('is_active')->get(),'majors'=>DB::table('majors')->orderBy('name')->get(),'calendar'=>DB::table('school_calendar')->where(fn($q)=>$q->whereNull('target_role')->orWhere('target_role',$user->role))->orderBy('date_start')->get()]); }
    public function assignments(Request $request){
        $user=$request->user(); $sessionIds=UserDataScope::sessionIds($user);
        $assignments=DB::table('assignments')->join('class_sessions','class_sessions.id','=','assignments.class_session_id')->join('subjects','subjects.id','=','class_sessions.subject_id')->join('school_classes','school_classes.id','=','class_sessions.school_class_id')->leftJoin('learning_meetings','learning_meetings.id','=','assignments.learning_meeting_id')->whereIn('assignments.class_session_id',$sessionIds)->select('assignments.*','subjects.name as subject','school_classes.name as class_name','learning_meetings.meeting_number')->orderBy('assignments.due_date')->get();
        $submissions=DB::table('assignment_submissions')->whereIn('assignment_id',$assignments->pluck('id'))->when($user->role==='student',fn($q)=>$q->where('student_id',$user->id))->get()->groupBy('assignment_id');
        return view('modules.assignments',compact('assignments','submissions','user'));
    }
    public function finance(Request $request){ abort_if($request->user()->role==='teacher',403); return view('modules.finance',['bills'=>DB::table('spp_bills')->join('users','users.id','=','spp_bills.student_id')->whereIn('student_id',UserDataScope::studentIds($request->user()))->select('spp_bills.*','users.name')->latest('spp_bills.created_at')->get()]); }
}
