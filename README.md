# Gudang AFNALINK — Warehouse Management System

Webapp gudang untuk AFNALINK (Laravel 12 + PWA). Tema modern dark identik `afnalink_app` (`#08080f`, glass, cyan/indigo), sidebar desktop + bottom navbar mobile.

**Lokasi:** `C:\xampp\htdocs\gudang-afnalink`  
**URL Dev:** `http://127.0.0.1:8001` (via `php artisan serve --port=8001`)  
**Database:** `gudang_afnalink` (MySQL XAMPP)

## List Data Pegawai (Update: Personil → Pegawai)
Menu **Data Pegawai** (`/pegawai` alias `/karyawan`) — list nama & jabatan 7: **Teknisi, NOC, Marketing, Kasir, CEO, CFO, CMO** (`app\Models\Karyawan.php:1`, `database\migrations\2024_01_02_000006_create_karyawans_table.php:1`). Fitur: CRUD, search, filter jabatan/status, **Import Excel + Download Template** (`app\Imports\KaryawanImport.php:1`, `app\Exports\KaryawanExport.php:1`, `app\Exports\PegawaiTemplateExport.php:1`), ringkasan per-jabatan. Dipakai di form **Barang Keluar** (`resources\views\barang-keluar\create.blade.php:1`) — pilih jabatan → dropdown nama dari Data Pegawai (atau input manual).

## Import & Template
- **Data Barang** (`/barang`) — **Import Excel** (`app\Imports\BarangImport.php:1`, header `nama|kategori|merk|stok|satuan|serial_number|spesifikasi`) + **Download Template** (`app\Exports\BarangTemplateExport.php:1`, `GET /barang/template`) — contoh 4 baris, header cyan. Jika nama+kategori sudah ada, stok akan ditambah (update), bukan duplikat.
- **Data Pegawai** (`/pegawai`) — Import + **Download Template** (`GET /pegawai/template` atau `/karyawan/template`, header `nama|jabatan|no_hp|email|alamat|status`, contoh 5 baris, header indigo) + Export.

## Fitur
- **Auth real** — Admin & Operator (login `admin@afnalink.my.id` / `operator@afnalink.my.id`, password `password`)
- **Role:** Admin full + Kelola Users (7 jabatan: Teknisi/NOC/Marketing/Finance/CEO/CFO/CMO). Operator bisa Edit/Tambah/Hapus/Update Barang, Input Masuk/Keluar, Laporan — hanya tidak bisa Kelola Users.
- **Data Barang:** CRUD, search/kategori, filter low-stock (≤5), pagination 20, foto, satuan, serial number
- **Barang Masuk:** Form + Import Excel (`nama_barang|kategori|jumlah|merk|satuan|sumber|catatan`) + validasi per-baris + stok auto + Export
- **Barang Keluar (Pengambilan):** Pilih teknisi (nama + jabatan) → barang (stok>0) → jumlah (validasi cegah minus) → SN opsional → keperluan → stok auto berkurang
- **Dashboard:** Total barang/stok, low-stock alert, masuk/keluar hari ini, recent activity
- **Laporan:** Audit log + filter tanggal + Export Excel, ringkasan masuk/keluar
- **PWA:** `manifest.json` + `sw.js` (standalone, shortcuts, offline cache asset), installable HP/desktop
- **Theme:** Dark (#08080f) default + Light toggle (localStorage), glass, Inter font

## Quick Start
```bash
cd C:\xampp\htdocs\gudang-afnalink
php artisan serve --host=127.0.0.1 --port=8001
# atau via XAMPP Apache: http://localhost/gudang-afnalink/public
npm run build  # rebuild asset jika ubah css/js
```

## Akun Demo
| Role | Email | Password | Jabatan |
|------|-------|----------|---------|
| Admin | admin@afnalink.my.id | password | CEO |
| Operator | operator@afnalink.my.id | password | NOC |

## Struktur
- `app/Http/Controllers` — Auth, Dashboard, Barang, BarangMasuk, BarangKeluar, Laporan, User
- `app/Models` — User (jabatan/role), Barang, BarangMasuk, BarangKeluar, AuditLog
- `app/Imports|Exports` — maatwebsite/excel
- `resources/views` — layouts/app (sidebar+bottom nav), auth, dashboard, barang, barang-masuk, barang-keluar, laporan, users
- `public/manifest.json`, `public/sw.js`

## API/Route
`GET /dashboard`, `resource barang`, `barang-masuk|keluar`, `laporan`, `users` (admin only), `export/barang`, `barang-masuk/export|import`, `laporan/export`

## Catatan
- Stok minus dicegah (server + audit). Low-stock threshold 5.
- Foto barang di `storage/app/public/barang` (link `public/storage`).
- Excel: import pakai heading row, export .xlsx.
