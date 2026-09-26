<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <meta name="theme-color" content="#35358f">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>if(localStorage.getItem('lumora-theme')==='dark'||(!localStorage.getItem('lumora-theme')&&matchMedia('(prefers-color-scheme:dark)').matches))document.documentElement.classList.add('dark')</script>
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="icon" href="/logo_lumora.png">
    <title>@yield('title','Lumora')</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
@php
    $role=auth()->user()->role;
    $lang=session('locale','id');
    $labels=$lang==='en'?['dashboard'=>'Dashboard','explore'=>'Explore','class'=>'Classes','chat'=>'Chat','account'=>'Account','attendance'=>'Manage Attendance','tasks'=>'Assignments','users'=>'User Management','back'=>'Back']:['dashboard'=>'Dashboard','explore'=>'Jelajahi','class'=>'Kelas','chat'=>'Obrolan','account'=>'Akun','attendance'=>'Kelola Presensi','tasks'=>'Tugas','users'=>'Manajemen Pengguna','back'=>'Kembali'];
    $classLabel=$role==='parent'&&$lang==='id'?'Akademik Anak':$labels['class'];
@endphp
<div class="min-h-screen lg:flex">
    <aside class="sidebar hidden lg:flex">
        <a href="{{ route('dashboard') }}" class="brand"><img src="/logo_lumora.png" alt="Lumora"><span><b>Lumora</b><small>School Management</small></span></a>
        <nav class="nav portal-nav">
            @if($role==='admin')
            <span class="nav-label">UTAMA</span>
            <a class="{{ request()->routeIs('dashboard')?'active':'' }}" href="{{ route('dashboard') }}"><span class="nav-icon">⌂</span><span>Dashboard</span></a>
            <span class="nav-label">AKADEMIK & SEKOLAH</span>
            <a href="{{ route('academics') }}"><span class="nav-icon">▦</span><span>Kelas & Rombel</span></a>
            <a href="{{ route('academics') }}"><span class="nav-icon">▤</span><span>Mata Pelajaran</span></a>
            <a href="{{ route('academics') }}"><span class="nav-icon">◷</span><span>Jadwal Pelajaran</span></a>
            <a href="{{ route('academics') }}"><span class="nav-icon">□</span><span>Kalender Akademik</span></a>
            <span class="nav-label">PENGGUNA & RELASI</span>
            <a class="{{ request()->routeIs('users.*')&&!request('role')?'active':'' }}" href="{{ route('users.index') }}"><span class="nav-icon">♙</span><span>Semua Pengguna</span></a>
            <a href="{{ route('users.index',['role'=>'teacher']) }}"><span class="nav-icon">◇</span><span>Guru</span></a>
            <a href="{{ route('users.index',['role'=>'student']) }}"><span class="nav-icon">○</span><span>Murid</span></a>
            <a href="{{ route('users.index',['role'=>'parent']) }}"><span class="nav-icon">♧</span><span>Wali Murid</span></a>
            <span class="nav-label">PRESENSI</span>
            <a class="{{ request()->routeIs('attendance','stations.*')?'active':'' }}" href="{{ route('attendance') }}"><span class="nav-icon">✓</span><span>Presensi & Stasiun QR</span></a>
            <a class="{{ request()->routeIs('leave.*')?'active':'' }}" href="{{ route('leave.index') }}"><span class="nav-icon">▧</span><span>Izin & Sakit</span></a>
            <span class="nav-label">KEUANGAN & INFORMASI</span>
            <a class="{{ request()->routeIs('finance')?'active':'' }}" href="{{ route('finance') }}"><span class="nav-icon">◇</span><span>Tagihan SPP</span></a>
            <a class="{{ request()->routeIs('explore')?'active':'' }}" href="{{ route('explore') }}"><span class="nav-icon">◈</span><span>Berita & Pengumuman</span></a>
            <span class="nav-label">SISTEM</span>
            <a class="{{ request()->routeIs('account','profile.*')?'active':'' }}" href="{{ route('account') }}"><span class="nav-icon">⚙</span><span>Pengaturan Akun</span></a>
            @else
            <span class="nav-label">MENU UTAMA</span>
            <a class="{{ request()->routeIs('dashboard')?'active':'' }}" href="{{ route('dashboard') }}"><span class="nav-icon">⌂</span><span>{{ $labels['dashboard'] }}</span></a>
            <a class="{{ request()->routeIs('explore')?'active':'' }}" href="{{ route('explore') }}"><span class="nav-icon">◇</span><span>{{ $labels['explore'] }}</span></a>
            <a class="{{ request()->routeIs('academics','learning.*')?'active':'' }}" href="{{ route('academics') }}"><span class="nav-icon">▦</span><span>{{ $classLabel }}</span></a>
            <a class="{{ request()->routeIs('chat')?'active':'' }}" href="{{ route('chat') }}"><span class="nav-icon">▤</span><span>{{ $labels['chat'] }}</span></a>
            <a class="{{ request()->routeIs('account','profile.*')?'active':'' }}" href="{{ route('account') }}"><span class="nav-icon">○</span><span>{{ $labels['account'] }}</span></a>
            @if(auth()->user()->isRole('admin','teacher'))
                <span class="nav-label">OPERASIONAL</span>
                <a class="{{ request()->routeIs('attendance','stations.*')?'active':'' }}" href="{{ route('attendance') }}"><span class="nav-icon">✓</span><span>{{ $labels['attendance'] }}</span></a>
            @endif
            @unless($role==='admin')<a class="{{ request()->routeIs('assignments')?'active':'' }}" href="{{ route('assignments') }}"><span class="nav-icon">□</span><span>{{ $labels['tasks'] }}</span></a>@endunless
            <a class="{{ request()->routeIs('leave.*')?'active':'' }}" href="{{ route('leave.index') }}"><span class="nav-icon">▧</span><span>Izin & Sakit</span></a>
            @if($role==='admin')
                <a class="{{ request()->routeIs('users.*')?'active':'' }}" href="{{ route('users.index') }}"><span class="nav-icon">♙</span><span>{{ $labels['users'] }}</span></a>
            @endif
            @endif
        </nav>
        <div class="profile">
            <a href="{{ route('account') }}" class="avatar">@if(auth()->user()->photo)<img src="{{ Storage::url(auth()->user()->photo) }}" alt="Foto profil">@else{{ strtoupper(substr(auth()->user()->name,0,1)) }}@endif</a>
            <a href="{{ route('account') }}" class="profile-name"><b>{{ auth()->user()->name }}</b><small>{{ ucfirst($role) }} · Lihat akun</small></a>
            <form action="{{ route('logout') }}" method="post">@csrf<button title="Keluar">↪</button></form>
        </div>
    </aside>
    <main class="main">
        <header class="topbar">
            <div>@unless(request()->routeIs('dashboard'))<button type="button" class="back-button" onclick="history.length>1?history.back():location.href='{{ route('dashboard') }}'" aria-label="{{ $labels['back'] }}">←</button>@endunless<div><p class="eyebrow">{{ now()->locale($lang)->translatedFormat('l, d F Y') }}</p><h1>@yield('heading',$lang==='en'?'Welcome':'Selamat datang')</h1></div></div>
            <div class="top-actions">
                <button class="icon-button theme-toggle" id="themeToggle" title="Ganti tema"><span class="theme-icon">☾</span></button>
                <div class="notification-wrap"><button class="icon-button" id="notificationButton" title="Notifikasi" aria-expanded="false">♢<span></span></button><div class="notification-dropdown" id="notificationDropdown"><div><b>Notifikasi</b><small>{{ $navNotifications->count() }} informasi terbaru</small></div>@forelse($navNotifications as $notice)<article><i></i><div><b>{{ $notice->title }}</b><p>{{ Str::limit($notice->content,70) }}</p><small>{{ \Carbon\Carbon::parse($notice->published_at)->diffForHumans() }}</small></div></article>@empty<p class="empty">Belum ada notifikasi.</p>@endforelse</div></div>
                <a href="{{ route('account') }}" class="avatar">@if(auth()->user()->photo)<img src="{{ Storage::url(auth()->user()->photo) }}" alt="Foto profil">@else{{ strtoupper(substr(auth()->user()->name,0,1)) }}@endif</a>
            </div>
        </header>
        @if(session('status'))<div class="alert success">✓ {{ session('status') }}</div>@endif
        @if(session('error'))<div class="alert error">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="alert error">{{ $errors->first() }}</div>@endif
        <div class="content">@yield('content')</div>
    </main>
</div>
<nav class="bottom-nav portal-bottom lg:hidden">
    <a class="{{ request()->routeIs('dashboard')?'active':'' }}" href="{{ route('dashboard') }}"><b>⌂</b><span>{{ $labels['dashboard'] }}</span></a>
    <a class="{{ request()->routeIs('explore')?'active':'' }}" href="{{ route('explore') }}"><b>◇</b><span>{{ $labels['explore'] }}</span></a>
    <a class="{{ request()->routeIs('academics','learning.*')?'active':'' }}" href="{{ route('academics') }}"><b>▦</b><span>{{ $labels['class'] }}</span></a>
    <a class="{{ request()->routeIs('chat')?'active':'' }}" href="{{ route('chat') }}"><b>▤</b><span>{{ $labels['chat'] }}</span></a>
    <a class="{{ request()->routeIs('account','profile.*')?'active':'' }}" href="{{ route('account') }}"><b>●</b><span>{{ $labels['account'] }}</span></a>
</nav>
</body>
</html>
