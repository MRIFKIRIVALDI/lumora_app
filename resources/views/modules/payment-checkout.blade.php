@extends('layouts.app')
@section('title','Pembayaran SPP — Lumora')
@section('heading','Pembayaran SPP')
@section('content')
<section class="payment-checkout card">
    <div class="payment-heading"><span class="payment-icon"><x-icon name="wallet" size="28"/></span><div><p class="eyebrow">PAYMENT SANDBOX</p><h2>Simulasi pembayaran</h2><p>Tidak ada uang sungguhan yang diproses pada mode percobaan ini.</p></div></div>
    <div class="payment-invoice"><div><span>Tagihan untuk</span><b>{{ $bill->student_name }}</b></div><div><span>Periode</span><b>{{ \Carbon\Carbon::create($bill->year,$bill->month)->locale('id')->translatedFormat('F Y') }}</b></div><div><span>Total pembayaran</span><strong>Rp {{ number_format($bill->amount,0,',','.') }}</strong></div></div>
    @if($bill->status==='paid')
        <div class="payment-paid"><x-icon name="check"/> Tagihan ini sudah lunas.</div>
        <a class="outline-button" href="{{ route('finance') }}">Kembali ke Keuangan</a>
    @else
        <form method="post" action="{{ route('finance.pay',$bill->id) }}" class="payment-methods">@csrf
            <h3>Pilih metode simulasi</h3>
            <label><input type="radio" name="method" value="qris" checked><span><b>QRIS Sandbox</b><small>Simulasi pembayaran melalui QR</small></span></label>
            <label><input type="radio" name="method" value="virtual_account"><span><b>Virtual Account</b><small>Simulasi transfer bank otomatis</small></span></label>
            <label><input type="radio" name="method" value="ewallet"><span><b>Dompet Digital</b><small>Simulasi pembayaran e-wallet</small></span></label>
            <button class="primary-button" type="submit">Bayar Rp {{ number_format($bill->amount,0,',','.') }} (Simulasi)</button>
        </form>
    @endif
</section>
@endsection
