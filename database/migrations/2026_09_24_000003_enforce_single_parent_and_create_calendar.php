<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('parent_student', fn(Blueprint $table) => $table->unique('student_id', 'parent_student_student_unique'));
        Schema::create('school_calendar', function (Blueprint $table) {
            $table->id(); $table->string('title'); $table->enum('type',['libur','ujian','agenda'])->default('agenda');
            $table->date('date_start'); $table->date('date_end'); $table->string('target_role')->nullable();
            $table->foreignId('created_by')->constrained('users'); $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('school_calendar');
        Schema::table('parent_student', fn(Blueprint $table) => $table->dropUnique('parent_student_student_unique'));
    }
};
