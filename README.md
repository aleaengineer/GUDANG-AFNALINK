# GUDANG AFNALINK

Warehouse Management System untuk AFNALINK — kelola stok gudang, catat keluar-masuk barang oleh teknisi, berbasis Laravel + PWA.

## Maksud dan Tujuan
Mencatat dan mengelola inventory gudang AFNALINK secara terpusat: stok barang (router, ONT, kabel FO, dll), riwayat masuk-keluar, dan pengambilan barang oleh teknisi/pegawai agar stok terpantau dan laporan mudah diekspor ke Excel.

## Flow
1. **Operator** login → kelola **Data Barang** (tambah/edit) dan **Data Pegawai** (7 jabatan: Teknisi, NOC, Marketing, Kasir, CEO, CFO, CMO).
2. **Barang Masuk** — operator input stok masuk atau **Import Excel** (template tersedia) → stok otomatis bertambah.
3. **Barang Keluar** — teknisi ambil barang → operator catat **nama teknisi, jabatan, barang, jumlah, SN/keperluan** → stok otomatis berkurang (validasi cegah minus).
4. **Hapus Data Barang** oleh operator → masuk **Verifikasi Admin** → admin Setujui/Tolak di halaman Verifikasi.
5. **Laporan** — filter per bulan/tanggal, lihat riwayat keluar-masuk + audit log, **Export Excel**.
6. **PWA** — installable di HP/desktop, **Dark/Light** toggle.

## Cara Instalasi
```bash
# 1. Clone
git clone https://github.com/aleaengineer/GUDANG-AFNALINK.git
cd GUDANG-AFNALINK

# 2. Install dependency
composer install
npm install

# 3. Env & key
cp .env.example .env
php artisan key:generate

# 4. Database (MySQL, buat db gudang_afnalink)
# Edit .env: DB_DATABASE=gudang_afnalink, DB_USERNAME=root, DB_PASSWORD=
php artisan migrate --seed

# 5. Storage & build
php artisan storage:link
npm run build

# 6. Jalankan
php artisan serve --host=127.0.0.1 --port=8001
# Buka http://127.0.0.1:8001/login
# Admin: admin@afnalink.my.id / password
# Operator: operator@afnalink.my.id / password
```
