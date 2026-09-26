@extends('layouts.app')
@section('title','Jelajahi — Lumora')
@section('heading','Jelajahi')
@section('content')
<section class="portal-hero"><div><p class="eyebrow">KABAR LUMORA</p><h2>Berita dan kegiatan sekolah</h2><p>Temukan pengumuman, prestasi, agenda, dan informasi terbaru dari sekolah.</p></div><span>◇</span></section>
<div class="news-grid">@forelse($news as $item)<article class="card news-card"><div class="news-cover"><img src="/logo_lumora.png" alt="Lumora"><span>{{ $item->target_role ? ucfirst($item->target_role) : 'Semua warga sekolah' }}</span></div><div><small>{{ \Carbon\Carbon::parse($item->published_at)->locale('id')->translatedFormat('d F Y') }}</small><h3>{{ $item->title }}</h3><p>{{ $item->content }}</p></div></article>@empty<div class="card empty">Belum ada berita sekolah.</div>@endforelse</div>
@endsection
