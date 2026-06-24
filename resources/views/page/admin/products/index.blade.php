@extends('layout.app')

@section('content')

<div class="ttl">Produk</div>

@if (session('success'))
    <div class="alert-box" style="margin-bottom:12px;">{{ session('success') }}</div>
@endif

<div class="prd-wrp" id="productListAdmin">
    @foreach($products as $product)
        @include('component.product-admin',[
            'img' => !empty($product->gambar) ? asset('storage/'.$product->gambar) : null,
            'nm' => $product->nama_produk,
            'pr' => $product->harga,
            'editUrl' => route('admin.products.edit', $product->id),
        ])
    @endforeach
</div>

<a href="{{ route('admin.products.create') }}" class="btn-add-product">+ Tambah Produk</a>

@if ($products->hasPages())
    <div class="pagination">
        @if ($products->onFirstPage())
            <span class="page-btn page-disabled">&lt;</span>
        @else
            <a href="{{ $products->previousPageUrl() }}" class="page-btn">&lt;</a>
        @endif

        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="page-btn {{ $page === $products->currentPage() ? 'page-active' : '' }}">{{ $page }}</a>
        @endforeach

        @if ($products->hasMorePages())
            <a href="{{ $products->nextPageUrl() }}" class="page-btn">&gt;</a>
        @else
            <span class="page-btn page-disabled">&gt;</span>
        @endif
    </div>
@endif

@endsection
