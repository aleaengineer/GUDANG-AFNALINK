<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KaryawanExport;
use App\Imports\KaryawanImport;
use App\Exports\PegawaiTemplateExport;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::query();
        if ($s = $request->search) $query->where('nama', 'like', "%{$s}%");
        if ($j = $request->jabatan) $query->where('jabatan', $j);
        if ($st = $request->status) $query->where('status', $st);
        $karyawans = $query->orderBy('jabatan')->orderBy('nama')->paginate(20)->withQueryString();
        $jabatans = ['Teknisi','NOC','Marketing','Kasir','CEO','CFO','CMO'];
        return view('karyawan.index', compact('karyawans','jabatans'));
    }

    public function create()
    {
        $jabatans = ['Teknisi','NOC','Marketing','Kasir','CEO','CFO','CMO'];
        return view('karyawan.create', compact('jabatans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|in:Teknisi,NOC,Marketing,Kasir,CEO,CFO,CMO,Finance',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);
        $k = Karyawan::create($data);
        AuditLog::record(auth()->id(), 'create_karyawan', "Tambah pegawai: {$k->nama} ({$k->jabatan})");
        return redirect()->route('karyawan.index')->with('success', 'Pegawai berhasil ditambahkan');
    }

    public function edit(Karyawan $karyawan)
    {
        $jabatans = ['Teknisi','NOC','Marketing','Kasir','CEO','CFO','CMO'];
        return view('karyawan.edit', compact('karyawan','jabatans'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|in:Teknisi,NOC,Marketing,Kasir,CEO,CFO,CMO,Finance',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);
        $karyawan->update($data);
        AuditLog::record(auth()->id(), 'update_karyawan', "Update pegawai: {$karyawan->nama} ({$karyawan->jabatan})");
        return redirect()->route('karyawan.index')->with('success', 'Pegawai berhasil diupdate');
    }

    public function destroy(Karyawan $karyawan)
    {
        $nama = $karyawan->nama;
        $karyawan->delete();
        AuditLog::record(auth()->id(), 'delete_karyawan', "Hapus pegawai: {$nama}");
        return redirect()->route('karyawan.index')->with('success', 'Pegawai berhasil dihapus');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:2048']);
        try {
            $import = new KaryawanImport();
            Excel::import($import, $request->file('file'));
            $count = $import->getImportedCount();
            $errors = $import->getErrors();
            if (!empty($errors)) {
                return redirect()->route('karyawan.index')->with('success', "Import selesai: {$count} berhasil, ".count($errors)." gagal")->with('import_errors', $errors);
            }
            return redirect()->route('karyawan.index')->with('success', "Import berhasil: {$count} data pegawai");
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Gagal import: '.$e->getMessage()]);
        }
    }

    public function export()
    {
        return Excel::download(new KaryawanExport, 'pegawai-'.date('Y-m-d').'.xlsx');
    }

    public function template()
    {
        return Excel::download(new PegawaiTemplateExport, 'template-pegawai.xlsx');
    }
}
