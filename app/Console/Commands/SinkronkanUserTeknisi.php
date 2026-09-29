<?php
namespace App\Console\Commands;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SinkronkanUserTeknisi extends Command
{
    protected $signature = 'user:sinkron-teknisi
                            {--password=afnalink123 : Password untuk akun teknisi yang dibuat}
                            {--dry-run : Tampilkan rencana tanpa menyimpan}';

    protected $description = 'Buat akun login role teknisi dari Data Pegawai jabatan Teknisi (email dipakai sebagai username)';

    public function handle(): int
    {
        $password = (string) $this->option('password');
        $dry = (bool) $this->option('dry-run');

        if (mb_strlen($password) < 6) {
            $this->error('Password minimal 6 karakter.');
            return self::FAILURE;
        }

        $karyawans = Karyawan::where('jabatan', 'Teknisi')
            ->where('status', 'aktif')
            ->whereNotNull('email')
            ->orderBy('nama')
            ->get();

        if ($karyawans->isEmpty()) {
            $this->warn('Tidak ada pegawai jabatan Teknisi yang aktif dan punya email.');
            return self::SUCCESS;
        }

        $rows = [];
        $skip = [];

        foreach ($karyawans as $k) {
            $email = trim((string) $k->email);

            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skip[] = [$k->nama, $email !== '' ? $email : '(kosong)', 'email tidak valid'];
                continue;
            }

            if (User::where('email', $email)->exists()) {
                $skip[] = [$k->nama, $email, 'sudah punya akun'];
                continue;
            }

            $rows[] = $k;
        }

        $this->table(
            ['ID PEGAWAI', 'NAMA', 'EMAIL (USERNAME)', 'NO HP', 'JABATAN', 'ROLE', 'AKSI'],
            array_map(fn ($k) => [
                $k->id, $k->nama, trim((string) $k->email), $k->no_hp ?? '-',
                'Teknisi', 'teknisi', 'akan dibuat',
            ], $rows)
        );

        if ($skip !== []) {
            $this->newLine();
            $this->table(['NAMA', 'EMAIL', 'ALASAN DILEWATI'], $skip);
        }

        $this->newLine();
        $this->info(sprintf('%d akun akan dibuat, %d dilewati.', count($rows), count($skip)));

        if ($rows === []) {
            return self::SUCCESS;
        }

        if ($dry) {
            $this->comment('--dry-run aktif, tidak ada perubahan ke database.');
            return self::SUCCESS;
        }

        $created = [];
        DB::transaction(function () use ($rows, $password, &$created) {
            foreach ($rows as $k) {
                User::create([
                    'name' => $k->nama,
                    'email' => trim((string) $k->email),
                    'password' => $password,
                    'jabatan' => 'Teknisi',
                    'role' => 'teknisi',
                    'no_hp' => $k->no_hp,
                ]);
                $created[] = trim((string) $k->email);
            }
        });

        $this->info('Selesai. ' . count($created) . ' akun role teknisi berhasil dibuat.');
        $this->line('Password semua akun: ' . $password);
        $this->comment('Saran: minta setiap teknisi mengganti password lewat menu Profile.');

        return self::SUCCESS;
    }
}
