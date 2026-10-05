<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('report_cards',function(Blueprint $t){$t->id();$t->foreignId('class_session_id')->constrained()->cascadeOnDelete();$t->string('semester',20);$t->enum('status',['draft','submitted','approved','rejected'])->default('draft');$t->foreignId('created_by')->constrained('users');$t->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();$t->text('review_note')->nullable();$t->timestamp('submitted_at')->nullable();$t->timestamp('approved_at')->nullable();$t->timestamps();$t->unique(['class_session_id','semester']);});
        Schema::create('report_grades',function(Blueprint $t){$t->id();$t->foreignId('report_card_id')->constrained()->cascadeOnDelete();$t->foreignId('student_id')->constrained('users')->cascadeOnDelete();$t->decimal('assignment_score',5,2)->default(0);$t->decimal('quiz_score',5,2)->default(0);$t->decimal('mid_score',5,2)->default(0);$t->decimal('final_exam_score',5,2)->default(0);$t->decimal('final_score',5,2)->default(0);$t->string('predicate',2)->nullable();$t->text('teacher_note')->nullable();$t->timestamps();$t->unique(['report_card_id','student_id']);});
    }
    public function down(): void { Schema::dropIfExists('report_grades');Schema::dropIfExists('report_cards'); }
};
