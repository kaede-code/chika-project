@extends('layout.auth')

@section('content')

<div class="auth-screen">
    <div class="auth-card">
        <div class="auth-logo">
            <img src="{{ asset('assets/logo/logo.jpeg') }}" alt="Logo" />
        </div>

        <h1 class="auth-title">Daftar Akun</h1>
        <p class="auth-subtitle">Buat akun Anda untuk mulai berbelanja</p>

        @if ($errors->any())
            <div class="auth-alert auth-alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="/register" class="auth-form">
            @csrf

            <div class="field">
                <label for="name" class="field-label">Nama</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Nama lengkap"
                    required
                    maxlength="255"
                />
                @error('name')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

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
                    placeholder="Buat password"
                    required
                    minlength="6"
                />
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="field">
                <label for="password_confirmation" class="field-label">Konfirmasi Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Ulangi password"
                    required
                    minlength="6"
                />
            </div>

            {{-- no email field --}}

            <button type="submit" class="btn btn-primary btn-full mt-12">Daftar</button>

            <div class="auth-footer">
                <span class="muted">Sudah punya akun?</span>
                <a href="/login" class="auth-link">Login</a>
            </div>
        </form>
    </div>
</div>

@endsection

