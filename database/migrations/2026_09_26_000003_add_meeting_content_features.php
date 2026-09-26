<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void{
  Schema::table('materials',function(Blueprint $t){$t->string('file_path')->nullable()->after('url');$t->string('file_name')->nullable()->after('file_path');$t->string('mime_type')->nullable()->after('file_name');});
  Schema::create('meeting_attendances',function(Blueprint $t){$t->id();$t->foreignId('learning_meeting_id')->constrained()->cascadeOnDelete();$t->foreignId('student_id')->constrained('users')->cascadeOnDelete();$t->enum('status',['hadir','sakit','izin','alpa']);$t->text('note')->nullable();$t->foreignId('recorded_by')->constrained('users');$t->timestamps();$t->unique(['learning_meeting_id','student_id']);});
  Schema::create('quiz_questions',function(Blueprint $t){$t->id();$t->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();$t->text('question_text');$t->json('options');$t->string('correct_option',1);$t->unsignedSmallInteger('points')->default(10);$t->timestamps();});
  Schema::create('quiz_attempts',function(Blueprint $t){$t->id();$t->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();$t->foreignId('student_id')->constrained('users')->cascadeOnDelete();$t->unsignedSmallInteger('score')->default(0);$t->unsignedSmallInteger('max_score')->default(0);$t->timestamp('submitted_at');$t->timestamps();$t->unique(['quiz_id','student_id']);});
  Schema::create('quiz_answers',function(Blueprint $t){$t->id();$t->foreignId('quiz_attempt_id')->constrained()->cascadeOnDelete();$t->foreignId('quiz_question_id')->constrained()->cascadeOnDelete();$t->string('selected_option',1)->nullable();$t->boolean('is_correct')->default(false);$t->unsignedSmallInteger('score')->default(0);$t->timestamps();$t->unique(['quiz_attempt_id','quiz_question_id']);});
 }
 public function down():void{Schema::dropIfExists('quiz_answers');Schema::dropIfExists('quiz_attempts');Schema::dropIfExists('quiz_questions');Schema::dropIfExists('meeting_attendances');Schema::table('materials',fn(Blueprint $t)=>$t->dropColumn(['file_path','file_name','mime_type']));}
};
