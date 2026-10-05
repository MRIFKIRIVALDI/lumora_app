<?php

namespace App\Http\Controllers;

use App\Support\UserDataScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ReportCardController extends Controller
{
    public function index(Request $request)
    {
        $user=$request->user();$sessionIds=UserDataScope::sessionIds($user);$studentIds=UserDataScope::studentIds($user);
        $reports=DB::table('report_cards')->join('class_sessions','class_sessions.id','=','report_cards.class_session_id')->join('subjects','subjects.id','=','class_sessions.subject_id')->join('school_classes','school_classes.id','=','class_sessions.school_class_id')->join('users as teachers','teachers.id','=','class_sessions.teacher_id')->whereIn('report_cards.class_session_id',$sessionIds)->when($user->isRole('student','parent'),fn($q)=>$q->where('report_cards.status','approved')->whereExists(fn($x)=>$x->selectRaw('1')->from('report_grades')->whereColumn('report_grades.report_card_id','report_cards.id')->whereIn('report_grades.student_id',$studentIds)))->select('report_cards.*','subjects.name as subject','school_classes.name as class_name','teachers.name as teacher')->orderByDesc('report_cards.created_at')->get();
        $sessions=$user->role==='teacher'?DB::table('class_sessions')->join('subjects','subjects.id','=','class_sessions.subject_id')->join('school_classes','school_classes.id','=','class_sessions.school_class_id')->where('teacher_id',$user->id)->select('class_sessions.id','subjects.name as subject','school_classes.name as class_name')->get():collect();
        return view('reports.index',compact('reports','sessions','user'));
    }

    public function store(Request $request)
    {
        $data=$request->validate(['class_session_id'=>'required|exists:class_sessions,id','semester'=>['required',Rule::in(['Ganjil','Genap'])]]);abort_unless(UserDataScope::sessionIds($request->user())->contains((int)$data['class_session_id']),403);
        $id=DB::table('report_cards')->updateOrInsert(['class_session_id'=>$data['class_session_id'],'semester'=>$data['semester']],['created_by'=>$request->user()->id,'updated_at'=>now(),'created_at'=>now()]);
        $report=DB::table('report_cards')->where(['class_session_id'=>$data['class_session_id'],'semester'=>$data['semester']])->first();return redirect()->route('reports.show',$report->id)->with('status','Draft rapor siap diisi.');
    }

    private function report(int $id,Request $request)
    {
        $report=DB::table('report_cards')->join('class_sessions','class_sessions.id','=','report_cards.class_session_id')->join('subjects','subjects.id','=','class_sessions.subject_id')->join('school_classes','school_classes.id','=','class_sessions.school_class_id')->join('users as teachers','teachers.id','=','class_sessions.teacher_id')->where('report_cards.id',$id)->select('report_cards.*','class_sessions.teacher_id','class_sessions.school_class_id','subjects.name as subject','school_classes.name as class_name','teachers.name as teacher')->first();abort_unless($report,404);$user=$request->user();abort_unless($user->role==='admin'||UserDataScope::sessionIds($user)->contains($report->class_session_id),403);if($user->isRole('student','parent'))abort_unless($report->status==='approved',403);return $report;
    }

    public function show(int $report,Request $request)
    {
        $report=$this->report($report,$request);$user=$request->user();$visible=UserDataScope::studentIds($user);$students=DB::table('class_students')->join('users','users.id','=','class_students.student_id')->leftJoin('report_grades',fn($j)=>$j->on('report_grades.student_id','=','users.id')->where('report_grades.report_card_id',$report->id))->where('class_students.school_class_id',$report->school_class_id)->where('class_students.status','active')->when($user->isRole('student','parent'),fn($q)=>$q->whereIn('users.id',$visible))->select('users.id','users.name','report_grades.assignment_score','report_grades.quiz_score','report_grades.mid_score','report_grades.final_exam_score','report_grades.final_score','report_grades.predicate','report_grades.teacher_note')->orderBy('users.name')->get();return view('reports.show',compact('report','students','user'));
    }

    public function grades(int $report,Request $request)
    {
        $report=$this->report($report,$request);abort_unless($request->user()->role==='teacher'&&(int)$report->teacher_id===$request->user()->id,403);abort_if(in_array($report->status,['submitted','approved']),422,'Rapor yang sedang diperiksa atau sudah disetujui tidak dapat diubah.');$data=$request->validate(['grades'=>'required|array','grades.*.assignment'=>'required|numeric|between:0,100','grades.*.quiz'=>'required|numeric|between:0,100','grades.*.mid'=>'required|numeric|between:0,100','grades.*.final_exam'=>'required|numeric|between:0,100','grades.*.note'=>'nullable|string|max:1000']);$validIds=DB::table('class_students')->where('school_class_id',$report->school_class_id)->where('status','active')->pluck('student_id');foreach($data['grades'] as $studentId=>$grade){if(!$validIds->contains((int)$studentId))continue;$score=round($grade['assignment']*.25+$grade['quiz']*.20+$grade['mid']*.25+$grade['final_exam']*.30,2);$predicate=$score>=90?'A':($score>=80?'B':($score>=70?'C':($score>=60?'D':'E')));DB::table('report_grades')->updateOrInsert(['report_card_id'=>$report->id,'student_id'=>$studentId],['assignment_score'=>$grade['assignment'],'quiz_score'=>$grade['quiz'],'mid_score'=>$grade['mid'],'final_exam_score'=>$grade['final_exam'],'final_score'=>$score,'predicate'=>$predicate,'teacher_note'=>$grade['note']??null,'created_at'=>now(),'updated_at'=>now()]);}DB::table('report_cards')->where('id',$report->id)->update(['status'=>'draft','updated_at'=>now()]);return back()->with('status','Nilai seluruh murid berhasil disimpan.');
    }

    public function submit(int $report,Request $request){$report=$this->report($report,$request);abort_unless($request->user()->role==='teacher'&&(int)$report->teacher_id===$request->user()->id,403);abort_unless(DB::table('report_grades')->where('report_card_id',$report->id)->count()===DB::table('class_students')->where('school_class_id',$report->school_class_id)->where('status','active')->count(),422,'Lengkapi nilai seluruh murid sebelum diajukan.');DB::table('report_cards')->where('id',$report->id)->update(['status'=>'submitted','submitted_at'=>now(),'review_note'=>null,'updated_at'=>now()]);return back()->with('status','Rapor diajukan kepada admin.');}
    public function review(int $report,Request $request){$report=$this->report($report,$request);abort_unless($request->user()->role==='admin',403);$data=$request->validate(['decision'=>'required|in:approved,rejected','review_note'=>'nullable|string|max:1000']);DB::table('report_cards')->where('id',$report->id)->update(['status'=>$data['decision'],'reviewed_by'=>$request->user()->id,'review_note'=>$data['review_note']??null,'approved_at'=>$data['decision']==='approved'?now():null,'updated_at'=>now()]);return back()->with('status',$data['decision']==='approved'?'Rapor disetujui dan dapat dilihat murid/wali.':'Rapor dikembalikan kepada guru.');}
}
