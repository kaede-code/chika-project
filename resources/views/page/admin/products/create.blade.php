@extends('layout.app')

@section('content')

<div class="ttl">Tambah Produk</div>

@if (session('success'))
    <div class="alert-box" style="margin-bottom:12px;">{{ session('success') }}</div>
@endif

<div class="content-box" style="margin-top:14px; max-width:700px;">
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:12px;">
        @csrf

        <div class="field">
            <label class="field-label">Gambar Produk</label>
            <div class="upload-area" id="uploadArea">
                <input type="file" id="gambar" name="gambar" accept="image/*" onchange="handleFiles(this.files)" style="display:none;" />
                <div id="uploadPlaceholder">
                    <div style="font-size:28px; margin-bottom:6px;">📁</div>
                    <div style="font-weight:900; font-size:14px;">Tarik & lepas gambar di sini</div>
                    <div style="font-weight:800; font-size:12px; color:rgba(30,41,59,.5); margin-top:2px;">atau klik untuk memilih file</div>
                </div>
                <div id="uploadPreview" style="display:none; position:relative;">
                    <img id="imagePreview" src="" alt="Preview" style="width:100%; max-height:200px; object-fit:contain; border-radius:8px;" />
                    <button type="button" class="upload-remove" onclick="removeFile()">✕</button>
                </div>
            </div>
            @error('gambar')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label class="field-label" for="nama_produk">Nama Produk</label>
            <input id="nama_produk" name="nama_produk" type="text" value="{{ old('nama_produk') }}" required>
            @error('nama_produk')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label class="field-label" for="harga">Harga Produk</label>
            <input id="harga" name="harga" type="number" min="0.01" step="0.01" value="{{ old('harga') }}" required>
            @error('harga')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label class="field-label" for="kategori">Kategori</label>
            <select id="kategori" name="kategori" required>
                <option value="Jus" {{ old('kategori') === 'Jus' ? 'selected' : '' }}>Jus</option>
                <option value="Salad" {{ old('kategori') === 'Salad' ? 'selected' : '' }}>Salad</option>
            </select>
            @error('kategori')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div style="display:flex; gap:10px; margin-top:6px;">
            <a href="{{ route('admin.products.index') }}" class="btn btn-accent" style="flex:1; text-align:center;">Batal</a>
            <button type="submit" class="btn btn-primary" style="flex:1; text-align:center;">Simpan Produk</button>
        </div>
    </form>
</div>

<style>
.upload-area {
    width:100%;
    min-height:160px;
    border:2px dashed rgba(2,6,23,.15);
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    transition:all .2s;
    background:#fafafa;
    padding:16px;
}
.upload-area:hover, .upload-area.drag-over {
    border-color:var(--primary);
    background:var(--green-soft);
}
.upload-remove {
    position:absolute;
    top:6px;
    right:6px;
    width:28px;
    height:28px;
    border-radius:50%;
    border:none;
    background:rgba(0,0,0,.5);
    color:#fff;
    font-weight:900;
    font-size:14px;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
}
</style>

<script>
var uploadArea = document.getElementById('uploadArea');
var fileInput = document.getElementById('gambar');
var placeholder = document.getElementById('uploadPlaceholder');
var preview = document.getElementById('uploadPreview');
var previewImg = document.getElementById('imagePreview');

uploadArea.addEventListener('click', function(e) {
    if (e.target.closest('.upload-remove')) return;
    fileInput.click();
});

uploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('drag-over');
});

uploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.classList.remove('drag-over');
});

uploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('drag-over');
    if (e.dataTransfer.files.length) {
        fileInput.files = e.dataTransfer.files;
        handleFiles(e.dataTransfer.files);
    }
});

function handleFiles(files) {
    if (!files || !files.length) return;
    var file = files[0];
    if (!file.type.startsWith('image/')) {
        alert('File harus berupa gambar.');
        return;
    }
    var reader = new FileReader();
    reader.onload = function(e) {
        previewImg.src = e.target.result;
        placeholder.style.display = 'none';
        preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
}

function removeFile() {
    fileInput.value = '';
    preview.style.display = 'none';
    placeholder.style.display = 'block';
    previewImg.src = '';
}
</script>

@endsection
