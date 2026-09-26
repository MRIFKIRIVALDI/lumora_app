@extends('layouts.app')
@section('title','Pindai QR — Lumora')
@section('heading','Pindai QR Presensi')
@section('content')
<section class="scanner-card card"><div class="scanner-copy"><p class="eyebrow">KAMERA PRESENSI</p><h2>Arahkan kamera ke QR</h2><p>Pastikan QR berada di dalam bingkai. Lumora akan membuka halaman konfirmasi secara otomatis.</p></div><div class="camera-frame"><video id="qrCamera" playsinline muted></video><div class="camera-guide"><i></i><i></i><i></i><i></i></div><div id="cameraMessage">Tekan tombol untuk mengaktifkan kamera.</div></div><div class="scanner-actions"><button class="primary-button" id="startQrCamera">Buka kamera</button><button class="outline-button" id="stopQrCamera" disabled>Matikan kamera</button></div><p class="scanner-help">Kamera memerlukan izin browser dan HTTPS, kecuali saat menggunakan localhost/127.0.0.1.</p></section>
@endsection
