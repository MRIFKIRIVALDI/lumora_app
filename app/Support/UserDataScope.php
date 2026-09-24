<?php
namespace App\Support;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
class UserDataScope
{
    public static function classIds(User $user): Collection { return match($user->role){ 'admin'=>DB::table('school_classes')->pluck('id'), 'teacher'=>DB::table('school_classes')->where('homeroom_teacher_id',$user->id)->pluck('id')->merge(DB::table('class_sessions')->where('teacher_id',$user->id)->pluck('school_class_id'))->unique()->values(), 'student'=>DB::table('class_students')->where('student_id',$user->id)->where('status','active')->pluck('school_class_id'), 'parent'=>DB::table('class_students')->whereIn('student_id',self::studentIds($user))->where('status','active')->pluck('school_class_id')->unique()->values(), default=>collect() }; }
    public static function studentIds(User $user): Collection { return match($user->role){ 'admin'=>DB::table('users')->where('role','student')->pluck('id'), 'teacher'=>DB::table('class_students')->whereIn('school_class_id',self::classIds($user))->where('status','active')->pluck('student_id')->unique()->values(), 'student'=>collect([$user->id]), 'parent'=>DB::table('parent_student')->where('parent_id',$user->id)->pluck('student_id'), default=>collect() }; }
    public static function sessionIds(User $user): Collection { return DB::table('class_sessions')->whereIn('school_class_id',self::classIds($user))->when($user->role==='teacher',fn($q)=>$q->where(fn($x)=>$x->where('teacher_id',$user->id)->orWhereIn('school_class_id',DB::table('school_classes')->where('homeroom_teacher_id',$user->id)->select('id'))))->pluck('id'); }
}
