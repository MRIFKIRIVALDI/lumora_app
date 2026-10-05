<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\UserDataScope;
use Carbon\Carbon;
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
        $todayAttendance = $user->isRole('student','teacher') ? DB::table('attendances')->where('student_id',$user->id)->whereDate('date',today())->first() : null;
        $attendanceCounts = DB::table('attendances')->whereIn('student_id',$studentIds)->whereDate('date',today())->select('status',DB::raw('count(*) as total'))->groupBy('status')->pluck('total','status');
        $presentCount=(int)($attendanceCounts['hadir']??0)+(int)($attendanceCounts['terlambat']??0); $sickCount=(int)($attendanceCounts['sakit']??0); $permitCount=(int)($attendanceCounts['izin']??0); $alphaCount=max(0,$studentIds->count()-$presentCount-$sickCount-$permitCount);
        $financeYear=(int)now()->year;
        $monthlyBills=DB::table('spp_bills')->whereIn('student_id',$studentIds)->where('year',$financeYear)
            ->select('month','status',DB::raw('sum(amount) as total'))->groupBy('month','status')->get();
        $financePaid=[];$financePending=[];
        foreach(range(1,12) as $month){
            $financePaid[]=(float)($monthlyBills->first(fn($row)=>(int)$row->month===$month&&$row->status==='paid')->total??0);
            $financePending[]=(float)($monthlyBills->first(fn($row)=>(int)$row->month===$month&&$row->status==='pending')->total??0);
        }
        $charts = [
            'weekly'=>[82,88,91,86,94,90,96],
            'payments'=>[58,64,61,73,78,84],
            'attendance'=>[$presentCount,$sickCount,$permitCount,$alphaCount],
            'finance'=>['year'=>$financeYear,'paid'=>$financePaid,'pending'=>$financePending],
        ];
        $calendarEvents=DB::table('school_calendar')->where(fn($q)=>$q->whereNull('target_role')->orWhere('target_role',$user->role))->orderBy('date_start')->limit(12)->get();
        $studentSchedule=collect();
        $studentClasses=collect(); $pendingWork=collect(); $children=collect();
        $dashboardBills=DB::table('spp_bills')->join('users','users.id','=','spp_bills.student_id')
            ->whereIn('spp_bills.student_id',$studentIds)->where('spp_bills.status','pending')
            ->select('spp_bills.*','users.name as student_name')->orderBy('spp_bills.year')->orderBy('spp_bills.month')->limit(6)->get();
        if($user->role==='student'){
            $classId=DB::table('class_students')->where('student_id',$user->id)->where('status','active')->value('school_class_id');
            if($classId)$studentSchedule=DB::table('class_sessions')->join('subjects','subjects.id','=','class_sessions.subject_id')->where('school_class_id',$classId)->select('class_sessions.*','subjects.name as subject')->orderBy('start_time')->get();
            $studentClasses=DB::table('class_sessions')->join('subjects','subjects.id','=','class_sessions.subject_id')->join('users','users.id','=','class_sessions.teacher_id')->join('school_classes','school_classes.id','=','class_sessions.school_class_id')->whereIn('class_sessions.id',$sessionIds)->select('class_sessions.*','subjects.name as subject','users.name as teacher','school_classes.name as class_name')->get();
            $assignmentWork=DB::table('assignments')->join('class_sessions','class_sessions.id','=','assignments.class_session_id')->join('subjects','subjects.id','=','class_sessions.subject_id')->whereIn('assignments.class_session_id',$sessionIds)->where('due_date','>',now())->select('assignments.id','assignments.title','assignments.due_date','subjects.name as subject')->get()->map(function($item){$item->type='assignment';$item->url=route('assignments');return $item;});
            $quizWork=DB::table('quizzes')->join('learning_meetings','learning_meetings.id','=','quizzes.learning_meeting_id')->join('class_sessions','class_sessions.id','=','learning_meetings.class_session_id')->join('subjects','subjects.id','=','class_sessions.subject_id')->whereIn('class_sessions.id',$sessionIds)->where('quizzes.status','published')->whereNotExists(fn($q)=>$q->selectRaw('1')->from('quiz_attempts')->whereColumn('quiz_attempts.quiz_id','quizzes.id')->where('quiz_attempts.student_id',$user->id))->select('quizzes.id','quizzes.title',DB::raw("learning_meetings.meeting_date as due_date"),'subjects.name as subject')->get()->map(function($item){$item->title='Kuis: '.$item->title;$item->type='quiz';$item->url=route('quizzes.show',$item->id);return $item;});
            $pendingWork=$assignmentWork->concat($quizWork)->sortBy('due_date')->take(8)->values();
        }
        if($user->role==='parent')$children=DB::table('users')->leftJoin('class_students','class_students.student_id','=','users.id')->leftJoin('school_classes','school_classes.id','=','class_students.school_class_id')->whereIn('users.id',$studentIds)->select('users.*','school_classes.name as class_name')->get();
        $adminOverview=[]; $recentUsers=collect(); $classOverview=collect(); $teacherSchedule=collect();
        if($user->role==='admin'){
            $adminOverview=[
                'roles'=>DB::table('users')->select('role',DB::raw('count(*) as total'))->groupBy('role')->pluck('total','role'),
                'classes'=>DB::table('school_classes')->count(), 'subjects'=>DB::table('subjects')->count(), 'sessions'=>DB::table('class_sessions')->count(),
                'meetings'=>DB::table('learning_meetings')->count(), 'materials'=>DB::table('materials')->count(), 'assignments'=>DB::table('assignments')->count(), 'quizzes'=>DB::table('quizzes')->count(),
                'student_attendance'=>DB::table('attendances')->join('users','users.id','=','attendances.student_id')->where('users.role','student')->whereDate('attendances.date',today())->count(),
                'teacher_attendance'=>DB::table('attendances')->join('users','users.id','=','attendances.student_id')->where('users.role','teacher')->whereDate('attendances.date',today())->count(),
                'submissions'=>DB::table('assignment_submissions')->count(), 'pending_bills'=>DB::table('spp_bills')->where('status','pending')->count(),
            ];
            $recentUsers=DB::table('users')->latest()->limit(5)->get();
            $classOverview=DB::table('school_classes')->leftJoin('class_students',fn($join)=>$join->on('class_students.school_class_id','=','school_classes.id')->where('class_students.status','active'))->leftJoin('users','users.id','=','school_classes.homeroom_teacher_id')->select('school_classes.id','school_classes.name','school_classes.grade_level','users.name as homeroom',DB::raw('count(class_students.id) as students'))->groupBy('school_classes.id','school_classes.name','school_classes.grade_level','users.name')->orderBy('school_classes.grade_level')->get();
            $teacherSchedule=DB::table('class_sessions')->join('users','users.id','=','class_sessions.teacher_id')->join('subjects','subjects.id','=','class_sessions.subject_id')->join('school_classes','school_classes.id','=','class_sessions.school_class_id')->select('class_sessions.*','users.name as teacher_name','subjects.name as subject','school_classes.name as class_name')->orderByRaw("CASE day WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 ELSE 6 END")->orderBy('start_time')->get();
        }
        $weekOffset=max(-52,min(52,(int)$request->query('week',0)));$weekDates=collect(range(0,6))->map(fn($i)=>now()->startOfWeek()->addWeeks($weekOffset)->addDays($i));
        $monthInput=$request->query('month',now()->format('Y-m'));try{$calendarMonth=Carbon::createFromFormat('Y-m',$monthInput)->startOfMonth();}catch(\Throwable){$calendarMonth=now()->startOfMonth();}$calendarStart=$calendarMonth->copy()->startOfWeek();$calendarDays=collect(range(0,41))->map(fn($i)=>$calendarStart->copy()->addDays($i));
        $calendarSchedule=collect();if($user->isRole('student','teacher'))$calendarSchedule=DB::table('class_sessions')->join('subjects','subjects.id','=','class_sessions.subject_id')->join('school_classes','school_classes.id','=','class_sessions.school_class_id')->whereIn('class_sessions.id',$sessionIds)->select('class_sessions.*','subjects.name as subject','school_classes.name as class_name')->get();
        return view('dashboard',compact('user','stats','announcements','schedule','todayAttendance','charts','calendarEvents','studentSchedule','studentClasses','pendingWork','children','dashboardBills','weekDates','weekOffset','calendarMonth','calendarDays','calendarSchedule','adminOverview','recentUsers','classOverview','teacherSchedule'));
    }
}
