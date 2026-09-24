<?php
namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BarangMasukImport;
use App\Exports\BarangMasukExport;
class BarangMasukController extends Controller {
    public function index(){
        $masuks=BarangMasuk::with(['barang','operator'])->latest()->paginate(20);
        return view('barang-masuk.index', compact('masuks'));
    }
    public function create(){
        $barangs=Barang::orderBy('nama')->get();
        return view('barang-masuk.create', compact('barangs'));
    }
    public function store(Request $request){
        // Custom barang diluar list
        if($request->input('barang_id') === 'custom'){
            $request->validate([
                'custom_nama'=>'required|string|max:255',
                'custom_kategori'=>'nullable|string|max:100',
                'custom_satuan'=>'nullable|string|max:50',
                'jumlah'=>'required|integer|min:1',
                'sumber'=>'nullable|string|max:255',
                'catatan'=>'nullable|string',
            ]);
            $nama = trim($request->custom_nama);
            $kategori = trim($request->custom_kategori) ?: 'Lainnya';
            $satuan = trim($request->custom_satuan) ?: 'pcs';
            // Cek jika nama sudah ada, pakai yang ada; jika tidak buat baru
            $barang = Barang::where('nama', $nama)->first();
            if(!$barang){
                $barang = Barang::create([
                    'nama' => $nama,
                    'kategori' => $kategori,
                    'stok' => 0,
                    'satuan' => $satuan,
                ]);
                AuditLog::record(auth()->id(),'create_barang_custom',"Buat barang baru via Barang Masuk: {$barang->nama} ({$kategori})");
            }
            $data = [
                'barang_id' => $barang->id,
                'jumlah' => $request->jumlah,
                'sumber' => $request->sumber,
                'catatan' => $request->catatan,
                'operator_id' => auth()->id(),
            ];
            $masuk = BarangMasuk::create($data);
            $barang->increment('stok', $data['jumlah']);
            AuditLog::record(auth()->id(),'barang_masuk',"Barang masuk (custom): {$barang->nama} +{$data['jumlah']} {$barang->satuan}");
            return redirect()->route('barang-masuk.index')->with('success','Barang baru dibuat & stok masuk berhasil dicatat');
        }

        $data=$request->validate([
            'barang_id'=>'required|exists:barangs,id',
            'jumlah'=>'required|integer|min:1',
            'sumber'=>'nullable|string|max:255',
            'catatan'=>'nullable|string',
        ]);
        $data['operator_id']=auth()->id();
        $masuk=BarangMasuk::create($data);
        $barang=Barang::find($data['barang_id']);
        $barang->increment('stok',$data['jumlah']);
        AuditLog::record(auth()->id(),'barang_masuk',"Barang masuk: {$barang->nama} +{$data['jumlah']} {$barang->satuan}");
        return redirect()->route('barang-masuk.index')->with('success','Barang masuk berhasil dicatat, stok bertambah');
    }
    public function import(Request $request){
        $request->validate(['file'=>'required|file|mimes:xlsx,xls,csv|max:2048']);
        try{
            $import=new BarangMasukImport(auth()->id());
            Excel::import($import, $request->file('file'));
            $count=$import->getImportedCount();
            $errors=$import->getErrors();
            if(!empty($errors)){
                return redirect()->route('barang-masuk.index')->with('success',"Import selesai: {$count} berhasil, ".count($errors)." gagal")->with('import_errors',$errors);
            }
            return redirect()->route('barang-masuk.index')->with('success',"Import berhasil: {$count} data");
        }catch(\Exception $e){
            return back()->withErrors(['file'=>'Gagal import: '.$e->getMessage()]);
        }
    }
    public function export(){
        return Excel::download(new BarangMasukExport, 'barang-masuk-'.date('Y-m-d').'.xlsx');
    }
    public function destroy(BarangMasuk $barangMasuk){
        $barang=$barangMasuk->barang;
        if($barang) $barang->decrement('stok', $barangMasuk->jumlah);
        $barangMasuk->delete();
        AuditLog::record(auth()->id(),'delete_masuk',"Hapus barang masuk: {$barang->nama} -{$barangMasuk->jumlah}");
        return back()->with('success','Data dihapus, stok dikurangi');
    }
}
