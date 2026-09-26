@extends('layouts.app')
@section('title','Akun — Lumora')
@section('heading','Akun')
@section('content')
<section class="account-banner"><a href="{{ route('profile.edit') }}" class="avatar xlarge">@if(auth()->user()->photo)<img src="{{ Storage::url(auth()->user()->photo) }}" alt="Foto profil">@else{{ strtoupper(substr(auth()->user()->name,0,1)) }}@endif</a><div><h2>{{ auth()->user()->name }}</h2><p>{{ ucfirst(auth()->user()->role) }} · {{ auth()->user()->email }}</p></div></section>
<section class="account-menu card">
    <a href="{{ route('profile.edit') }}"><i>○</i><span><b>Kelola Profil</b><small>Identitas, foto, kontak, dan keamanan akun</small></span><em>›</em></a>
    <a href="{{ route('academics') }}"><i>▦</i><span><b>Ruang Kelas</b><small>Jadwal, materi, tugas, dan kuis</small></span><em>›</em></a>
    @unless(auth()->user()->role==='admin')<a href="{{ route('assignments') }}"><i>□</i><span><b>Tugas & Pengumpulan</b><small>Lihat tenggat dan status pengumpulan tugas</small></span><em>›</em></a>@endunless
    <a href="{{ route('leave.index') }}"><i>▧</i><span><b>Izin & Sakit</b><small>Ajukan atau periksa ketidakhadiran</small></span><em>›</em></a>
    @if(auth()->user()->isRole('student','teacher'))<a href="{{ route('attendance.scanner') }}"><i>◎</i><span><b>Pindai QR Presensi</b><small>Buka kamera untuk membaca QR Lumora</small></span><em>›</em></a>@endif
    @if(auth()->user()->isRole('admin','teacher'))<a href="{{ route('attendance') }}"><i>✓</i><span><b>Kelola Presensi</b><small>Stasiun QR dan rekap kehadiran</small></span><em>›</em></a>@endif
    @unless(auth()->user()->role==='teacher')<a href="{{ route('finance') }}"><i>◇</i><span><b>Keuangan</b><small>Tagihan dan status pembayaran</small></span><em>›</em></a>@endunless
    <button type="button" id="accountThemeToggle"><i>☾</i><span><b>Tampilan</b><small>Gunakan mode terang atau gelap</small></span><em>›</em></button>
    <form method="post" action="{{ route('language.update') }}" class="language-setting">@csrf<input type="hidden" name="locale" value="{{ session('locale','id')==='id'?'en':'id' }}"><button><i>文</i><span><b>Bahasa / Language</b><small>{{ session('locale','id')==='id'?'Indonesia · ganti ke English':'English · switch to Indonesia' }}</small></span><em>›</em></button></form>
    <a href="{{ route('explore') }}"><i>?</i><span><b>Pusat Informasi</b><small>Berita, panduan, dan informasi sekolah</small></span><em>›</em></a>
    <form action="{{ route('logout') }}" method="post">@csrf<button><i>↪</i><span><b>Keluar</b><small>Akhiri sesi pada perangkat ini</small></span><em>›</em></button></form>
</section>
@endsection
