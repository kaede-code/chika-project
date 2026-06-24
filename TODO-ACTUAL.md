# TODO-ACTUAL

## 1) Rapikan tampilan customer (card produk)
- [x] Update `resources/views/component/product.blade.php` sesuai layout (card rapi + qty row + Pesan)
- [x] Update CSS di `resources/css/app.css` untuk men-stabilkan ukuran card & alignment

## 2) Rapikan tampilan admin (card produk)
- [x] Update `resources/views/component/product-admin.blade.php` agar gaya sama seperti customer (card + tombol)
- [x] Update CSS di `resources/css/app.css` agar card admin seragam tinggi & rapi

## 3) Perbaiki cart/checkout bar muncul saat login pertama kali
- [x] Periksa & update `resources/js/app.js` untuk show/hide cart berdasarkan jumlah item cart user (berdasarkan DOM count)
- [ ] Pastikan cart count di DOM benar-benar dihitung dari `cart_items` user login (perlu dicek view/server-side)
- [ ] Uji:
  - login customer baru -> cart bar tidak muncul
  - tekan Pesan -> cart bar muncul dengan total qty
  - hapus semua item -> cart bar hilang

