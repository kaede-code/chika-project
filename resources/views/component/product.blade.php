@php
    $imgSrc = $img ?? null;
    $nameText = $nm ?? '';
    $priceText = (int) ($pr ?? 0);
    $productId = (int) ($id ?? 0);
    $qty = (int) ($qtyInCart ?? 0);
    $kategori = $kategori ?? '';
@endphp

<div class="product-card" data-product-id="{{ $productId }}" data-kategori="{{ $kategori ?? '' }}">
    <div class="product-image">
        @if($imgSrc)
            <img src="{{ $imgSrc }}" alt="{{ $nameText }}">
        @else
            <div class="product-image-placeholder">No Image</div>
        @endif
    </div>

    <div class="product-info">
        <div class="product-name">{{ $nameText }}</div>
        <div class="product-price">Rp {{ number_format($priceText, 0, ',', '.') }}</div>
    </div>

    <div class="product-action">
        <form method="POST" action="{{ route('customer.cart.add', ['product' => $productId]) }}" class="product-action-form">
            @csrf
            <input type="hidden" name="qty" value="{{ $qty > 0 ? $qty : 1 }}" class="product-qty-input">

            <button type="submit" class="pc-btn pc-btn-pesan" style="display:{{ $qty > 0 ? 'none' : 'flex' }}">Pesan</button>

            <div class="pc-qty-box" style="display:{{ $qty > 0 ? 'flex' : 'none' }}">
                <button type="button" class="pc-qty-btn pc-qty-minus">-</button>
                <span class="pc-qty-value">{{ $qty > 0 ? $qty : 1 }}</span>
                <button type="button" class="pc-qty-btn pc-qty-plus">+</button>
            </div>
        </form>
    </div>
</div>
