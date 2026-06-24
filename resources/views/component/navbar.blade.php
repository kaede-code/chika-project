@php use Illuminate\Support\Facades\Auth; @endphp
<div class="nav">
    @auth
        @php($role = optional(auth()->user())->role)
    @endauth

    @if($role === 'customer')
        <a href="/customer/menu" class="nav-item {{ request()->is('customer/menu') ? 'act' : '' }}">
            <span>🍹</span><p>Menu</p>
        </a>
        <a href="/customer/riwayat" class="nav-item {{ request()->is('customer/riwayat') ? 'act' : '' }}">
            <span>📋</span><p>Aktivitas</p>
        </a>
        <a href="/customer/profile" class="nav-item {{ request()->is('customer/profile') ? 'act' : '' }}">
            <span>👤</span><p>Profile</p>
        </a>
    @elseif($role === 'admin' || $role === 'master_admin')
        <a href="/admin/dashboard" class="nav-item {{ request()->is('admin/dashboard') ? 'act' : '' }}">
            <span>📊</span><p>Dashboard</p>
        </a>
        <a href="/admin/products" class="nav-item {{ request()->is('admin/products') ? 'act' : '' }}">
            <span>🍎</span><p>Produk</p>
        </a>
        <a href="/admin/orders" class="nav-item {{ request()->is('admin/orders') ? 'act' : '' }}">
            <span>📋</span><p>Aktivitas</p>
        </a>
        @if($role === 'master_admin')
            <a href="/admin/users" class="nav-item {{ request()->is('admin/users') ? 'act' : '' }}">
                <span>👥</span><p>Users</p>
            </a>
        @endif
        <a href="/admin/profile" class="nav-item {{ request()->is('admin/profile') ? 'act' : '' }}">
            <span>👤</span><p>Profile</p>
        </a>
    @endif
</div>
