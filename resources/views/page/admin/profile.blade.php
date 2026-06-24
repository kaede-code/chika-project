@extends('layout.app')

@section('content')

@php
    $user = auth()->user();
    $roleLabel = match($user->role) {
        'master_admin' => 'Master Admin',
        'admin' => 'Admin',
        default => 'Admin',
    };
@endphp

<div class="ttl">Profile</div>

@if (session('success'))
    <div class="alert-box" style="margin-bottom:12px;">{{ session('success') }}</div>
@endif

<div class="profile-card" style="padding:14px; margin-bottom:14px;">
    <div style="display:flex; align-items:center; gap:12px;">
        @php($avatarUrl = $user->avatar ? asset('storage/' . $user->avatar) : null)
        @if($avatarUrl)
            <img class="profile-avatar" src="{{ $avatarUrl }}" alt="Avatar" />
        @else
            <div class="profile-avatar profile-avatar-placeholder" aria-hidden="true">👤</div>
        @endif
        <div>
            <div style="font-weight:900; font-size:15px;">{{ $user->name }}</div>
            <div style="font-weight:800; font-size:12px; color:rgba(30,41,59,.5);">{{ $roleLabel }}</div>
        </div>
    </div>
</div>

<div style="display:flex; flex-direction:column; gap:8px;" id="adminProfileMenu">
    <button class="profile-menu-btn" onclick="showAdminForm()">🏦 Pusat Admin</button>
    <button class="profile-menu-btn" onclick="alert('Fitur belum tersedia')">⚙️ Pengaturan</button>
    <button class="profile-menu-btn" onclick="alert('Fitur belum tersedia')">📄 Kebijakan</button>
    <form method="POST" action="{{ url('/logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="profile-menu-btn btn-logout">🚪 Logout</button>
    </form>
</div>

{{-- Pusat Admin (read-only) --}}
<div id="adminForm" style="display:none;">
    <div class="profile-card" style="padding:14px;">
        <h3 class="profile-title" style="margin:0 0 14px; font-size:16px;">Data Admin</h3>

        <div class="profile-row">
            <div class="profile-label">Nama</div>
            <div class="profile-value">{{ $user->name }}</div>
        </div>
        <div class="profile-row" style="border-bottom:none;">
            <div class="profile-label">Nomor HP</div>
            <div class="profile-value">{{ $user->no_hp ?? '-' }}</div>
        </div>

        <div style="margin-top:14px;">
            <button type="button" class="btn btn-accent btn-full" onclick="hideAdminForm()">← Kembali</button>
        </div>
    </div>

    @if($user->role === 'master_admin')
        <div class="profile-card" style="padding:14px; margin-top:12px;">
            <h3 class="profile-title" style="margin:0 0 12px; font-size:16px;">Manajemen Akun</h3>
            <a href="{{ route('admin.users') }}" class="btn btn-primary btn-full">👥 Kelola Pengguna</a>
        </div>
    @endif
</div>

<style>
.profile-menu-btn {
    width:100%;
    padding:12px 14px;
    border-radius:12px;
    font-weight:900;
    font-size:14px;
    background:#ffffff;
    color:var(--text);
    border:1px solid rgba(2,6,23,.06);
    cursor:pointer;
    text-align:left;
    display:flex;
    align-items:center;
    gap:10px;
    transition:all .15s;
    box-shadow:0 2px 8px rgba(2,6,23,.03);
}
.profile-menu-btn:hover {
    background:#f8fafc;
    border-color:rgba(2,6,23,.12);
    box-shadow:0 4px 12px rgba(2,6,23,.06);
}
.profile-menu-btn:active { transform:scale(.99); }
</style>

<script>
function showAdminForm() {
    document.getElementById('adminProfileMenu').style.display = 'none';
    document.getElementById('adminForm').style.display = 'block';
}
function hideAdminForm() {
    document.getElementById('adminProfileMenu').style.display = 'flex';
    document.getElementById('adminForm').style.display = 'none';
}
</script>

@endsection
