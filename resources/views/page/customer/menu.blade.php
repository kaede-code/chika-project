@extends('layout.app')

@section('content')

@if (session('success_small'))
    <div class="alert-box" style="margin-bottom:12px;">
        {{ session('success_small') }}
    </div>
@endif

{{-- Search --}}
<div class="greeting-box">🍹 Pesan jus & salad favorit kamu!</div>
<input type="text" id="searchInput" class="search-input" placeholder="Cari produk..." autocomplete="off">

{{-- Category filter --}}
<div class="cat-filter">
    <button class="cat-btn cat-btn-active" data-cat="all">Semua</button>
    <button class="cat-btn" data-cat="Jus">Jus</button>
    <button class="cat-btn" data-cat="Salad">Salad</button>
</div>

{{-- Products --}}
<div class="prd-wrp" id="productList">
    @foreach($products as $product)
        @php
            $qtyInCart = $cartQuantities[$product->id] ?? 0;
        @endphp
        @include('component.product',[
            'img' => !empty($product->gambar) ? asset('storage/'.$product->gambar) : asset('assets/product/no-image.png'),
            'nm' => $product->nama_produk,
            'pr' => $product->harga,
            'kategori' => $product->kategori,
            'id' => $product->id,
            'qtyInCart' => $qtyInCart,
        ])
    @endforeach
</div>

<script>
var searchInput = document.getElementById('searchInput');
var productList = document.getElementById('productList');
var catBtns = document.querySelectorAll('.cat-btn');
var activeCat = 'all';

catBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
        catBtns.forEach(function(b) { b.classList.remove('cat-btn-active'); });
        this.classList.add('cat-btn-active');
        activeCat = this.getAttribute('data-cat');
        filterProducts();
    });
});

searchInput.addEventListener('input', filterProducts);

function filterProducts() {
    var query = searchInput.value.toLowerCase();
    var cards = productList.querySelectorAll('.product-card');
    cards.forEach(function(card) {
        var name = card.querySelector('.product-name').textContent.toLowerCase();
        var cat = card.getAttribute('data-kategori') || '';
        var matchSearch = name.indexOf(query) !== -1;
        var matchCat = activeCat === 'all' || cat === activeCat;
        card.style.display = (matchSearch && matchCat) ? '' : 'none';
    });
}
</script>

@endsection

