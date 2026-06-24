<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Chika Fruit Bar</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

</head>

<body>

    <div class="app">

        @include('component.header')

        <main class="main page">
            @yield('content')
        </main>

        @auth
            @php($role = optional(auth()->user())->role)
            {{-- Admin: tanpa cart, Customer: cart tampil di menu --}}
            @if($role === 'customer' && request()->is('customer/menu'))
                @include('component.cart')
            @endif
        @endauth

        @include('component.navbar')




    </div>

    <script src="{{ asset('js/app.js') }}"></script>

</body>

</html>