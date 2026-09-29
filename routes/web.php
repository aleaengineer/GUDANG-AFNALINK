<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\ProfileController;
use App\Exports\BarangExport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return redirect()->route('login');
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile - semua role
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Dashboard - semua role
    Route::middleware('role:admin,operator,teknisi')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    // Barang Keluar (Pengambilan) - admin, operator, teknisi
    Route::middleware('role:admin,operator,teknisi')->group(function () {
        Route::get('/barang-keluar', [BarangKeluarController::class, 'index'])->name('barang-keluar.index');
        Route::get('/barang-keluar/create', [BarangKeluarController::class, 'create'])->name('barang-keluar.create');
        Route::post('/barang-keluar', [BarangKeluarController::class, 'store'])->name('barang-keluar.store');
    });

    // Admin + Operator
    Route::middleware('role:admin,operator')->group(function () {
        // Hapus pengambilan (stok dikembalikan) - tanpa teknisi
        Route::delete('/barang-keluar/{barangKeluar}', [BarangKeluarController::class, 'destroy'])->name('barang-keluar.destroy');

        // Barang - hapus butuh verifikasi admin jika operator
        Route::get('/barang/template', [BarangController::class, 'template'])->name('barang.template');
        Route::post('/barang/import', [BarangController::class, 'import'])->name('barang.import');
        Route::post('/barang/{barang}/verify', [BarangController::class, 'verify'])->name('barang.verify');
        Route::post('/barang/{barang}/cancel', [BarangController::class, 'cancelRequest'])->name('barang.cancel');
        Route::resource('barang', BarangController::class);
        Route::get('/export/barang', function () {
            return Excel::download(new BarangExport, 'barang-'.date('Y-m-d').'.xlsx');
        })->name('barang.export');

        // Barang Masuk
        Route::get('/barang-masuk', [BarangMasukController::class, 'index'])->name('barang-masuk.index');
        Route::get('/barang-masuk/create', [BarangMasukController::class, 'create'])->name('barang-masuk.create');
        Route::post('/barang-masuk', [BarangMasukController::class, 'store'])->name('barang-masuk.store');
        Route::post('/barang-masuk/import', [BarangMasukController::class, 'import'])->name('barang-masuk.import');
        Route::get('/barang-masuk/export', [BarangMasukController::class, 'export'])->name('barang-masuk.export');
        Route::delete('/barang-masuk/{barangMasuk}', [BarangMasukController::class, 'destroy'])->name('barang-masuk.destroy');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

        // Pegawai / Karyawan - list nama & jabatan (Teknisi/NOC/Marketing/Kasir/CEO/CFO/CMO)
        Route::get('/karyawan/export', [KaryawanController::class, 'export'])->name('karyawan.export');
        Route::get('/karyawan/template', [KaryawanController::class, 'template'])->name('karyawan.template');
        Route::post('/karyawan/import', [KaryawanController::class, 'import'])->name('karyawan.import');
        Route::resource('karyawan', KaryawanController::class)->except(['show']);
        // Alias Pegawai untuk URL yang lebih user-friendly
        Route::get('/pegawai', [KaryawanController::class, 'index'])->name('pegawai.index');
        Route::get('/pegawai/export', [KaryawanController::class, 'export'])->name('pegawai.export');
        Route::get('/pegawai/template', [KaryawanController::class, 'template'])->name('pegawai.template');
        Route::post('/pegawai/import', [KaryawanController::class, 'import'])->name('pegawai.import');
        Route::get('/pegawai/create', [KaryawanController::class, 'create'])->name('pegawai.create');
        Route::post('/pegawai', [KaryawanController::class, 'store'])->name('pegawai.store');
        Route::get('/pegawai/{karyawan}/edit', [KaryawanController::class, 'edit'])->name('pegawai.edit');
        Route::put('/pegawai/{karyawan}', [KaryawanController::class, 'update'])->name('pegawai.update');
        Route::delete('/pegawai/{karyawan}', [KaryawanController::class, 'destroy'])->name('pegawai.destroy');
    });

    // Admin only - Users & Verifikasi Hapus
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/verifikasi', [\App\Http\Controllers\VerifikasiController::class, 'index'])->name('verifikasi.index');
    });
});
