@extends('layouts.app')
@section('title','Keuangan — Lumora')
@section('heading','Keuangan SPP')
@section('content')
<div class="stats mini">
    <article class="stat-card"><div><p>Total tagihan</p><strong>Rp {{ number_format($bills->sum('amount'),0,',','.') }}</strong><small>{{ $bills->count() }} transaksi</small></div></article>
    <article class="stat-card"><div><p>Sudah lunas</p><strong>{{ $bills->where('status','paid')->count() }}</strong><small>Terekonsiliasi</small></div></article>
    <article class="stat-card"><div><p>Menunggu</p><strong>{{ $bills->where('status','pending')->count() }}</strong><small>Perlu tindak lanjut</small></div></article>
</div>
@if($user->role==='admin')
<details class="card finance-issuer"><summary>＋ Terbitkan tagihan SPP</summary><form method="post" action="{{ route('finance.store') }}" class="compact-form">@csrf
    <label>Murid<select name="student_id"><option value="">Semua murid aktif</option>@foreach($students as $student)<option value="{{ $student->id }}">{{ $student->name }}</option>@endforeach</select></label>
    <div class="form-row"><label>Bulan<select name="month">@foreach(range(1,12) as $month)<option value="{{ $month }}" @selected($month==(int)date('n'))>{{ \Carbon\Carbon::create()->month($month)->locale('id')->translatedFormat('F') }}</option>@endforeach</select></label><label>Tahun<input type="number" name="year" value="{{ date('Y') }}" min="2020" max="2100" required></label></div>
    <label>Nominal<input type="number" name="amount" value="450000" min="1000" step="1000" required></label><button class="primary-button" type="submit">Terbitkan tagihan</button>
</form></details>
@endif
<section class="card table-card finance-table"><div class="section-head"><h3>{{ $user->role==='admin'?'Daftar seluruh tagihan':'Tagihan sekolah' }}</h3><span>{{ $bills->count() }} data</span></div><div class="table-wrap"><table><thead><tr><th>Murid</th><th>Periode</th><th>Jumlah</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
@forelse($bills as $bill)<tr><td><b>{{ $bill->name }}</b></td><td>{{ str_pad($bill->month,2,'0',STR_PAD_LEFT) }}/{{ $bill->year }}</td><td>Rp {{ number_format($bill->amount,0,',','.') }}</td><td><span class="badge {{ $bill->status==='paid'?'hadir':'terlambat' }}">{{ $bill->status==='paid'?'Lunas':'Menunggu' }}</span>@if($bill->paid_at)<small>{{ \Carbon\Carbon::parse($bill->paid_at)->locale('id')->translatedFormat('d M Y, H:i') }}</small>@endif</td><td>@if($user->role==='admin')<form method="post" action="{{ route('finance.status',$bill->id) }}">@csrf @method('patch')<input type="hidden" name="status" value="{{ $bill->status==='paid'?'pending':'paid' }}"><button class="outline-button small" type="submit">{{ $bill->status==='paid'?'Batalkan lunas':'Tandai lunas' }}</button></form>@elseif($bill->status==='pending')<a class="primary-button small" href="{{ route('finance.checkout',$bill->id) }}">Bayar sekarang</a>@else<span class="badge paid">Selesai</span>@endif</td></tr>@empty<tr><td colspan="5" class="empty">Belum ada tagihan.</td></tr>@endforelse
</tbody></table></div></section>
@if($payments->isNotEmpty())<section class="card payment-history"><div class="section-head"><div><p class="eyebrow">RIWAYAT TRANSAKSI</p><h3>Pembayaran terbaru</h3></div><span>Mode sandbox</span></div><div class="payment-history-list">@foreach($payments as $payment)<article><span><b>{{ $payment->student_name }}</b><small>{{ $payment->reference }} · {{ strtoupper(str_replace('_',' ',$payment->method)) }}</small></span><span><b>Rp {{ number_format($payment->amount,0,',','.') }}</b><small>{{ \Carbon\Carbon::parse($payment->paid_at)->locale('id')->translatedFormat('d M Y, H:i') }}</small></span></article>@endforeach</div></section>@endif
@endsection
