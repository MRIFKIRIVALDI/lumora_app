@extends('layouts.app')
@section('title',$material->title.' — Lumora')
@section('heading','Pratinjau Materi')
@section('content')
<a href="{{ route('learning.show',$material->class_session_id) }}" class="back-link">← Kembali ke Pertemuan</a>
<section class="card material-preview"><div class="section-head"><div><p class="eyebrow">{{ $material->meeting_title }}</p><h2>{{ $material->title }}</h2></div><span class="badge info">{{ ucfirst($material->type) }}</span></div>
@if($material->content)<div class="material-copy">{!! nl2br(e($material->content)) !!}</div>@endif
@if($material->url)<div class="external-preview"><p>Tautan materi eksternal</p><a href="{{ $material->url }}" target="_blank" rel="noopener" class="primary-button small">Buka tautan ↗</a></div>@endif
@if($material->file_path)<div class="file-preview"><div class="file-meta"><b>{{ $material->file_name }}</b><small>{{ $material->mime_type }}</small></div>
@if(str_starts_with($material->mime_type??'','image/'))<img src="{{ route('materials.file',$material->id) }}" alt="Pratinjau {{ $material->title }}">@elseif(($material->mime_type??'')==='application/pdf')<iframe src="{{ route('materials.file',$material->id) }}" title="Pratinjau {{ $material->title }}"></iframe>@else<div class="office-preview"><strong>▤</strong><p>Berkas siap diunduh. Format Office tidak dapat dirender langsung oleh semua browser.</p></div>@endif
<a href="{{ route('materials.download',$material->id) }}" class="primary-button">Unduh materi</a></div>@endif
</section>
@endsection
