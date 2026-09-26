<?php

namespace App\Http\Controllers;

use App\Support\UserDataScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AssignmentSubmissionController extends Controller
{
    public function store(Request $request, int $assignment)
    {
        $user=$request->user();
        $task=DB::table('assignments')->where('id',$assignment)->first();
        abort_unless($task && UserDataScope::sessionIds($user)->contains($task->class_session_id),403);
        $data=$request->validate(['file'=>'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,zip|max:10240']);
        $existing=DB::table('assignment_submissions')->where('assignment_id',$assignment)->where('student_id',$user->id)->first();
        if($existing?->drive_file_id) Storage::disk('public')->delete($existing->drive_file_id);
        $path=$data['file']->store('assignment-submissions/'.$user->id,'public');
        DB::table('assignment_submissions')->updateOrInsert(
            ['assignment_id'=>$assignment,'student_id'=>$user->id],
            ['drive_file_link'=>Storage::url($path),'drive_file_id'=>$path,'drive_file_name'=>$data['file']->getClientOriginalName(),'last_verified_at'=>now(),'access_status'=>'accessible','submitted_at'=>now(),'updated_at'=>now(),'created_at'=>$existing?->created_at??now()]
        );
        return back()->with('status','Tugas berhasil dikumpulkan. Anda dapat mengganti file sebelum dinilai.');
    }
}
