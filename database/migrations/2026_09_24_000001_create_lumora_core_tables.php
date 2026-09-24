<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $t) { $t->id(); $t->string('year_label'); $t->date('start_date'); $t->date('end_date'); $t->boolean('is_active')->default(false); $t->timestamps(); });
        Schema::create('majors', function (Blueprint $t) { $t->id(); $t->string('name'); $t->string('code')->unique(); $t->timestamps(); });
        Schema::create('school_classes', function (Blueprint $t) { $t->id(); $t->foreignId('academic_year_id')->constrained()->cascadeOnDelete(); $t->unsignedTinyInteger('grade_level'); $t->foreignId('major_id')->nullable()->constrained()->nullOnDelete(); $t->string('name'); $t->foreignId('homeroom_teacher_id')->nullable()->constrained('users')->nullOnDelete(); $t->timestamps(); });
        Schema::create('class_students', function (Blueprint $t) { $t->id(); $t->foreignId('school_class_id')->constrained()->cascadeOnDelete(); $t->foreignId('student_id')->constrained('users')->cascadeOnDelete(); $t->enum('status', ['active','moved','graduated'])->default('active'); $t->timestamps(); $t->unique(['school_class_id','student_id']); });
        Schema::create('parent_student', function (Blueprint $t) { $t->foreignId('parent_id')->constrained('users')->cascadeOnDelete(); $t->foreignId('student_id')->constrained('users')->cascadeOnDelete(); $t->primary(['parent_id','student_id']); });
        Schema::create('subjects', function (Blueprint $t) { $t->id(); $t->string('name'); $t->string('code')->unique(); $t->timestamps(); });
        Schema::create('class_sessions', function (Blueprint $t) { $t->id(); $t->foreignId('school_class_id')->constrained()->cascadeOnDelete(); $t->foreignId('subject_id')->constrained()->cascadeOnDelete(); $t->foreignId('teacher_id')->constrained('users')->cascadeOnDelete(); $t->string('room_name')->nullable(); $t->string('day'); $t->time('start_time'); $t->time('end_time'); $t->timestamps(); });
        Schema::create('qr_stations', function (Blueprint $t) { $t->id(); $t->string('label'); $t->foreignId('opened_by')->constrained('users'); $t->timestamp('opened_at'); $t->timestamp('closed_at')->nullable(); $t->enum('status',['active','inactive'])->default('active')->index(); $t->timestamps(); });
        Schema::create('attendances', function (Blueprint $t) { $t->id(); $t->foreignId('student_id')->constrained('users'); $t->date('date')->index(); $t->time('check_in')->nullable(); $t->time('check_out')->nullable(); $t->enum('status',['hadir','terlambat','izin','sakit','alpa'])->index(); $t->decimal('latitude',10,7)->nullable(); $t->decimal('longitude',10,7)->nullable(); $t->foreignId('station_id')->nullable()->constrained('qr_stations')->nullOnDelete(); $t->timestamps(); $t->unique(['student_id','date']); });
        Schema::create('assignments', function (Blueprint $t) { $t->id(); $t->foreignId('class_session_id')->constrained()->cascadeOnDelete(); $t->string('title'); $t->text('description')->nullable(); $t->dateTime('due_date'); $t->unsignedSmallInteger('max_score')->default(100); $t->timestamps(); });
        Schema::create('assignment_submissions', function (Blueprint $t) { $t->id(); $t->foreignId('assignment_id')->constrained()->cascadeOnDelete(); $t->foreignId('student_id')->constrained('users')->cascadeOnDelete(); $t->text('drive_file_link'); $t->string('drive_file_id')->nullable(); $t->string('drive_file_name')->nullable(); $t->timestamp('last_verified_at')->nullable(); $t->enum('access_status',['accessible','inaccessible','unchecked'])->default('unchecked'); $t->timestamp('submitted_at'); $t->unsignedSmallInteger('score')->nullable(); $t->text('teacher_note')->nullable(); $t->timestamps(); $t->unique(['assignment_id','student_id']); });
        Schema::create('spp_bills', function (Blueprint $t) { $t->id(); $t->foreignId('student_id')->constrained('users'); $t->unsignedTinyInteger('month'); $t->unsignedSmallInteger('year'); $t->decimal('amount',12,2); $t->enum('status',['pending','paid'])->default('pending')->index(); $t->timestamp('paid_at')->nullable(); $t->timestamps(); $t->unique(['student_id','month','year']); });
        Schema::create('announcements', function (Blueprint $t) { $t->id(); $t->string('title'); $t->text('content'); $t->string('target_role')->nullable(); $t->foreignId('created_by')->constrained('users'); $t->timestamp('published_at')->nullable(); $t->timestamps(); });
    }
    public function down(): void { foreach (['announcements','spp_bills','assignment_submissions','assignments','attendances','qr_stations','class_sessions','subjects','parent_student','class_students','school_classes','majors','academic_years'] as $table) Schema::dropIfExists($table); }
};
