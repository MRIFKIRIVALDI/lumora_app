<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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

    public function test_non_student_cannot_submit_student_attendance(): void
    {
        $teacher = User::factory()->create(['role'=>'teacher','is_active'=>true]);
        $this->actingAs($teacher)->post('/presensi/catat',['action'=>'check_in'])->assertForbidden();
    }

    public function test_admin_can_open_station_and_rotate_a_qr_token(): void
    {
        \Illuminate\Support\Facades\Event::fake();
        $admin=User::factory()->create(['role'=>'admin','is_active'=>true]);
        $response=$this->actingAs($admin)->post('/stasiun-qr',['label'=>'Gerbang Test']);
        $stationId=\Illuminate\Support\Facades\DB::table('qr_stations')->value('id');
        $response->assertRedirect(route('stations.show',$stationId));
        $this->actingAs($admin)->getJson("/stasiun-qr/{$stationId}/token")->assertOk()->assertJsonStructure(['scan_url','expires_at']);
        $this->assertDatabaseCount('qr_tokens',1);
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
}
