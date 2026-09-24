<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin=User::create(['name'=>'Nadia Pratama','email'=>'admin@lumora.test','password'=>'password','role'=>'admin','phone'=>'081234567800']);
        $teacher=User::create(['name'=>'Budi Santoso, S.Pd.','email'=>'guru@lumora.test','password'=>'password','role'=>'teacher','phone'=>'081234567801']);
        $student=User::create(['name'=>'Raka Aditya','email'=>'murid@lumora.test','password'=>'password','role'=>'student','phone'=>'081234567802']);
        $parent=User::create(['name'=>'Ibu Maya','email'=>'wali@lumora.test','password'=>'password','role'=>'parent','phone'=>'081234567803']);
        $student2=User::create(['name'=>'Alya Putri','email'=>'alya@lumora.test','password'=>'password','role'=>'student']);
        $year=DB::table('academic_years')->insertGetId(['year_label'=>'2026/2027','start_date'=>'2026-07-13','end_date'=>'2027-06-25','is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        $major=DB::table('majors')->insertGetId(['name'=>'Ilmu Pengetahuan Alam','code'=>'IPA','created_at'=>now(),'updated_at'=>now()]);
        $class=DB::table('school_classes')->insertGetId(['academic_year_id'=>$year,'grade_level'=>11,'major_id'=>$major,'name'=>'XI IPA 1','homeroom_teacher_id'=>$teacher->id,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('class_students')->insert([['school_class_id'=>$class,'student_id'=>$student->id,'status'=>'active','created_at'=>now(),'updated_at'=>now()],['school_class_id'=>$class,'student_id'=>$student2->id,'status'=>'active','created_at'=>now(),'updated_at'=>now()]]);
        DB::table('parent_student')->insert(['parent_id'=>$parent->id,'student_id'=>$student->id]);
        $subjects=[]; foreach([['Matematika','MAT'],['Fisika','FIS'],['Bahasa Indonesia','BIN'],['Biologi','BIO']] as [$name,$code]) $subjects[$code]=DB::table('subjects')->insertGetId(compact('name','code')+['created_at'=>now(),'updated_at'=>now()]);
        $sessions=[]; foreach([['MAT','Senin','07:00','08:30','Ruang 11-A'],['FIS','Senin','08:45','10:15','Lab Fisika'],['BIN','Senin','10:30','12:00','Ruang 11-A'],['BIO','Selasa','07:00','08:30','Lab Biologi']] as [$code,$day,$start,$end,$room]) $sessions[$code]=DB::table('class_sessions')->insertGetId(['school_class_id'=>$class,'subject_id'=>$subjects[$code],'teacher_id'=>$teacher->id,'room_name'=>$room,'day'=>$day,'start_time'=>$start,'end_time'=>$end,'created_at'=>now(),'updated_at'=>now()]);
        $station=DB::table('qr_stations')->insertGetId(['label'=>'Gerbang Utama','opened_by'=>$admin->id,'opened_at'=>now(),'status'=>'active','created_at'=>now(),'updated_at'=>now()]);
        DB::table('attendances')->insert(['student_id'=>$student2->id,'date'=>today(),'check_in'=>'07:08','status'=>'terlambat','latitude'=>-6.2000010,'longitude'=>106.8166650,'station_id'=>$station,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('assignments')->insert([['class_session_id'=>$sessions['MAT'],'title'=>'Latihan Persamaan Kuadrat','description'=>'Kerjakan soal halaman 42–44 dan kirim tautan Google Drive.','due_date'=>now()->addDays(2),'max_score'=>100,'created_at'=>now(),'updated_at'=>now()],['class_session_id'=>$sessions['FIS'],'title'=>'Laporan Praktikum Gerak','description'=>'Susun laporan praktikum dalam format dokumen.','due_date'=>now()->addDays(5),'max_score'=>100,'created_at'=>now(),'updated_at'=>now()]]);
        DB::table('spp_bills')->insert([['student_id'=>$student->id,'month'=>9,'year'=>2026,'amount'=>450000,'status'=>'pending','paid_at'=>null,'created_at'=>now(),'updated_at'=>now()],['student_id'=>$student2->id,'month'=>9,'year'=>2026,'amount'=>450000,'status'=>'paid','paid_at'=>now()->subDays(2),'created_at'=>now(),'updated_at'=>now()]]);
        DB::table('announcements')->insert([['title'=>'Asesmen Tengah Semester','content'=>'Pelaksanaan ATS dimulai Senin, 5 Oktober 2026.','target_role'=>null,'created_by'=>$admin->id,'published_at'=>now()->subHours(2),'created_at'=>now(),'updated_at'=>now()],['title'=>'Pengumpulan Laporan Praktikum','content'=>'Pastikan tautan Google Drive dapat diakses sebelum dikirim.','target_role'=>'student','created_by'=>$teacher->id,'published_at'=>now()->subDay(),'created_at'=>now(),'updated_at'=>now()]]);
        DB::table('school_calendar')->insert([['title'=>'Asesmen Tengah Semester','type'=>'ujian','date_start'=>now()->addDays(11)->toDateString(),'date_end'=>now()->addDays(15)->toDateString(),'target_role'=>null,'created_by'=>$admin->id,'created_at'=>now(),'updated_at'=>now()],['title'=>'Libur Maulid Nabi','type'=>'libur','date_start'=>now()->addDays(21)->toDateString(),'date_end'=>now()->addDays(21)->toDateString(),'target_role'=>null,'created_by'=>$admin->id,'created_at'=>now(),'updated_at'=>now()],['title'=>'Pertemuan Orang Tua','type'=>'agenda','date_start'=>now()->addDays(7)->toDateString(),'date_end'=>now()->addDays(7)->toDateString(),'target_role'=>'parent','created_by'=>$admin->id,'created_at'=>now(),'updated_at'=>now()]]);
    }
}
