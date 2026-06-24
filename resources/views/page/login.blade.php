@extends('layout.auth')

@section('content')

<div class="auth-screen">
    <div class="auth-card">
        <div class="auth-logo">
            <img src="{{ asset('assets/logo/logo.jpeg') }}" alt="Logo" />
        </div>

        <h1 class="auth-title">Selamat Datang</h1>
        <p class="auth-subtitle">Masuk ke akun Anda</p>

        @if (session('success'))
            <div class="auth-alert auth-alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="auth-alert auth-alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="/login" class="auth-form">
            @csrf

            <div class="field">
                <label for="no_hp" class="field-label">Nomor HP</label>
                <input
                    type="text"
                    id="no_hp"
                    name="no_hp"
                    value="{{ old('no_hp') }}"
                    placeholder="08xxxxxxxxxx"
                    required
                    maxlength="30"
                />
                @error('no_hp')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>


            <div class="field">
                <label for="password" class="field-label">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    required
                />
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-full mt-12">Login</button>

            <div class="auth-footer">
                <span class="muted">Belum punya akun?</span>
                <a href="/register" class="auth-link">Daftar</a>
            </div>
        </form>
    </div>
</div>

@endsection

