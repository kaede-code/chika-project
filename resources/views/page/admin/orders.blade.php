@extends('layout.app')

@section('content')

<div class="ttl">Aktivitas</div>

@if (session('success'))
    <div class="alert-box" style="margin-bottom:12px;">{{ session('success') }}</div>
@endif

<div class="content-box" style="padding:0; overflow-x:hidden;">
    @if($orders->isEmpty())
        <div style="padding:16px;">Belum ada order.</div>
    @else
        <div style="display:flex; flex-direction:column; gap:12px; padding:14px;">
            @foreach($orders as $order)
                @php
                    $badgeClass = match($order->status) {
                        'Menunggu Verifikasi' => 'badge badge-wait',
                        'Diproses' => 'badge badge-proc',
                        'Selesai' => 'badge badge-done',
                        'Ditolak' => 'badge badge-reject',
                        default => 'badge',
                    };
                    $totalItems = $order->items->sum('qty');
                @endphp

                <div class="order-card">
                    <div class="order-summary">
                        <div class="order-summary-left">
                            <div class="order-id">#{{ $order->reference ?? 'INV-' . $order->id }}</div>
                            <div class="order-meta">{{ $totalItems }} Barang • Rp {{ number_format((int)($order->total_amount ?? 0), 0, ',', '.') }}</div>
                        </div>
                        <div class="order-summary-right">
                            <div class="order-date">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                            <span class="{{ $badgeClass }}" style="margin-top:4px;">{{ $order->status }}</span>
                        </div>
                    </div>

                    <div class="order-detail">
                        <div style="padding:14px 0 0;">
                            <div style="font-weight:900; font-size:14px;">{{ $order->user->name ?? '-' }} ({{ $order->user->no_hp ?? '-' }})</div>
                            <div style="margin-top:3px; font-weight:800; font-size:13px; color:rgba(30,41,59,.7);">{{ $order->formatted_alamat }}</div>
                        </div>

                        <hr style="border:none; border-top:1px solid rgba(0,0,0,.06); margin:12px 0;" />

                        <div style="display:flex; flex-direction:column; gap:4px;">
                            @foreach($order->items as $item)
                                @php($product = $item->product)
                                <div style="display:flex; justify-content:space-between; font-weight:800; font-size:13px;">
                                    <span>{{ $product->nama_produk ?? 'Produk' }} × {{ $item->qty }}</span>
                                    <span>Rp {{ number_format((int)($item->subtotal ?? 0), 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div style="display:flex; justify-content:space-between; font-weight:1000; font-size:14px; margin-top:6px; padding-top:6px; border-top:1px solid rgba(0,0,0,.06);">
                            <span>Total</span>
                            <span style="color:var(--secondary);">Rp {{ number_format((int)($order->total_amount ?? 0), 0, ',', '.') }}</span>
                        </div>

                        <hr style="border:none; border-top:1px solid rgba(0,0,0,.06); margin:12px 0;" />

                        <div style="font-weight:900; font-size:13px; margin-bottom:6px;">Bukti Pembayaran</div>
                        @if($order->paymentProofs->isEmpty())
                            <div style="color:rgba(30,41,59,.65); font-weight:900; font-size:13px;">Belum ada bukti.</div>
                        @else
                            @foreach($order->paymentProofs as $proof)
                                <a href="{{ asset('storage/' . $proof->file_path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $proof->file_path) }}" alt="bukti" style="width:100%; max-width:400px; height:auto; border-radius:12px; border:1px solid rgba(0,0,0,.08);" />
                                </a>
                            @endforeach
                        @endif

                        <hr style="border:none; border-top:1px solid rgba(0,0,0,.06); margin:12px 0;" />

                        <div style="display:flex; gap:10px;">
                            <form method="POST" action="{{ url('/admin/orders/' . $order->id . '/status') }}" style="flex:1;" onclick="event.stopPropagation();">
                                @csrf
                                <input type="hidden" name="status" value="Diproses" />
                                <button type="submit" class="btn btn-primary" style="width:100%;">Terima</button>
                            </form>

                            <form method="POST" action="{{ url('/admin/orders/' . $order->id . '/status') }}" style="flex:1;" onclick="event.stopPropagation();">
                                @csrf
                                <input type="hidden" name="status" value="Ditolak" />
                                <button type="submit" class="btn btn-danger" style="width:100%;">Tolak</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.order-card {
    background:#fff;
    border-radius:16px;
    box-shadow:0 8px 30px rgba(2,6,23,.06);
    border:1px solid rgba(2,6,23,.04);
    overflow:hidden;
    cursor:pointer;
    transition:box-shadow .2s;
}
.order-card:hover { box-shadow:0 12px 40px rgba(2,6,23,.10); }
.order-summary {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:14px 16px;
    gap:12px;
}
.order-summary-left { flex:1; min-width:0; }
.order-id { font-weight:1000; font-size:15px; }
.order-meta { margin-top:3px; font-weight:800; font-size:13px; color:rgba(30,41,59,.6); }
.order-summary-right {
    display:flex;
    flex-direction:column;
    align-items:flex-end;
    flex-shrink:0;
}
.order-date { font-weight:800; font-size:11px; color:rgba(30,41,59,.45); white-space:nowrap; }
.order-detail {
    display:none;
    padding:0 16px 16px;
    border-top:0;
}
.order-card.open .order-detail { display:block; }
.order-card.open .order-summary { padding-bottom:0; }
@media (max-width:640px) {
    .order-summary { padding:12px 14px; }
    .order-detail { padding:0 14px 14px; }
    .order-id { font-size:14px; }
}
</style>

<script>
document.querySelectorAll('.order-card').forEach(function(card) {
    card.addEventListener('click', function(e) {
        if (e.target.closest('a') || e.target.closest('form')) return;
        this.classList.toggle('open');
    });
});
</script>

@endsection
