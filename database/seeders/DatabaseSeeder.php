<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now=now();
        $admins=collect(['Nadia Pratama','Rizky Firmansyah','Siti Rahmawati'])->map(fn($name,$i)=>User::create(['name'=>$name,'email'=>'admin'.($i+1).'@lumora.test','password'=>'password','role'=>'admin','phone'=>'081110000'.($i+1),'nik'=>'327301000000'.str_pad((string)($i+1),4,'0',STR_PAD_LEFT),'occupation'=>'Administrator Sekolah']));
        $teacherNames=['Budi Santoso, S.Pd.','Dewi Lestari, S.Pd.','Ahmad Fauzan, S.Kom.','Rina Marlina, M.Pd.','Dedi Kurniawan, S.Pd.','Laila Nuraini, S.Si.'];
        $teachers=collect($teacherNames)->map(fn($name,$i)=>User::create(['name'=>$name,'email'=>'guru'.($i+1).'@lumora.test','password'=>'password','role'=>'teacher','phone'=>'082220000'.($i+1),'nik'=>'327302000000'.str_pad((string)($i+1),4,'0',STR_PAD_LEFT),'nip'=>'198'.($i+1).'092520260'.($i+1),'occupation'=>'Guru']));

        $studentFirst=['Alya','Bagas','Citra','Dimas','Elina','Fajar','Gita','Hafiz','Intan','Jovan','Keyla','Luthfi','Maira','Naufal','Olivia','Putra','Qanita','Rafi','Salma','Tegar','Vania'];
        $students=collect();
        foreach([10,11,12] as $classIndex=>$grade){
            for($seat=1;$seat<=7;$seat++){
                $index=$classIndex*7+$seat-1; $serial=$index+1;
                $students->push(User::create(['name'=>$studentFirst[$index].' Lumora','email'=>'murid'.$grade.'.'.$seat.'@lumora.test','password'=>'password','role'=>'student','phone'=>'0833300'.str_pad((string)$serial,3,'0',STR_PAD_LEFT),'nik'=>'327303000000'.str_pad((string)$serial,4,'0',STR_PAD_LEFT),'nis'=>'LMR-'.($grade).'-'.str_pad((string)$seat,2,'0',STR_PAD_LEFT),'nisn'=>'009876'.str_pad((string)$serial,4,'0',STR_PAD_LEFT),'gender'=>$serial%2?'Perempuan':'Laki-laki','birth_place'=>'Sukabumi','birth_date'=>now()->subYears(14+$classIndex)->subDays($serial)->toDateString()]));
            }
        }

        $parentNames=['Andi Wijaya','Maya Puspita','Rudi Hartono','Nina Kusuma','Agus Setiawan','Yuni Kartika','Hendra Gunawan','Fitri Handayani','Wahyu Hidayat','Ratna Sari'];
        $parents=collect($parentNames)->map(fn($name,$i)=>User::create(['name'=>$name,'email'=>'wali'.($i+1).'@lumora.test','password'=>'password','role'=>'parent','phone'=>'084440000'.($i+1),'nik'=>'327304000000'.str_pad((string)($i+1),4,'0',STR_PAD_LEFT),'occupation'=>['Wiraswasta','Ibu Rumah Tangga','Karyawan Swasta','Tenaga Kesehatan'][$i%4]]));
        $childGroups=[[0,1,2],[3,4,5],[6,7,8],[9,10,11],[12,13],[14,15],[16,17],[18],[19],[20]];
        foreach($childGroups as $parentIndex=>$children) foreach($children as $studentIndex) DB::table('parent_student')->insert(['parent_id'=>$parents[$parentIndex]->id,'student_id'=>$students[$studentIndex]->id]);

        $year=DB::table('academic_years')->insertGetId(['year_label'=>'2026/2027','start_date'=>'2026-07-13','end_date'=>'2027-06-25','is_active'=>1,'created_at'=>$now,'updated_at'=>$now]);
        $major=DB::table('majors')->insertGetId(['name'=>'Umum','code'=>'UMUM','created_at'=>$now,'updated_at'=>$now]);
        $classes=collect();
        foreach([10,11,12] as $i=>$grade) $classes->push(DB::table('school_classes')->insertGetId(['academic_year_id'=>$year,'grade_level'=>$grade,'major_id'=>$major,'name'=>'Kelas '.$grade.' A','homeroom_teacher_id'=>$teachers[$i]->id,'created_at'=>$now,'updated_at'=>$now]));
        foreach($classes as $classIndex=>$classId) for($seat=0;$seat<7;$seat++) DB::table('class_students')->insert(['school_class_id'=>$classId,'student_id'=>$students[$classIndex*7+$seat]->id,'status'=>'active','created_at'=>$now,'updated_at'=>$now]);

        $subjectData=[['Matematika','MAT'],['Bahasa Indonesia','BIN'],['Bahasa Inggris','BIG'],['Informatika','INF'],['Biologi','BIO'],['Fisika','FIS']];
        $subjects=collect($subjectData)->mapWithKeys(fn($subject)=>[$subject[1]=>DB::table('subjects')->insertGetId(['name'=>$subject[0],'code'=>$subject[1],'created_at'=>$now,'updated_at'=>$now])]);
        $days=['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        foreach($classes as $classIndex=>$classId){
            foreach($subjectData as $subjectIndex=>[$subjectName,$code]){
                $start=sprintf('%02d:00',7+($subjectIndex%3)*2); $end=sprintf('%02d:30',8+($subjectIndex%3)*2);
                $session=DB::table('class_sessions')->insertGetId(['school_class_id'=>$classId,'subject_id'=>$subjects[$code],'teacher_id'=>$teachers[$subjectIndex]->id,'room_name'=>$code==='INF'?'Lab Komputer':'Ruang '.(10+$classIndex).'-A','day'=>$days[($subjectIndex+$classIndex)%6],'start_time'=>$start,'end_time'=>$end,'created_at'=>$now,'updated_at'=>$now]);
                $meeting=DB::table('learning_meetings')->insertGetId(['class_session_id'=>$session,'meeting_number'=>1,'title'=>'Pengenalan '.$subjectName,'meeting_date'=>today()->addDays($subjectIndex)->toDateString(),'status'=>'published','created_by'=>$teachers[$subjectIndex]->id,'created_at'=>$now,'updated_at'=>$now]);
                DB::table('materials')->insert(['learning_meeting_id'=>$meeting,'title'=>'Materi Pertemuan 1','type'=>'text','content'=>'Ringkasan materi awal '.$subjectName.' untuk kelas '.(10+$classIndex).'.','uploaded_by'=>$teachers[$subjectIndex]->id,'created_at'=>$now,'updated_at'=>$now]);
                DB::table('assignments')->insert(['class_session_id'=>$session,'learning_meeting_id'=>$meeting,'title'=>'Tugas Awal '.$subjectName,'description'=>'Pelajari materi pertemuan pertama dan kerjakan latihan yang diberikan.','due_date'=>now()->addDays(7+$subjectIndex),'max_score'=>100,'created_at'=>$now,'updated_at'=>$now]);
                DB::table('quizzes')->insert(['learning_meeting_id'=>$meeting,'title'=>'Kuis Pembuka '.$subjectName,'duration_minutes'=>20,'status'=>'published','created_by'=>$teachers[$subjectIndex]->id,'created_at'=>$now,'updated_at'=>$now]);
            }
        }

        foreach($students as $student) DB::table('spp_bills')->insert(['student_id'=>$student->id,'month'=>9,'year'=>2026,'amount'=>450000,'status'=>'pending','created_at'=>$now,'updated_at'=>$now]);
        DB::table('announcements')->insert([['title'=>'Selamat Datang di Lumora','content'=>'Portal akademik dan presensi sekolah siap digunakan untuk simulasi.','target_role'=>null,'created_by'=>$admins[0]->id,'published_at'=>$now,'created_at'=>$now,'updated_at'=>$now],['title'=>'Simulasi Pembelajaran','content'=>'Guru dapat membuka kelas, membuat pertemuan, membagikan materi, tugas, dan kuis.','target_role'=>'teacher','created_by'=>$admins[0]->id,'published_at'=>$now,'created_at'=>$now,'updated_at'=>$now]]);
        DB::table('school_calendar')->insert([['title'=>'Asesmen Tengah Semester','type'=>'ujian','date_start'=>today()->addDays(11),'date_end'=>today()->addDays(15),'target_role'=>null,'created_by'=>$admins[0]->id,'created_at'=>$now,'updated_at'=>$now],['title'=>'Pertemuan Orang Tua','type'=>'agenda','date_start'=>today()->addDays(7),'date_end'=>today()->addDays(7),'target_role'=>'parent','created_by'=>$admins[0]->id,'created_at'=>$now,'updated_at'=>$now]]);
        $this->call(DemoQuizQuestionSeeder::class);
    }
}
