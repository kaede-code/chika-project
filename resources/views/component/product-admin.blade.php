@php
    $imgSrc = $img ?? null;
    $nameText = $nm ?? '';
    $priceText = (int) ($pr ?? 0);
    $editUrlFinal = $editUrl ?? '#';
@endphp

<div class="product-card">
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
        <a class="pc-btn pc-btn-edit" href="{{ $editUrlFinal }}">Edit</a>
    </div>
</div>
