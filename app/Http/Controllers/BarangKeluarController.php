<?php
namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\AuditLog;
use Illuminate\Http\Request;
class BarangKeluarController extends Controller {
    public function index(){
        $keluars=BarangKeluar::with(['barang','operator'])->latest()->paginate(20);
        return view('barang-keluar.index', compact('keluars'));
    }
    public function create(Request $request){
        $barangs=Barang::where('stok','>',0)->orderBy('nama')->get();
        $jabatans=['Teknisi','NOC','Marketing','Kasir','CEO','CFO','CMO'];
        // List data personil untuk dropdown - filter by jabatan jika ada query
        $karyawans=\App\Models\Karyawan::aktif()->orderBy('nama')->get();
        if($request->jabatan) $karyawans=\App\Models\Karyawan::aktif()->where('jabatan',$request->jabatan)->orderBy('nama')->get();
        return view('barang-keluar.create', compact('barangs','jabatans','karyawans'));
    }
    public function store(Request $request){
        $data=$request->validate([
            'barang_id'=>'required|exists:barangs,id',
            'jumlah'=>'required|integer|min:1',
            'teknisi_nama'=>'required|string|max:255',
            'teknisi_jabatan'=>'required|in:Teknisi,NOC,Marketing,Kasir,CEO,CFO,CMO,Finance',
            'serial_number'=>'nullable|string|max:255',
            'keperluan'=>'nullable|string',
        ]);
        $barang=Barang::find($data['barang_id']);
        if($data['jumlah'] > $barang->stok){
            return back()->withErrors(['jumlah'=>"Stok tidak cukup! Sisa stok: {$barang->stok} {$barang->satuan}"])->withInput();
        }
        $data['operator_id']=auth()->id();
        $keluar=BarangKeluar::create($data);
        $barang->decrement('stok',$data['jumlah']);
        AuditLog::record(auth()->id(),'barang_keluar',"Pengambilan: {$data['teknisi_nama']} ({$data['teknisi_jabatan']}) ambil {$barang->nama} {$data['jumlah']} {$barang->satuan}");
        return redirect()->route('barang-keluar.index')->with('success','Pengambilan berhasil dicatat, stok berkurang');
    }
    public function destroy(BarangKeluar $barangKeluar){
        $barang=$barangKeluar->barang;
        if($barang) $barang->increment('stok', $barangKeluar->jumlah);
        $barangKeluar->delete();
        AuditLog::record(auth()->id(),'delete_keluar',"Hapus pengambilan: {$barang->nama} +{$barangKeluar->jumlah}");
        return back()->with('success','Data dihapus, stok dikembalikan');
    }
}
