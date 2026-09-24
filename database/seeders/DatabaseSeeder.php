<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Barang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin AFNALINK',
            'email' => 'admin@afnalink.my.id',
            'password' => Hash::make('password'),
            'jabatan' => 'CEO',
            'role' => 'admin',
        ]);

        // Operator
        User::create([
            'name' => 'Operator Gudang',
            'email' => 'operator@afnalink.my.id',
            'password' => Hash::make('password'),
            'jabatan' => 'NOC',
            'role' => 'operator',
        ]);

        // Sample teknisi users
        $jabatans = ['Teknisi','NOC','Marketing','Kasir','CFO','CMO'];
        foreach ($jabatans as $j) {
            User::create([
                'name' => 'Sample '.$j,
                'email' => strtolower($j).'@afnalink.my.id',
                'password' => Hash::make('password'),
                'jabatan' => $j,
                'role' => 'operator',
            ]);
        }

        // Sample karyawan / personil list data (7 jabatan)
        $karyawans = [
            ['nama' => 'Budi Santoso', 'jabatan' => 'Teknisi', 'no_hp' => '081234567801', 'status' => 'aktif'],
            ['nama' => 'Andi Wijaya', 'jabatan' => 'Teknisi', 'no_hp' => '081234567802', 'status' => 'aktif'],
            ['nama' => 'Siti Rahayu', 'jabatan' => 'NOC', 'no_hp' => '081234567803', 'status' => 'aktif'],
            ['nama' => 'Rina Marlina', 'jabatan' => 'Marketing', 'no_hp' => '081234567804', 'status' => 'aktif'],
            ['nama' => 'Dewi Kasir', 'jabatan' => 'Kasir', 'no_hp' => '081234567805', 'status' => 'aktif'],
            ['nama' => 'Pak Direktur', 'jabatan' => 'CEO', 'no_hp' => '081234567806', 'status' => 'aktif'],
            ['nama' => 'Bu CFO', 'jabatan' => 'CFO', 'no_hp' => '081234567807', 'status' => 'aktif'],
            ['nama' => 'Pak CMO', 'jabatan' => 'CMO', 'no_hp' => '081234567808', 'status' => 'aktif'],
            ['nama' => 'Joko Teknisi', 'jabatan' => 'Teknisi', 'no_hp' => '081234567809', 'status' => 'aktif'],
            ['nama' => 'Agus NOC', 'jabatan' => 'NOC', 'no_hp' => '081234567810', 'status' => 'aktif'],
        ];
        foreach ($karyawans as $k) {
            \App\Models\Karyawan::create($k);
        }

        // Sample barangs
        $barangs = [
            ['nama' => 'Router TP-Link TL-WR840N', 'kategori' => 'Router', 'merk' => 'TP-Link', 'stok' => 25, 'satuan' => 'pcs'],
            ['nama' => 'Router ZTE F670L', 'kategori' => 'Router', 'merk' => 'ZTE', 'stok' => 15, 'satuan' => 'pcs'],
            ['nama' => 'ONT Huawei HG8245H', 'kategori' => 'ONT', 'merk' => 'Huawei', 'stok' => 30, 'satuan' => 'pcs'],
            ['nama' => 'OLT ZTE C320', 'kategori' => 'OLT', 'merk' => 'ZTE', 'stok' => 2, 'satuan' => 'pcs'],
            ['nama' => 'Kabel FO Dropcore 100m', 'kategori' => 'Kabel FO', 'merk' => 'Generic', 'stok' => 50, 'satuan' => 'roll'],
            ['nama' => 'Splitter 1:8', 'kategori' => 'Splitter', 'merk' => 'Generic', 'stok' => 12, 'satuan' => 'pcs'],
            ['nama' => 'Patchcord SC-SC 5m', 'kategori' => 'Patchcord', 'merk' => 'Generic', 'stok' => 40, 'satuan' => 'pcs'],
            ['nama' => 'Switch 8 Port', 'kategori' => 'Switch', 'merk' => 'TP-Link', 'stok' => 8, 'satuan' => 'pcs'],
            ['nama' => 'Access Point Ubiquiti', 'kategori' => 'Access Point', 'merk' => 'Ubiquiti', 'stok' => 4, 'satuan' => 'pcs'],
            ['nama' => 'Konektor SC', 'kategori' => 'Lainnya', 'merk' => 'Generic', 'stok' => 100, 'satuan' => 'pcs'],
        ];

        foreach ($barangs as $b) {
            Barang::create($b);
        }
    }
}
