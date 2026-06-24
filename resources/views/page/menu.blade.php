@extends('layout.app')


@section('content')

<div class="ttl">
    Menu Fruit Bar
</div>

<div class="cat-wrap">

    <button class="cat active">
        Semua
    </button>

    <button class="cat">
        Juice
    </button>

    <button class="cat">
        Milk
    </button>

    <button class="cat">
        Snack
    </button>

</div>

<div class="prd-wrp">

{{-- Produk ditampilkan dari komponen produk yang sudah ada --}}
    @include('component.product',[
        'img' => asset('assets/product/alpukat.png'),
        'nm' => 'Jus Alpukat',
        'pr' => '10.000'
    ])

    @include('component.product',[
        'img' => asset('assets/product/anggur.png'),
        'nm' => 'Jus Anggur',
        'pr' => '10.000'
    ])

    @include('component.product',[
        'img' => asset('assets/product/apel.png'),
        'nm' => 'Jus Apel',
        'pr' => '10.000'
    ])

    {{-- lanjutkan semua produk lainnya di sini --}}

</div>

@endsection