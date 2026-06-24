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

<div class="profile-card" style="padding:14px; margin-bottom:14px;" id="profileHeader">
    <div style="display:flex; align-items:center; gap:12px;">
        @php($avatarUrl = $user->avatar ? asset('storage/' . $user->avatar) : null)
        @if($avatarUrl)
            <img class="profile-avatar" src="{{ $avatarUrl }}" alt="Avatar" />
        @else
            <div class="profile-avatar profile-avatar-placeholder" aria-hidden="true">👤</div>
        @endif
        <div>
            <div style="font-weight:900; font-size:15px;">{{ $user->name }}</div>
            <div style="font-weight:800; font-size:13px; color:rgba(30,41,59,.6);">{{ $user->no_hp ?? '-' }}</div>
            <div style="font-weight:800; font-size:11px; color:rgba(30,41,59,.45);">{{ $roleLabel }}</div>
        </div>
    </div>
</div>

<div style="display:flex; flex-direction:column; gap:8px;" id="profileMenu">
    <button class="profile-menu-btn" onclick="showForm()">🏦 Pusat Akun</button>
    @if($user->role === 'master_admin')
        <a href="{{ route('admin.users') }}" class="profile-menu-btn" style="text-decoration:none;">👥 Kelola User</a>
    @endif
    <button class="profile-menu-btn" onclick="alert('Fitur belum tersedia')">⚙️ Pengaturan</button>
    <button class="profile-menu-btn" onclick="alert('Fitur belum tersedia')">📄 Kebijakan</button>
    <form method="POST" action="{{ url('/logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="profile-menu-btn btn-logout">🚪 Logout</button>
    </form>
</div>

<div id="editForm" style="display:none;">
    <div class="profile-card" style="padding:14px;">
        <h3 class="profile-title" style="margin:0 0 14px; font-size:16px;">Edit Data Diri</h3>

        <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf

            <div class="field">
                <label for="name" class="field-label">Nama</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required maxlength="255" />
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field" style="margin-top:12px;">
                <label for="no_hp" class="field-label">No HP</label>
                <input id="no_hp" name="no_hp" type="text" value="{{ old('no_hp', $user->no_hp) }}" required maxlength="30" />
                @error('no_hp')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div style="display:flex; gap:10px; margin-top:14px;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Simpan</button>
                <button type="button" class="btn btn-accent" style="flex:1;" onclick="hideForm()">Batal</button>
            </div>
        </form>
    </div>
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
function showForm() {
    document.getElementById('profileHeader').style.display = 'none';
    document.getElementById('profileMenu').style.display = 'none';
    document.getElementById('editForm').style.display = 'block';
}
function hideForm() {
    document.getElementById('profileHeader').style.display = 'block';
    document.getElementById('profileMenu').style.display = 'flex';
    document.getElementById('editForm').style.display = 'none';
}
</script>

@endsection
