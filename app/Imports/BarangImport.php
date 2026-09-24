<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\AuditLog;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BarangImport implements ToCollection, WithHeadingRow
{
    private $imported = 0;
    private $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $i => $row) {
            $idx = $i + 2;
            try {
                $nama = trim($row['nama'] ?? $row['nama_barang'] ?? '');
                $kategori = trim($row['kategori'] ?? 'Lainnya');
                $merk = trim($row['merk'] ?? '');
                $stok = (int)($row['stok'] ?? $row['jumlah'] ?? 0);
                $satuan = trim($row['satuan'] ?? 'pcs');
                $serial = trim($row['serial_number'] ?? $row['sn'] ?? '');
                $spesifikasi = trim($row['spesifikasi'] ?? $row['deskripsi'] ?? '');

                if (empty($nama)) { $this->errors[] = "Baris {$idx}: Nama wajib diisi"; continue; }
                if ($stok < 0) { $this->errors[] = "Baris {$idx}: Stok tidak boleh negatif"; continue; }
                if (empty($kategori)) $kategori = 'Lainnya';
                if (empty($satuan)) $satuan = 'pcs';

                $existing = Barang::where('nama', $nama)->where('kategori', $kategori)->first();
                if ($existing) {
                    $existing->update([
                        'merk' => $merk ?: $existing->merk,
                        'stok' => $existing->stok + $stok,
                        'satuan' => $satuan,
                        'serial_number' => $serial ?: $existing->serial_number,
                        'spesifikasi' => $spesifikasi ?: $existing->spesifikasi,
                    ]);
                } else {
                    Barang::create([
                        'nama' => $nama,
                        'kategori' => $kategori,
                        'merk' => $merk ?: null,
                        'stok' => $stok,
                        'satuan' => $satuan,
                        'serial_number' => $serial ?: null,
                        'spesifikasi' => $spesifikasi ?: null,
                    ]);
                }
                $this->imported++;
            } catch (\Exception $e) {
                $this->errors[] = "Baris {$idx}: ".$e->getMessage();
            }
        }
        if ($this->imported > 0) AuditLog::record(auth()->id(), 'import_barang', "Import barang: {$this->imported} data");
    }

    public function getImportedCount() { return $this->imported; }
    public function getErrors() { return $this->errors; }
}
