@extends('layout.app')

@section('content')

<div class="ttl">Kelola User</div>

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
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary" style="min-height:36px; padding:0 14px; font-size:12px; text-decoration:none; display:inline-flex; align-items:center;">Edit</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div style="padding:14px;">
        {{ $users->links() }}
    </div>
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
