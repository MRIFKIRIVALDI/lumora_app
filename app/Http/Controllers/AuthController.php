<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller
{
    public function create() { return view('auth.login'); }
    public function store(Request $request) { $credentials=$request->validate(['email'=>['required','email'],'password'=>['required','string']]); $key=strtolower($request->input('email')).'|'.$request->ip(); if(RateLimiter::tooManyAttempts($key,5)) throw ValidationException::withMessages(['email'=>'Terlalu banyak percobaan. Coba lagi dalam '.RateLimiter::availableIn($key).' detik.']); if(!Auth::attempt([...$credentials,'is_active'=>true],$request->boolean('remember'))){ RateLimiter::hit($key,60); throw ValidationException::withMessages(['email'=>'Email, kata sandi, atau status akun tidak valid.']); } RateLimiter::clear($key); $request->session()->regenerate(); return redirect()->intended(route('dashboard')); }
    public function destroy(Request $request) { Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken(); return redirect()->route('login')->with('status','Anda berhasil keluar.'); }
}
