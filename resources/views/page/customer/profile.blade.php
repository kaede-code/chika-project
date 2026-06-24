@extends('layout.app')

@section('content')

@php
    $user = auth()->user();
    $rawAlamat = $user->alamat ?? '';
    if (str_contains($rawAlamat, '||')) {
        $parts = explode('||', $rawAlamat);
        $jalanVal = $parts[0] ?? '';
        $kecamatanVal = $parts[1] ?? '';
        $kabupatenKotaVal = $parts[2] ?? '';
        $provinsiVal = $parts[3] ?? '';
    } else {
        $jalanVal = $rawAlamat;
        $kecamatanVal = '';
        $kabupatenKotaVal = '';
        $provinsiVal = '';
    }
@endphp

<div class="ttl">Profile</div>

@if (session('success'))
    <div class="alert-box" style="margin-bottom:12px;">{{ session('success') }}</div>
@endif

{{-- Profile Header --}}
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
        </div>
    </div>
</div>

{{-- Menu --}}
<div style="display:flex; flex-direction:column; gap:8px;" id="profileMenu">
    <button class="profile-menu-btn" onclick="showForm()">🏦 Pusat Akun</button>
    <button class="profile-menu-btn" onclick="alert('Fitur belum tersedia')">⚙️ Pengaturan</button>
    <button class="profile-menu-btn" onclick="alert('Fitur belum tersedia')">📄 Kebijakan</button>
    <form method="POST" action="{{ url('/logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="profile-menu-btn btn-logout">🚪 Logout</button>
    </form>
</div>

{{-- Edit Form (hidden by default) --}}
<div id="editForm" style="display:none;">
    <div class="profile-card" style="padding:14px;">
        <h3 class="profile-title" style="margin:0 0 14px; font-size:16px;">Edit Data Diri</h3>

        <form method="POST" action="{{ route('customer.profile.update') }}">
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

            <div style="margin-top:14px;">
                <div class="field-label" style="margin-bottom:8px;">Alamat</div>

                <div class="field">
                    <label for="provinsi" class="field-label" style="font-size:12px;">Provinsi</label>
                    <input id="provinsi" name="provinsi" type="text" value="{{ old('provinsi', $provinsiVal) }}" maxlength="255" />
                    @error('provinsi')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field" style="margin-top:8px;">
                    <label for="kabupaten_kota" class="field-label" style="font-size:12px;">Kabupaten/Kota</label>
                    <input id="kabupaten_kota" name="kabupaten_kota" type="text" value="{{ old('kabupaten_kota', $kabupatenKotaVal) }}" maxlength="255" />
                    @error('kabupaten_kota')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field" style="margin-top:8px;">
                    <label for="kecamatan" class="field-label" style="font-size:12px;">Kecamatan</label>
                    <input id="kecamatan" name="kecamatan" type="text" value="{{ old('kecamatan', $kecamatanVal) }}" maxlength="255" />
                    @error('kecamatan')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field" style="margin-top:8px;">
                    <label for="jalan" class="field-label" style="font-size:12px;">Jalan</label>
                    <input id="jalan" name="jalan" type="text" value="{{ old('jalan', $jalanVal) }}" maxlength="500" />
                    @error('jalan')<div class="field-error">{{ $message }}</div>@enderror
                </div>
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
