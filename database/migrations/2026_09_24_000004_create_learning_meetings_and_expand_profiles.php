<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function(Blueprint $t){ $t->string('identity_number')->nullable(); $t->string('gender',20)->nullable(); $t->string('birth_place')->nullable(); $t->date('birth_date')->nullable(); $t->text('address')->nullable(); $t->text('bio')->nullable(); $t->string('occupation')->nullable(); $t->string('emergency_contact_name')->nullable(); $t->string('emergency_contact_phone',30)->nullable(); });
        Schema::create('learning_meetings', function(Blueprint $t){ $t->id(); $t->foreignId('class_session_id')->constrained()->cascadeOnDelete(); $t->unsignedSmallInteger('meeting_number'); $t->string('title'); $t->date('meeting_date'); $t->enum('status',['draft','published','completed'])->default('draft'); $t->foreignId('created_by')->constrained('users'); $t->timestamps(); $t->unique(['class_session_id','meeting_number']); });
        Schema::create('materials', function(Blueprint $t){ $t->id(); $t->foreignId('learning_meeting_id')->constrained()->cascadeOnDelete(); $t->string('title'); $t->enum('type',['text','link','video','document'])->default('text'); $t->text('content')->nullable(); $t->text('url')->nullable(); $t->foreignId('uploaded_by')->constrained('users'); $t->timestamps(); });
        Schema::create('quizzes', function(Blueprint $t){ $t->id(); $t->foreignId('learning_meeting_id')->constrained()->cascadeOnDelete(); $t->string('title'); $t->unsignedSmallInteger('duration_minutes')->default(30); $t->enum('status',['draft','published','closed'])->default('draft'); $t->foreignId('created_by')->constrained('users'); $t->timestamps(); });
        Schema::table('assignments', fn(Blueprint $t)=>$t->foreignId('learning_meeting_id')->nullable()->after('class_session_id')->constrained()->nullOnDelete());
    }
    public function down(): void
    {
        Schema::table('assignments', fn(Blueprint $t)=>$t->dropConstrainedForeignId('learning_meeting_id'));
        Schema::dropIfExists('quizzes'); Schema::dropIfExists('materials'); Schema::dropIfExists('learning_meetings');
        Schema::table('users', fn(Blueprint $t)=>$t->dropColumn(['identity_number','gender','birth_place','birth_date','address','bio','occupation','emergency_contact_name','emergency_contact_phone']));
    }
};
