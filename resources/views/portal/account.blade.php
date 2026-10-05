@extends('layouts.app')
@section('title','Akun — Lumora')
@section('heading','Akun')
@section('content')
@php $user=auth()->user(); @endphp
<section class="account-banner"><a href="{{ route('profile.edit') }}" class="avatar xlarge account-avatar">@if($user->photo)<img src="{{ Storage::url($user->photo) }}" alt="Foto profil">@else<span>{{ strtoupper(substr($user->name,0,1)) }}</span>@endif</a><div><span class="account-role">{{ ucfirst($user->role) }}</span><h2>{{ $user->name }}</h2><p>{{ $user->email }}</p></div></section>
<section class="account-menu card">
    <a href="{{ route('profile.edit') }}"><i class="tone-indigo"><x-icon name="user" size="23"/></i><span><b>Kelola Profil</b><small>Identitas, foto, kontak, dan keamanan akun</small></span><em><x-icon name="chevron-right"/></em></a>
    <a href="{{ route('academics') }}"><i class="tone-blue"><x-icon name="classes" size="23"/></i><span><b>Ruang Kelas</b><small>Jadwal, materi, tugas, dan kuis</small></span><em><x-icon name="chevron-right"/></em></a>
    @unless($user->role==='admin')<a href="{{ route('assignments') }}"><i class="tone-violet"><x-icon name="assignment" size="23"/></i><span><b>Tugas & Pengumpulan</b><small>Lihat tenggat dan status pengumpulan tugas</small></span><em><x-icon name="chevron-right"/></em></a>@endunless
    <a href="{{ route('leave.index') }}"><i class="tone-orange"><x-icon name="clipboard" size="23"/></i><span><b>Izin & Sakit</b><small>Ajukan atau periksa ketidakhadiran</small></span><em><x-icon name="chevron-right"/></em></a>
    @if($user->isRole('student','teacher'))<a href="{{ route('attendance.scanner') }}"><i class="tone-green"><x-icon name="qr" size="23"/></i><span><b>Pindai QR Presensi</b><small>Buka kamera untuk membaca QR Lumora</small></span><em><x-icon name="chevron-right"/></em></a>@endif
    @if($user->isRole('admin','teacher'))<a href="{{ route('attendance') }}"><i class="tone-green"><x-icon name="check" size="23"/></i><span><b>Kelola Presensi</b><small>Stasiun QR dan rekap kehadiran</small></span><em><x-icon name="chevron-right"/></em></a>@endif
    @unless($user->role==='teacher')<a href="{{ route('finance') }}"><i class="tone-cyan"><x-icon name="wallet" size="23"/></i><span><b>Keuangan</b><small>Tagihan dan status pembayaran</small></span><em><x-icon name="chevron-right"/></em></a>@endunless
    <button type="button" id="accountThemeToggle"><i class="tone-purple"><x-icon name="palette" size="23"/></i><span><b>Tampilan</b><small>Gunakan mode terang atau gelap</small></span><em><x-icon name="chevron-right"/></em></button>
    <form method="post" action="{{ route('language.update') }}" class="language-setting">@csrf<input type="hidden" name="locale" value="{{ session('locale','id')==='id'?'en':'id' }}"><button><i class="tone-blue"><x-icon name="language" size="23"/></i><span><b>Bahasa / Language</b><small>{{ session('locale','id')==='id'?'Indonesia · ganti ke English':'English · switch to Indonesia' }}</small></span><em><x-icon name="chevron-right"/></em></button></form>
    <a href="{{ route('explore') }}"><i class="tone-amber"><x-icon name="help" size="23"/></i><span><b>Pusat Informasi</b><small>Berita, panduan, dan informasi sekolah</small></span><em><x-icon name="chevron-right"/></em></a>
    <form action="{{ route('logout') }}" method="post">@csrf<button class="logout-row"><i class="tone-red"><x-icon name="logout" size="23"/></i><span><b>Keluar</b><small>Akhiri sesi pada perangkat ini</small></span><em><x-icon name="chevron-right"/></em></button></form>
</section>
@endsection
