<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->get('/')->assertRedirect('/login');
        $this->get('/login')->assertOk()->assertSee('Masuk ke akun Anda');
    }

    public function test_registered_active_user_can_login(): void
    {
        $user = User::factory()->create(['role' => 'student', 'is_active' => true, 'password' => 'password']);
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_non_admin_cannot_open_user_management(): void
    {
        $user = User::factory()->create(['role' => 'student', 'is_active' => true]);
        $this->actingAs($user)->get('/pengguna')->assertForbidden();
    }

    public function test_student_can_record_check_in_and_check_out_once(): void
    {
        $student = User::factory()->create(['role'=>'student','is_active'=>true]);
        $this->actingAs($student)->post('/presensi/catat',['action'=>'check_in','latitude'=>-6.2,'longitude'=>106.8])->assertRedirect();
        $this->assertTrue(\Illuminate\Support\Facades\DB::table('attendances')->where('student_id',$student->id)->whereDate('date',today())->exists());
        $this->actingAs($student)->post('/presensi/catat',['action'=>'check_out','latitude'=>-6.2,'longitude'=>106.8])->assertRedirect();
        $this->assertNotNull(\Illuminate\Support\Facades\DB::table('attendances')->where('student_id',$student->id)->value('check_out'));
    }

    public function test_teacher_attendance_requires_admin_qr(): void
    {
        $teacher = User::factory()->create(['role'=>'teacher','is_active'=>true]);
        $this->actingAs($teacher)->post('/presensi/catat',['action'=>'check_in'])->assertRedirect()->assertSessionHas('error');
        $this->assertDatabaseMissing('attendances',['student_id'=>$teacher->id]);
    }

    public function test_admin_can_open_station_and_rotate_a_qr_token(): void
    {
        \Illuminate\Support\Facades\Event::fake();
        $admin=User::factory()->create(['role'=>'admin','is_active'=>true]);
        $response=$this->actingAs($admin)->post('/stasiun-qr',['label'=>'Gerbang Test','audience'=>'student']);
        $stationId=\Illuminate\Support\Facades\DB::table('qr_stations')->value('id');
        $response->assertRedirect(route('stations.show',$stationId));
        $this->actingAs($admin)->getJson("/stasiun-qr/{$stationId}/token")->assertOk()->assertJsonStructure(['scan_url','expires_at']);
        $this->assertDatabaseCount('qr_tokens',1);
    }

    public function test_present_teacher_can_open_student_qr_station(): void
    {
        \Illuminate\Support\Facades\Event::fake();
        $admin=User::factory()->create(['role'=>'admin','is_active'=>true]);
        $teacher=User::factory()->create(['role'=>'teacher','is_active'=>true]);
        $this->actingAs($teacher)->post('/stasiun-qr',['label'=>'Kelas','audience'=>'student'])->assertForbidden();
        $this->actingAs($admin)->post('/stasiun-qr',['label'=>'QR Guru','audience'=>'teacher']);
        $station=\Illuminate\Support\Facades\DB::table('qr_stations')->where('audience','teacher')->first();
        $payload=$this->actingAs($admin)->getJson("/stasiun-qr/{$station->id}/token")->json();
        $token=basename($payload['scan_url']);
        $this->actingAs($teacher)->post('/presensi/catat',['action'=>'check_in','qr_token'=>$token])->assertRedirect()->assertSessionHas('status');
        $this->actingAs($teacher)->post('/stasiun-qr',['label'=>'Kelas','audience'=>'student'])->assertRedirect();
        $this->assertDatabaseHas('qr_stations',['label'=>'Kelas','audience'=>'student','opened_by'=>$teacher->id]);
    }

    public function test_portal_navigation_is_available_and_adjusted_by_role(): void
    {
        $student=User::factory()->create(['role'=>'student','is_active'=>true]);
        foreach(['/dashboard','/jelajahi','/akademik','/obrolan','/akun'] as $path) {
            $this->actingAs($student)->get($path)->assertOk();
        }
        $this->actingAs($student)->get('/akun')->assertSee('Kelola Profil')->assertDontSee('Kelola Presensi');
        $teacher=User::factory()->create(['role'=>'teacher','is_active'=>true]);
        $this->actingAs($teacher)->get('/akun')->assertOk()->assertSee('Kelola Presensi');
    }

    public function test_demo_seeder_creates_requested_simulation_data(): void
    {
        $this->seed();
        $this->assertDatabaseCount('users',40);
        $this->assertSame(3,User::where('role','admin')->count());
        $this->assertSame(6,User::where('role','teacher')->count());
        $this->assertSame(21,User::where('role','student')->count());
        $this->assertSame(10,User::where('role','parent')->count());
        $this->assertDatabaseCount('school_classes',3);
        $this->assertDatabaseCount('class_students',21);
        $this->assertDatabaseCount('parent_student',21);
        $this->assertDatabaseCount('learning_meetings',18);
        $this->assertDatabaseCount('assignments',18);
    }

    public function test_teacher_has_camera_scanner_and_student_profile_fields_are_role_specific(): void
    {
        $teacher=User::factory()->create(['role'=>'teacher','is_active'=>true]);
        $this->actingAs($teacher)->get('/presensi/pindai')->assertOk()->assertSee('Buka kamera');
        $student=User::factory()->create(['role'=>'student','is_active'=>true]);
        $this->actingAs($student)->get('/profil')->assertOk()->assertSee('NISN')->assertDontSee('Pekerjaan/Jabatan');
    }

    public function test_seeded_teacher_can_create_meeting_and_share_assignment(): void
    {
        $this->seed();
        $teacher=User::where('email','guru1@lumora.test')->firstOrFail();
        $session=\Illuminate\Support\Facades\DB::table('class_sessions')->where('teacher_id',$teacher->id)->first();
        $this->actingAs($teacher)->post("/akademik/sesi/{$session->id}/pertemuan",['meeting_number'=>2,'title'=>'Simulasi Pertemuan Kedua','meeting_date'=>today()->addWeek()->toDateString(),'status'=>'published'])->assertRedirect()->assertSessionHas('status');
        $meeting=\Illuminate\Support\Facades\DB::table('learning_meetings')->where('class_session_id',$session->id)->where('meeting_number',2)->first();
        $this->actingAs($teacher)->post("/akademik/sesi/{$session->id}/tugas",['learning_meeting_id'=>$meeting->id,'title'=>'Tugas Simulasi','description'=>'Tugas untuk pengujian alur.','due_date'=>now()->addWeeks(2)->format('Y-m-d H:i:s'),'max_score'=>100])->assertRedirect()->assertSessionHas('status');
        $this->assertDatabaseHas('assignments',['class_session_id'=>$session->id,'learning_meeting_id'=>$meeting->id,'title'=>'Tugas Simulasi']);
    }

    public function test_admin_cannot_manage_or_open_assignments(): void
    {
        $this->seed();
        $admin=User::where('email','admin1@lumora.test')->firstOrFail();
        $session=\Illuminate\Support\Facades\DB::table('class_sessions')->first();
        $assignment=\Illuminate\Support\Facades\DB::table('assignments')->where('class_session_id',$session->id)->first();
        $this->actingAs($admin)->get('/tugas')->assertForbidden();
        $this->actingAs($admin)->get("/akademik/sesi/{$session->id}")->assertOk()->assertDontSee($assignment->title);
        $this->actingAs($admin)->post("/akademik/sesi/{$session->id}/pertemuan",['meeting_number'=>9,'title'=>'Tidak Diizinkan','meeting_date'=>today(),'status'=>'published'])->assertForbidden();
    }

    public function test_admin_dashboard_and_role_directory_are_comprehensive(): void
    {
        $this->seed();
        $admin=User::where('email','admin1@lumora.test')->firstOrFail();
        $this->actingAs($admin)->get('/dashboard')->assertOk()->assertSee('PUSAT KENDALI ADMIN')->assertSee('Kapasitas kelas')->assertSee('PENGGUNA TERBARU')->assertSee('Presensi & Stasiun QR',false);
        $this->actingAs($admin)->get('/pengguna?role=teacher')->assertOk()->assertSee('Budi Santoso')->assertDontSee('Alya Lumora');
        $this->actingAs($admin)->get('/pengguna?role=student')->assertOk()->assertSee('Alya Lumora')->assertDontSee('Budi Santoso');
    }

    public function test_teacher_can_generate_recurring_meetings_in_one_action(): void
    {
        $this->seed(); $teacher=User::where('email','guru1@lumora.test')->firstOrFail(); $session=\Illuminate\Support\Facades\DB::table('class_sessions')->where('teacher_id',$teacher->id)->first();
        $before=\Illuminate\Support\Facades\DB::table('learning_meetings')->where('class_session_id',$session->id)->count();
        $this->actingAs($teacher)->post("/akademik/sesi/{$session->id}/pertemuan-berulang",['title'=>'Pola Matematika','date_start'=>'2026-10-01','date_end'=>'2026-10-03','recurrence'=>'daily','start_time'=>'08:00','end_time'=>'09:00','location'=>'Ruang 10-A','status'=>'published'])->assertRedirect()->assertSessionHas('status');
        $this->assertSame($before+3,\Illuminate\Support\Facades\DB::table('learning_meetings')->where('class_session_id',$session->id)->count());
        $this->assertDatabaseHas('learning_meetings',['class_session_id'=>$session->id,'start_time'=>'08:00','end_time'=>'09:00','location'=>'Ruang 10-A']);
    }

    public function test_parent_leave_request_can_be_approved_and_updates_attendance(): void
    {
        Storage::fake('public'); $this->seed(); $parent=User::where('email','wali1@lumora.test')->firstOrFail(); $student=$parent->children()->firstOrFail();
        $this->actingAs($parent)->post('/pengajuan-kehadiran',['student_id'=>$student->id,'type'=>'sakit','date_start'=>today()->toDateString(),'date_end'=>today()->toDateString(),'reason'=>'Demam dan perlu istirahat.','evidence'=>UploadedFile::fake()->image('surat.jpg')])->assertRedirect()->assertSessionHas('status');
        $leave=\Illuminate\Support\Facades\DB::table('leave_requests')->where('student_id',$student->id)->first(); $teacher=User::where('email','guru1@lumora.test')->firstOrFail();
        $this->actingAs($teacher)->patch("/pengajuan-kehadiran/{$leave->id}",['decision'=>'approved','review_note'=>'Bukti sesuai'])->assertRedirect()->assertSessionHas('status');
        $this->assertDatabaseHas('leave_requests',['id'=>$leave->id,'status'=>'approved','reviewed_by'=>$teacher->id]);
        $this->assertDatabaseHas('attendances',['student_id'=>$student->id,'date'=>today()->toDateString(),'status'=>'sakit','leave_request_id'=>$leave->id]);
        $admin=User::where('email','admin1@lumora.test')->firstOrFail(); $response=$this->actingAs($admin)->get('/presensi?date='.today()->toDateString())->assertOk();
        $this->assertSame(1,$response->viewData('summary')['sakit']); $this->assertGreaterThan(0,$response->viewData('summary')['alpa']);
    }

    public function test_attendance_finalizer_persists_alfa_for_missing_students(): void
    {
        $this->seed(); $date=today()->subDay()->toDateString(); $this->artisan('attendance:finalize',['date'=>$date])->assertSuccessful();
        $this->assertSame(21,\Illuminate\Support\Facades\DB::table('attendances')->whereDate('date',$date)->where('status','alpa')->count());
    }

    public function test_student_can_read_material_and_upload_assignment_file(): void
    {
        Storage::fake('public');
        $this->seed();
        $student=User::where('email','murid10.1@lumora.test')->firstOrFail();
        $session=\Illuminate\Support\Facades\DB::table('class_sessions')->where('school_class_id',\Illuminate\Support\Facades\DB::table('class_students')->where('student_id',$student->id)->value('school_class_id'))->first();
        $assignment=\Illuminate\Support\Facades\DB::table('assignments')->where('class_session_id',$session->id)->first();
        $this->actingAs($student)->get("/akademik/sesi/{$session->id}")->assertOk()->assertSee('Materi Pertemuan 1')->assertSee('Ringkasan materi awal');
        $this->actingAs($student)->get('/tugas')->assertOk()->assertSee($assignment->title)->assertSee('Unggah file jawaban');
        $this->actingAs($student)->post("/tugas/{$assignment->id}/kumpulkan",['file'=>UploadedFile::fake()->create('jawaban.pdf',100,'application/pdf')])->assertRedirect()->assertSessionHas('status');
        $submission=\Illuminate\Support\Facades\DB::table('assignment_submissions')->where('assignment_id',$assignment->id)->where('student_id',$student->id)->first();
        $this->assertNotNull($submission);
        Storage::disk('public')->assertExists($submission->drive_file_id);
    }

    public function test_admin_can_update_role_and_parent_student_relation(): void
    {
        $admin=User::factory()->create(['role'=>'admin','is_active'=>true]);
        $parent=User::factory()->create(['role'=>'parent','is_active'=>true]);
        $student=User::factory()->create(['role'=>'student','is_active'=>true]);
        $this->actingAs($admin)->put("/pengguna/{$parent->id}/relasi",['student_ids'=>[$student->id]])->assertRedirect();
        $this->assertDatabaseHas('parent_student',['parent_id'=>$parent->id,'student_id'=>$student->id]);
        $this->actingAs($admin)->put("/pengguna/{$student->id}",['name'=>$student->name,'email'=>$student->email,'phone'=>'08123','role'=>'teacher','is_active'=>1])->assertRedirect();
        $this->assertDatabaseHas('users',['id'=>$student->id,'role'=>'teacher']);
        $this->assertDatabaseMissing('parent_student',['student_id'=>$student->id]);
    }

    public function test_user_can_update_own_profile_and_password(): void
    {
        $user=User::factory()->create(['role'=>'teacher','is_active'=>true,'password'=>'password']);
        $this->actingAs($user)->put('/profil',['name'=>'Nama Baru','email'=>$user->email,'phone'=>'081234'])->assertRedirect();
        $this->assertDatabaseHas('users',['id'=>$user->id,'name'=>'Nama Baru','phone'=>'081234']);
        $this->actingAs($user)->put('/profil/password',['current_password'=>'password','password'=>'password-baru','password_confirmation'=>'password-baru'])->assertRedirect();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('password-baru',$user->fresh()->password));
    }

    public function test_student_can_only_have_one_parent_while_parent_has_many_students(): void
    {
        $admin=User::factory()->create(['role'=>'admin','is_active'=>true]);
        $firstParent=User::factory()->create(['role'=>'parent','is_active'=>true]);
        $secondParent=User::factory()->create(['role'=>'parent','is_active'=>true]);
        $studentA=User::factory()->create(['role'=>'student','is_active'=>true]);
        $studentB=User::factory()->create(['role'=>'student','is_active'=>true]);
        $this->actingAs($admin)->put("/pengguna/{$firstParent->id}/relasi",['student_ids'=>[$studentA->id,$studentB->id]]);
        $this->assertDatabaseCount('parent_student',2);
        $this->actingAs($admin)->put("/pengguna/{$secondParent->id}/relasi",['student_ids'=>[$studentA->id]]);
        $this->assertDatabaseHas('parent_student',['parent_id'=>$secondParent->id,'student_id'=>$studentA->id]);
        $this->assertDatabaseMissing('parent_student',['parent_id'=>$firstParent->id,'student_id'=>$studentA->id]);
        $this->assertEquals(1,\Illuminate\Support\Facades\DB::table('parent_student')->where('student_id',$studentA->id)->count());
    }

    public function test_only_admin_and_teacher_dashboards_contain_analytics(): void
    {
        foreach(['admin'=>true,'teacher'=>true,'student'=>false,'parent'=>false] as $role=>$visible){
            $user=User::factory()->create(['role'=>$role,'is_active'=>true]);
            $response=$this->actingAs($user)->get('/dashboard');
            $visible ? $response->assertSee('Ringkasan dalam grafik') : $response->assertDontSee('Ringkasan dalam grafik');
            $response->assertSee('KALENDER AKADEMIK');
        }
    }

    public function test_academic_data_is_scoped_by_role_relationships(): void
    {
        $teacherA=User::factory()->create(['role'=>'teacher','is_active'=>true]); $teacherB=User::factory()->create(['role'=>'teacher','is_active'=>true]);
        $studentA=User::factory()->create(['role'=>'student','is_active'=>true]); $studentB=User::factory()->create(['role'=>'student','is_active'=>true]);
        $parent=User::factory()->create(['role'=>'parent','is_active'=>true]);
        $year=\Illuminate\Support\Facades\DB::table('academic_years')->insertGetId(['year_label'=>'2026/2027','start_date'=>'2026-07-01','end_date'=>'2027-06-30','is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        $subject=\Illuminate\Support\Facades\DB::table('subjects')->insertGetId(['name'=>'Matematika','code'=>'T-MAT','created_at'=>now(),'updated_at'=>now()]);
        $classA=\Illuminate\Support\Facades\DB::table('school_classes')->insertGetId(['academic_year_id'=>$year,'grade_level'=>10,'name'=>'X-A','homeroom_teacher_id'=>$teacherA->id,'created_at'=>now(),'updated_at'=>now()]);
        $classB=\Illuminate\Support\Facades\DB::table('school_classes')->insertGetId(['academic_year_id'=>$year,'grade_level'=>11,'name'=>'XI-B','homeroom_teacher_id'=>$teacherB->id,'created_at'=>now(),'updated_at'=>now()]);
        foreach([[$classA,$studentA->id],[$classB,$studentB->id]] as [$class,$student])\Illuminate\Support\Facades\DB::table('class_students')->insert(['school_class_id'=>$class,'student_id'=>$student,'status'=>'active','created_at'=>now(),'updated_at'=>now()]);
        \Illuminate\Support\Facades\DB::table('parent_student')->insert(['parent_id'=>$parent->id,'student_id'=>$studentA->id]);
        foreach([[$classA,$teacherA->id,'Ruang A'],[$classB,$teacherB->id,'Ruang B']] as [$class,$teacher,$room])\Illuminate\Support\Facades\DB::table('class_sessions')->insert(['school_class_id'=>$class,'subject_id'=>$subject,'teacher_id'=>$teacher,'room_name'=>$room,'day'=>'Senin','start_time'=>'07:00','end_time'=>'08:00','created_at'=>now(),'updated_at'=>now()]);
        $this->actingAs($teacherA)->get('/akademik')->assertSee('X-A')->assertDontSee('XI-B');
        $this->actingAs($studentA)->get('/akademik')->assertSee('X-A')->assertDontSee('XI-B');
        $this->actingAs($parent)->get('/akademik')->assertSee('X-A')->assertDontSee('XI-B');
        $admin=User::factory()->create(['role'=>'admin','is_active'=>true]); $this->actingAs($admin)->get('/akademik')->assertSee('X-A')->assertSee('XI-B');
    }

    public function test_teacher_can_create_pg_question_and_student_is_scored_automatically(): void
    {
        $this->seed();
        $teacher=User::where('email','guru1@lumora.test')->firstOrFail();
        $quiz=\Illuminate\Support\Facades\DB::table('quizzes')->where('created_by',$teacher->id)->first();
        $sessionId=\Illuminate\Support\Facades\DB::table('learning_meetings')->where('id',$quiz->learning_meeting_id)->value('class_session_id');
        $classId=\Illuminate\Support\Facades\DB::table('class_sessions')->where('id',$sessionId)->value('school_class_id');
        $student=User::findOrFail(\Illuminate\Support\Facades\DB::table('class_students')->where('school_class_id',$classId)->value('student_id'));
        $this->actingAs($teacher)->post("/kuis/{$quiz->id}/soal",['question_text'=>'Ibukota Indonesia?','option_a'=>'Jakarta','option_b'=>'Bandung','option_c'=>'Surabaya','option_d'=>'Medan','correct_option'=>'A','points'=>20])->assertRedirect();
        $questions=\Illuminate\Support\Facades\DB::table('quiz_questions')->where('quiz_id',$quiz->id)->get();
        $answers=$questions->mapWithKeys(fn($q)=>[$q->id=>$q->correct_option])->all();
        $this->actingAs($student)->post("/kuis/{$quiz->id}/kumpulkan",['answers'=>$answers])->assertRedirect()->assertSessionHas('status');
        $attempt=\Illuminate\Support\Facades\DB::table('quiz_attempts')->where('quiz_id',$quiz->id)->where('student_id',$student->id)->first();
        $this->assertSame((int)$attempt->max_score,(int)$attempt->score);
    }

    public function test_teacher_can_record_manual_attendance_for_class_meeting(): void
    {
        $this->seed();
        $teacher=User::where('email','guru1@lumora.test')->firstOrFail();
        $session=\Illuminate\Support\Facades\DB::table('class_sessions')->where('teacher_id',$teacher->id)->first();
        $meeting=\Illuminate\Support\Facades\DB::table('learning_meetings')->where('class_session_id',$session->id)->first();
        $studentIds=\Illuminate\Support\Facades\DB::table('class_students')->where('school_class_id',$session->school_class_id)->pluck('student_id');
        $statuses=$studentIds->mapWithKeys(fn($id)=>[$id=>'hadir'])->all();
        $this->actingAs($teacher)->post("/akademik/sesi/{$session->id}/pertemuan/{$meeting->id}/presensi",['statuses'=>$statuses])->assertRedirect()->assertSessionHas('status');
        $this->assertSame($studentIds->count(),\Illuminate\Support\Facades\DB::table('meeting_attendances')->where('learning_meeting_id',$meeting->id)->count());
    }
}
