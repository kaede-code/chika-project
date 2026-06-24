# TODO - Perbaikan Struktur Card Produk

## Checklist
- [x] Update `resources/views/component/product.blade.php` (customer card): susunan horizontal, tombol Pesan ↔ quantity switch.

- [ ] Update `resources/views/component/product-admin.blade.php` (admin card): struktur sama persis dengan customer, hanya beda tombol kanan.
- [ ] Update `resources/css/app.css`: samakan tinggi card, gambar & area action sejajar, hilangkan aturan yang mendorong tombol ke bawah.
- [ ] Update `resources/js/app.js`: implement logic UI switch tombol Pesan ↔ quantity (qty=0 kembali tombol Pesan), tetap sinkron input hidden.
- [ ] Verifikasi manual:
  - [ ] Customer: klik Pesan → tampil [-] qty [+], submit/endpoint tetap jalan sesuai backend.
  - [ ] Customer: klik [-] sampai 0 → tampil lagi tombol Pesan.
  - [ ] Admin: tombol Edit tetap rapi di kanan.

