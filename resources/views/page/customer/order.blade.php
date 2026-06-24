@extends('layout.app')

@section('content')

@php
    $cartItems = \App\Models\CartItem::with('product')
        ->where('user_id', auth()->id())
        ->get();
    $cartTotal = $cartItems->sum(fn($item) => $item->qty * ($item->product->harga ?? 0));
    $rawAlamat = trim((string) (auth()->user()->alamat ?? ''));
    $alamatUser = auth()->user()->formatted_alamat;
    $noHpUser = trim((string) (auth()->user()->no_hp ?? ''));
@endphp

<div class="ttl">Konfirmasi Pesanan</div>

@if (session('success'))
    <div class="alert-box" style="margin-bottom:12px;">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert-box" style="margin-bottom:12px; background:#fef2f2; color:#b91c1c;">
        {{ $errors->first() }}
    </div>
@endif

@if($cartItems->isEmpty())
    <div class="content-box" style="margin-top:14px;">
        <div style="text-align:center; padding:20px; font-weight:800; color:#6f6f6f;">
            Keranjang masih kosong. <a href="{{ route('customer.menu') }}" style="color:var(--secondary);">Pilih produk</a> terlebih dahulu.
        </div>
    </div>
@else
<div class="content-box" style="margin-top:14px; background:#ffffff; padding:14px; border-radius:16px; box-shadow:0 5px 20px rgba(0,0,0,0.05); max-width:100%;">
    <div style="display:flex; flex-direction:column; gap:14px;">

        <div style="background:#f8fff4; padding:12px; border-radius:12px; border:1px solid rgba(67,160,71,.18);">
            <div style="font-weight:900; font-size:14px;">{{ auth()->user()->name }} ({{ $noHpUser ?: '-' }})</div>
            <div style="margin-top:4px; font-weight:800; font-size:13px; color:rgba(30,41,59,.7);">{{ $alamatUser }}</div>
        </div>

        <div>
            <h3 class="section-title">Detail Pesanan</h3>
            <div class="kv">
                @foreach($cartItems as $item)
                    @php($product = $item->product)
                    @if(!$product) @continue @endif
                    @php($subtotal = (int) $item->qty * (int) $product->harga)
                    <div class="kv-row">
                        <div class="kv-label">{{ $product->nama_produk }} × {{ $item->qty }}</div>
                        <div class="kv-value">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                    </div>
                @endforeach
                <div class="kv-row" style="border-bottom:none; font-size:15px;">
                    <div class="kv-label" style="color:var(--secondary);">Total</div>
                    <div class="kv-value" style="color:var(--secondary);">Rp {{ number_format($cartTotal, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div>
            <h3 class="section-title">Metode Pembayaran</h3>
            <div class="pay-grid">
                <div class="pay-card">
                    <b>QRIS (Aktif)</b>
                    <div class="pay-sub">Silakan scan QRIS berikut untuk pembayaran.</div>
                    <img class="qris-img" src="{{ asset('assets/logo/qris.jpeg') }}" alt="QRIS" />
                </div>
                <div class="pay-card">
                    <b>Transfer Bank</b>
                    <div class="bank-line">Bank BCA</div>
                    <div class="bank-line">No Rekening: <b>1234567890</b></div>
                    <div class="bank-line">Atas Nama: <b>CHIKA FRUIT BAR</b></div>
                </div>
            </div>
        </div>

        <div class="pay-card" style="background:#f8fff4; border:1px solid rgba(67,160,71,.18);">
            <b>Upload Bukti Pembayaran</b>
            <div style="margin-top:8px; font-size:13px; color:#6f6f6f; font-weight:800;">
                Tipe file: jpg, jpeg, png, pdf. Maks 2MB.
            </div>

            <form method="POST" action="{{ route('customer.order.confirm') }}" enctype="multipart/form-data" class="mt-12">
                @csrf

                <div class="field">
                    <label for="bukti" class="field-label">Pilih file bukti pembayaran</label>
                    <input id="bukti" name="bukti" type="file" accept="image/jpeg,image/png,application/pdf" required />
                    @error('bukti')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                @if($rawAlamat === '')
                    <div class="field-error" style="color:#dc2626; font-size:12px; margin-top:4px; display:flex; align-items:flex-start; gap:8px;">
                        <span style="line-height:1;">⚠</span>
                        <span>Alamat belum diisi. Silakan lengkapi data pada menu Pusat Akun terlebih dahulu.</span>
                    </div>
                @endif

                @if($noHpUser === '')
                    <div class="field-error" style="color:#dc2626; font-size:12px; margin-top:4px; display:flex; align-items:flex-start; gap:8px;">
                        <span style="line-height:1;">⚠</span>
                        <span>Nomor HP belum diisi. Silakan lengkapi data pada menu Pusat Akun terlebih dahulu.</span>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary btn-full mt-12" {{ ($rawAlamat !== '' && $noHpUser !== '') ? '' : 'disabled' }}>
                    Konfirmasi & Kirim
                </button>
            </form>
        </div>

    </div>
</div>
@endif

@endsection
