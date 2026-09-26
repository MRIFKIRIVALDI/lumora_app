<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('attendance:finalize {date?}', function () {
    $date=$this->argument('date')?:today()->subDay()->toDateString(); $created=0;
    DB::table('users')->where('role','student')->where('is_active',true)->pluck('id')->each(function($id)use($date,&$created){if(!DB::table('attendances')->where('student_id',$id)->whereDate('date',$date)->exists()){DB::table('attendances')->insert(['student_id'=>$id,'date'=>$date,'status'=>'alpa','created_at'=>now(),'updated_at'=>now()]);$created++;}});
    $this->info("{$created} status alfa dibuat untuk {$date}.");
})->purpose('Finalize missing student attendance as alfa');

Schedule::command('attendance:finalize')->dailyAt('00:05');
