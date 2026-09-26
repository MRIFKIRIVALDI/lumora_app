<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up():void{Schema::table('learning_meetings',function(Blueprint $t){$t->time('start_time')->nullable()->after('meeting_date');$t->time('end_time')->nullable()->after('start_time');$t->string('location')->nullable()->after('end_time');});} public function down():void{Schema::table('learning_meetings',fn(Blueprint $t)=>$t->dropColumn(['start_time','end_time','location']));} };
