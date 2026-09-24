<?php

namespace App\Imports;

use App\Models\Karyawan;
use App\Models\AuditLog;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KaryawanImport implements ToCollection, WithHeadingRow
{
    private $imported = 0;
    private $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $i => $row) {
            $idx = $i + 2;
            try {
                $nama = trim($row['nama'] ?? $row['nama_lengkap'] ?? '');
                $jabatan = trim($row['jabatan'] ?? 'Teknisi');
                $no_hp = trim($row['no_hp'] ?? $row['hp'] ?? $row['telepon'] ?? '');
                $email = trim($row['email'] ?? '');
                $status = trim($row['status'] ?? 'aktif');

                $validJabatan = ['Teknisi','NOC','Marketing','Kasir','CEO','CFO','CMO','Finance'];
                if (empty($nama)) { $this->errors[] = "Baris {$idx}: Nama wajib diisi"; continue; }
                if (!in_array($jabatan, $validJabatan)) { $this->errors[] = "Baris {$idx}: Jabatan '{$jabatan}' tidak valid (harus: ".implode(', ', $validJabatan).")"; continue; }
                if (!in_array($status, ['aktif','nonaktif'])) $status = 'aktif';

                Karyawan::create([
                    'nama' => $nama,
                    'jabatan' => $jabatan,
                    'no_hp' => $no_hp ?: null,
                    'email' => $email ?: null,
                    'alamat' => null,
                    'status' => $status,
                ]);
                $this->imported++;
            } catch (\Exception $e) {
                $this->errors[] = "Baris {$idx}: ".$e->getMessage();
            }
        }
        if ($this->imported > 0) AuditLog::record(auth()->id(), 'import_karyawan', "Import pegawai: {$this->imported} data");
    }

    public function getImportedCount() { return $this->imported; }
    public function getErrors() { return $this->errors; }
}
