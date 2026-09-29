<?php
namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\AuditLog;
use Illuminate\Http\Request;
class BarangKeluarController extends Controller {
    public function index(){
        $keluars=BarangKeluar::with(['barang','operator','takenBy'])->latest()->paginate(20);
        return view('barang-keluar.index', compact('keluars'));
    }
    public function create(Request $request){
        $barangs=Barang::where('stok','>',0)->orderBy('nama')->get();
        $jabatans=['Teknisi','NOC','Marketing','Kasir','CEO','CFO','CMO'];
        $isTeknisi=auth()->user()->isTeknisi();
        // Role teknisi mencatat miliknya sendiri, tidak perlu daftar personil
        $karyawans=collect();
        if(!$isTeknisi){
            $karyawans=\App\Models\Karyawan::aktif()->orderBy('nama')->get();
            if($request->jabatan) $karyawans=\App\Models\Karyawan::aktif()->where('jabatan',$request->jabatan)->orderBy('nama')->get();
        }
        return view('barang-keluar.create', compact('barangs','jabatans','karyawans','isTeknisi'));
    }
    public function store(Request $request){
        $isTeknisi=auth()->user()->isTeknisi();
        $rules=[
            'barang_id'=>'required|exists:barangs,id',
            'jumlah'=>'required|integer|min:1',
            'serial_number'=>'nullable|string|max:255',
            'keperluan'=>'nullable|string',
        ];
        // Teknisi: nama & jabatan dipaksa dari akun, tidak boleh input manual
        if($isTeknisi){
            $rules['teknisi_nama']='nullable|string|max:255';
            $rules['teknisi_jabatan']='nullable|string|max:255';
        } else {
            $rules['teknisi_nama']='required|string|max:255';
            $rules['teknisi_jabatan']='required|in:Teknisi,NOC,Marketing,Kasir,CEO,CFO,CMO,Finance';
        }
        $data=$request->validate($rules);
        $barang=Barang::find($data['barang_id']);
        if($data['jumlah'] > $barang->stok){
            return back()->withErrors(['jumlah'=>"Stok tidak cukup! Sisa stok: {$barang->stok} {$barang->satuan}"])->withInput();
        }
        $data['operator_id']=auth()->id();
        if($isTeknisi){
            $data['teknisi_nama']=auth()->user()->name;
            $data['teknisi_jabatan']=auth()->user()->jabatan;
            $data['taken_by']=auth()->id();
        } else {
            $data['taken_by']=null;
        }
        $keluar=BarangKeluar::create($data);
        $barang->decrement('stok',$data['jumlah']);
        AuditLog::record(auth()->id(),'barang_keluar',"Pengambilan: {$data['teknisi_nama']} ({$data['teknisi_jabatan']}) ambil {$barang->nama} {$data['jumlah']} {$barang->satuan}");
        return redirect()->route('barang-keluar.index')->with('success','Pengambilan berhasil dicatat, stok berkurang');
    }
    public function destroy(BarangKeluar $barangKeluar){
        if(auth()->user()->isTeknisi()) abort(403, 'Akses ditolak');
        $barang=$barangKeluar->barang;
        if($barang) $barang->increment('stok', $barangKeluar->jumlah);
        $barangKeluar->delete();
        AuditLog::record(auth()->id(),'delete_keluar',"Hapus pengambilan: {$barang->nama} +{$barangKeluar->jumlah}");
        return back()->with('success','Data dihapus, stok dikembalikan');
    }
}
