<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\UserDataScope;
class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user=$request->user();
        $studentIds=UserDataScope::studentIds($user); $sessionIds=UserDataScope::sessionIds($user);
        $stats=match($user->role){
            'admin'=>[['Pengguna aktif',DB::table('users')->where('is_active',1)->count(),'4 peran terkelola'],['Hadir hari ini',DB::table('attendances')->whereDate('date',today())->whereIn('status',['hadir','terlambat'])->count(),'Data presensi pusat'],['Titik QR aktif',DB::table('qr_stations')->where('status','active')->count(),'Multi-titik realtime'],['Tagihan tertunda','Rp '.number_format(DB::table('spp_bills')->where('status','pending')->sum('amount'),0,',','.'),'Perlu dipantau']],
            'teacher'=>[['Kelas diampu',UserDataScope::classIds($user)->count(),'Tahun ajaran aktif'],['Jadwal hari ini',DB::table('class_sessions')->whereIn('id',$sessionIds)->count(),'Sesi pembelajaran'],['Tugas masuk',DB::table('assignment_submissions')->whereIn('assignment_id',DB::table('assignments')->whereIn('class_session_id',$sessionIds)->select('id'))->count(),'Perlu ditinjau'],['Murid terkait',$studentIds->count(),'Dari seluruh kelas']],
            'student'=>[['Jadwal hari ini',3,'Sesi pembelajaran'],['Tugas aktif',DB::table('assignments')->where('due_date','>',now())->count(),'1 mendekati tenggat'],['Kehadiran','96%','Semester ini'],['Tagihan','Rp '.number_format(DB::table('spp_bills')->where('student_id',$user->id)->where('status','pending')->sum('amount'),0,',','.'),'Belum dibayar']],
            default=>[['Anak terhubung',DB::table('parent_student')->where('parent_id',$user->id)->count(),'Dalam satu akun'],['Status hari ini','Hadir','Masuk pukul 06.53'],['Tagihan aktif','Rp 450.000','Jatuh tempo 10 Okt'],['Pengumuman',DB::table('announcements')->count(),'Informasi sekolah']]
        };
        $announcements=DB::table('announcements')->where(fn($q)=>$q->whereNull('target_role')->orWhere('target_role',$user->role))->latest('published_at')->limit(3)->get();
        $schedule=DB::table('class_sessions')->join('subjects','subjects.id','=','class_sessions.subject_id')->join('school_classes','school_classes.id','=','class_sessions.school_class_id')->whereIn('class_sessions.id',$sessionIds)->select('class_sessions.*','subjects.name as subject','school_classes.name as class_name')->orderBy('start_time')->limit(6)->get();
        $todayAttendance = $user->role === 'student' ? DB::table('attendances')->where('student_id',$user->id)->whereDate('date',today())->first() : null;
        $attendanceCounts = DB::table('attendances')->whereIn('student_id',$studentIds)->select('status',DB::raw('count(*) as total'))->groupBy('status')->pluck('total','status');
        $paid = (float) DB::table('spp_bills')->whereIn('student_id',$studentIds)->where('status','paid')->sum('amount');
        $pending = (float) DB::table('spp_bills')->whereIn('student_id',$studentIds)->where('status','pending')->sum('amount');
        $charts = [
            'weekly'=>[82,88,91,86,94,90,96],
            'payments'=>[58,64,61,73,78,84],
            'attendance'=>[(int)($attendanceCounts['hadir']??0),(int)($attendanceCounts['terlambat']??0),(int)($attendanceCounts['izin']??0),(int)($attendanceCounts['alpa']??0)],
            'finance'=>[$paid,$pending],
        ];
        $calendarEvents=DB::table('school_calendar')->where(fn($q)=>$q->whereNull('target_role')->orWhere('target_role',$user->role))->orderBy('date_start')->limit(12)->get();
        $studentSchedule=collect();
        $studentClasses=collect(); $pendingWork=collect(); $children=collect();
        if($user->role==='student'){
            $classId=DB::table('class_students')->where('student_id',$user->id)->where('status','active')->value('school_class_id');
            if($classId)$studentSchedule=DB::table('class_sessions')->join('subjects','subjects.id','=','class_sessions.subject_id')->where('school_class_id',$classId)->select('class_sessions.*','subjects.name as subject')->orderBy('start_time')->get();
            $studentClasses=DB::table('class_sessions')->join('subjects','subjects.id','=','class_sessions.subject_id')->join('users','users.id','=','class_sessions.teacher_id')->join('school_classes','school_classes.id','=','class_sessions.school_class_id')->whereIn('class_sessions.id',$sessionIds)->select('class_sessions.*','subjects.name as subject','users.name as teacher','school_classes.name as class_name')->get();
            $pendingWork=DB::table('assignments')->join('class_sessions','class_sessions.id','=','assignments.class_session_id')->join('subjects','subjects.id','=','class_sessions.subject_id')->whereIn('assignments.class_session_id',$sessionIds)->where('due_date','>',now())->select('assignments.*','subjects.name as subject')->orderBy('due_date')->limit(6)->get();
        }
        if($user->role==='parent')$children=DB::table('users')->leftJoin('class_students','class_students.student_id','=','users.id')->leftJoin('school_classes','school_classes.id','=','class_students.school_class_id')->whereIn('users.id',$studentIds)->select('users.*','school_classes.name as class_name')->get();
        $weekDates=collect(range(0,6))->map(fn($i)=>now()->startOfWeek()->addDays($i));
        return view('dashboard',compact('user','stats','announcements','schedule','todayAttendance','charts','calendarEvents','studentSchedule','studentClasses','pendingWork','children','weekDates'));
    }
}
