@extends('layout.app')

@section('content')

<div class="ttl">Manajemen Akun</div>

@if (session('success'))
    <div class="alert-box" style="margin-bottom:12px;">{{ session('success') }}</div>
@endif

<div class="content-box" style="padding:0; overflow-x:hidden;">
    @if($users->isEmpty())
        <div style="padding:16px;">Belum ada pengguna.</div>
    @else
        <div style="display:flex; flex-direction:column; gap:10px; padding:14px;">
            @foreach($users as $user)
                @php
                    $roleBadge = match($user->role) {
                        'master_admin' => 'badge badge-proc',
                        'admin' => 'badge badge-done',
                        'customer' => 'badge badge-wait',
                        default => 'badge',
                    };
                    $roleLabel = match($user->role) {
                        'master_admin' => 'Master Admin',
                        'admin' => 'Admin',
                        'customer' => 'Customer',
                        default => $user->role,
                    };
                @endphp

                <div class="user-card">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div class="profile-avatar profile-avatar-placeholder" style="width:42px; height:42px; font-size:16px; border-radius:12px;">👤</div>
                        <div style="flex:1; min-width:0;">
                            <div style="font-weight:900; font-size:14px;">{{ $user->name }}</div>
                            <div style="font-weight:800; font-size:12px; color:rgba(30,41,59,.5);">{{ $user->no_hp ?? '-' }}</div>
                        </div>
                        <span class="{{ $roleBadge }}">{{ $roleLabel }}</span>
                    </div>

                    <hr style="border:none; border-top:1px solid rgba(0,0,0,.06); margin:10px 0;" />

                    <div style="display:flex; flex-direction:column; gap:8px;">
                        <form method="POST" action="{{ route('admin.users.role', $user->id) }}" style="display:flex; gap:8px; align-items:center;">
                            @csrf
                            <select name="role" style="flex:1; padding:8px 10px; border-radius:10px; border:1px solid rgba(2,6,23,.12); font-weight:800; font-size:13px;">
                                <option value="master_admin" {{ $user->role === 'master_admin' ? 'selected' : '' }}>Master Admin</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                            </select>
                            <button type="submit" class="btn btn-primary" style="min-height:36px; padding:0 12px; font-size:12px;">Simpan</button>
                        </form>

                        <form method="POST" action="{{ route('admin.users.password', $user->id) }}" style="display:flex; gap:8px; align-items:center;">
                            @csrf
                            <input type="password" name="password" placeholder="Password baru" required minlength="6" style="flex:1; padding:8px 10px; border-radius:10px; border:1px solid rgba(2,6,23,.12); font-weight:800; font-size:13px;" />
                            <input type="password" name="password_confirmation" placeholder="Konfirmasi" required minlength="6" style="flex:1; padding:8px 10px; border-radius:10px; border:1px solid rgba(2,6,23,.12); font-weight:800; font-size:13px;" />
                            <button type="submit" class="btn btn-accent" style="min-height:36px; padding:0 12px; font-size:12px;">Ganti</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.user-card {
    background:#fff;
    border-radius:14px;
    padding:14px;
    box-shadow:0 4px 16px rgba(2,6,23,.05);
    border:1px solid rgba(2,6,23,.04);
}
</style>

@endsection
