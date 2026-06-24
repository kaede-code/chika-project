@extends('layout.app')

@section('content')

@php
    $roleLabel = match($user->role) {
        'master_admin' => 'Master Admin',
        'admin' => 'Admin',
        'customer' => 'Customer',
        default => $user->role,
    };
@endphp

<div class="ttl">Edit User</div>

@if (session('success'))
    <div class="alert-box" style="margin-bottom:12px;">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert-box" style="margin-bottom:12px; background:#fef2f2; color:#dc2626;">{{ session('error') }}</div>
@endif

<div class="content-box" style="padding:14px;">
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
        <div class="profile-avatar profile-avatar-placeholder" style="width:48px; height:48px; font-size:18px; border-radius:14px;">👤</div>
        <div>
            <div style="font-weight:900; font-size:15px;">{{ $user->name }}</div>
            <div style="font-weight:800; font-size:12px; color:rgba(30,41,59,.5);">{{ $roleLabel }}</div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PUT')

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

        <div class="field" style="margin-top:12px;">
            <label for="role" class="field-label">Role</label>
            <select id="role" name="role" style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid rgba(2,6,23,.12); font-weight:800; font-size:13px;">
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                @if($user->role === 'master_admin')
                    <option value="master_admin" selected>Master Admin</option>
                @endif
            </select>
            @error('role')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div style="display:flex; gap:10px; margin-top:18px;">
            <button type="submit" class="btn btn-primary" style="flex:1;">Simpan</button>
            <a href="{{ route('admin.users') }}" class="btn btn-accent" style="flex:1; text-align:center; text-decoration:none;">Batal</a>
        </div>
    </form>

    <button type="button" class="btn btn-danger" style="width:100%; margin-top:16px; min-height:40px; font-size:13px;" onclick="showDeleteModal({{ $user->id }}, '{{ $user->name }}')">🗑️ Hapus Akun</button>
</div>

<div id="deleteModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:999; align-items:center; justify-content:center; padding:20px;">
    <div style="background:#fff; border-radius:16px; padding:20px; max-width:340px; width:100%; box-shadow:0 8px 32px rgba(0,0,0,.15);">
        <div style="font-size:28px; text-align:center; margin-bottom:8px;">⚠️</div>
        <h3 style="margin:0 0 6px; font-size:16px; text-align:center;">Hapus Akun</h3>
        <p style="margin:0 0 16px; font-size:13px; color:rgba(30,41,59,.6); text-align:center;" id="deleteModalText">Yakin ingin menghapus akun <strong></strong>?</p>
        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <div style="display:flex; gap:8px;">
                <button type="button" class="btn btn-accent" style="flex:1;" onclick="hideDeleteModal()">Batal</button>
                <button type="submit" class="btn btn-danger" style="flex:1;">Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>

<style>
.btn-danger {
    background:#dc2626;
    color:#fff;
    border:none;
    border-radius:10px;
    font-weight:800;
    cursor:pointer;
    padding:10px 14px;
    transition:all .15s;
}
.btn-danger:hover { background:#b91c1c; }
.btn-danger:active { transform:scale(.97); }
</style>

<script>
function showDeleteModal(id, name) {
    document.getElementById('deleteModalText').innerHTML = 'Yakin ingin menghapus akun <strong>' + name + '</strong>?';
    document.getElementById('deleteForm').action = '/admin/users/' + id;
    document.getElementById('deleteModal').style.display = 'flex';
}
function hideDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}
</script>

@endsection
