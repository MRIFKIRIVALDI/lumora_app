<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceActionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\QrScanController;
use App\Http\Controllers\QrStationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AcademicController;
use App\Http\Controllers\LearningSessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\AssignmentSubmissionController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');
Route::middleware('guest')->group(function(){ Route::get('/login',[AuthController::class,'create'])->name('login'); Route::post('/login',[AuthController::class,'store'])->name('login.store'); });
Route::middleware('auth')->group(function(){
    Route::post('/logout',[AuthController::class,'destroy'])->name('logout');
    Route::get('/dashboard',DashboardController::class)->name('dashboard');
    Route::get('/presensi',[ModuleController::class,'attendance'])->name('attendance');
    Route::view('/presensi/pindai','stations.scanner')->middleware('role:student,teacher')->name('attendance.scanner');
    Route::post('/presensi/catat',[AttendanceActionController::class,'store'])->middleware('role:student,teacher')->name('attendance.store');
    Route::get('/presensi/scan/{token}',QrScanController::class)->middleware('role:student,teacher')->name('attendance.scan');
    Route::middleware('role:admin,teacher')->group(function(){ Route::post('/stasiun-qr',[QrStationController::class,'store'])->name('stations.store'); Route::get('/stasiun-qr/{station}',[QrStationController::class,'show'])->name('stations.show'); Route::get('/stasiun-qr/{station}/token',[QrStationController::class,'token'])->name('stations.token'); Route::patch('/stasiun-qr/{station}/tutup',[QrStationController::class,'close'])->name('stations.close'); });
    Route::get('/akademik',[ModuleController::class,'academics'])->name('academics');
    Route::get('/akademik/sesi/{session}',[LearningSessionController::class,'show'])->name('learning.show');
    Route::middleware('role:teacher')->group(function(){ Route::post('/akademik/sesi/{session}/pertemuan',[LearningSessionController::class,'meeting'])->name('learning.meetings.store'); Route::post('/akademik/sesi/{session}/pertemuan-berulang',[LearningSessionController::class,'recurringMeetings'])->name('learning.meetings.recurring'); Route::post('/akademik/sesi/{session}/materi',[LearningSessionController::class,'material'])->name('learning.materials.store'); Route::post('/akademik/sesi/{session}/tugas',[LearningSessionController::class,'assignment'])->name('learning.assignments.store'); Route::post('/akademik/sesi/{session}/kuis',[LearningSessionController::class,'quiz'])->name('learning.quizzes.store'); Route::post('/akademik/sesi/{session}/pertemuan/{meeting}/presensi',[LearningSessionController::class,'attendance'])->name('learning.attendance.store'); Route::post('/kuis/{quiz}/soal',[QuizController::class,'question'])->name('quizzes.questions.store'); });
    Route::get('/materi/{material}',[LearningSessionController::class,'materialPreview'])->name('materials.show');
    Route::get('/materi/{material}/file',[LearningSessionController::class,'materialFile'])->name('materials.file');
    Route::get('/materi/{material}/unduh',[LearningSessionController::class,'materialDownload'])->name('materials.download');
    Route::get('/kuis/{quiz}',[QuizController::class,'show'])->name('quizzes.show');
    Route::post('/kuis/{quiz}/kumpulkan',[QuizController::class,'submit'])->middleware('role:student')->name('quizzes.submit');
    Route::get('/tugas',[ModuleController::class,'assignments'])->middleware('role:teacher,student,parent')->name('assignments');
    Route::post('/tugas/{assignment}/kumpulkan',[AssignmentSubmissionController::class,'store'])->middleware('role:student')->name('assignments.submit');
    Route::get('/pengajuan-kehadiran',[LeaveRequestController::class,'index'])->name('leave.index');
    Route::post('/pengajuan-kehadiran',[LeaveRequestController::class,'store'])->middleware('role:student,parent')->name('leave.store');
    Route::patch('/pengajuan-kehadiran/{leaveRequest}',[LeaveRequestController::class,'review'])->middleware('role:admin,teacher')->name('leave.review');
    Route::get('/jelajahi',[PortalController::class,'explore'])->name('explore');
    Route::get('/obrolan',[PortalController::class,'chat'])->name('chat');
    Route::get('/akun',[PortalController::class,'account'])->name('account');
    Route::post('/bahasa',function(\Illuminate\Http\Request $request){ $data=$request->validate(['locale'=>'required|in:id,en']); session(['locale'=>$data['locale']]); app()->setLocale($data['locale']); return back()->with('status',$data['locale']==='en'?'Language changed to English.':'Bahasa diubah ke Indonesia.'); })->name('language.update');
    Route::get('/keuangan',[ModuleController::class,'finance'])->name('finance');
    Route::get('/profil',[ProfileController::class,'edit'])->name('profile.edit'); Route::put('/profil',[ProfileController::class,'update'])->name('profile.update'); Route::put('/profil/password',[ProfileController::class,'password'])->name('profile.password');
    Route::middleware('role:admin')->group(function(){ Route::get('/pengguna',[UserController::class,'index'])->name('users.index'); Route::post('/pengguna',[UserController::class,'store'])->name('users.store'); Route::get('/pengguna/{user}/edit',[UserController::class,'edit'])->name('users.edit'); Route::put('/pengguna/{user}',[UserController::class,'update'])->name('users.update'); Route::put('/pengguna/{user}/relasi',[UserController::class,'relations'])->name('users.relations'); Route::patch('/pengguna/{user}/toggle',[UserController::class,'toggle'])->name('users.toggle'); Route::post('/akademik/kelas',[AcademicController::class,'storeClass'])->name('academics.classes.store'); Route::post('/akademik/mapel',[AcademicController::class,'storeSubject'])->name('academics.subjects.store'); Route::post('/akademik/jadwal',[AcademicController::class,'storeSession'])->name('academics.sessions.store'); Route::post('/akademik/kalender',[AcademicController::class,'storeCalendar'])->name('academics.calendar.store'); });
});
