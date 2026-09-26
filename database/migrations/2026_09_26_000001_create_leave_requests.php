<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('leave_requests',function(Blueprint $t){$t->id();$t->foreignId('student_id')->constrained('users')->cascadeOnDelete();$t->foreignId('submitted_by')->constrained('users')->cascadeOnDelete();$t->enum('type',['izin','sakit']);$t->date('date_start');$t->date('date_end');$t->text('reason');$t->string('evidence_path')->nullable();$t->string('evidence_name')->nullable();$t->enum('status',['pending','approved','rejected'])->default('pending')->index();$t->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();$t->text('review_note')->nullable();$t->timestamp('reviewed_at')->nullable();$t->timestamps();}); Schema::table('attendances',fn(Blueprint $t)=>$t->foreignId('leave_request_id')->nullable()->after('qr_token_id')->constrained('leave_requests')->nullOnDelete()); }
 public function down(): void { Schema::table('attendances',fn(Blueprint $t)=>$t->dropConstrainedForeignId('leave_request_id'));Schema::dropIfExists('leave_requests'); }
};
