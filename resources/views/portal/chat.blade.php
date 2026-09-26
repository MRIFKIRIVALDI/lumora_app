@extends('layouts.app')
@section('title','Obrolan — Lumora')
@section('heading','Obrolan')
@section('content')
<section class="card conversation-shell"><div class="conversation-empty"><span>▤</span><h2>Ruang komunikasi sekolah</h2><p>Obrolan kelas dan pesan sekolah akan tampil di sini. Fondasi menu telah tersedia, sedangkan pengiriman pesan realtime masuk tahap pengembangan berikutnya.</p><a class="primary-button" href="{{ route('academics') }}">Buka ruang kelas</a></div></section>
@endsection
