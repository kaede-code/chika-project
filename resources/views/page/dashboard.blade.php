@extends('layout.app')

@section('content')

<div class="ttl">
    Dashboard
</div>

<div class="stats-wrap">

    @include('component.stats-card',[
        'title' => 'Total Order',
        'value' => '120'
    ])

    @include('component.stats-card',[
        'title' => 'Income',
        'value' => 'Rp 2.4jt'
    ])

    @include('component.stats-card',[
        'title' => 'Produk',
        'value' => '45'
    ])

</div>

@endsection