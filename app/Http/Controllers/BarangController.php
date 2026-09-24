<?php
namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BarangImport;
use App\Exports\BarangTemplateExport;
class BarangController extends Controller {
    public function index(Request $request){
        $query = Barang::query()->with('deletionRequester');
        if($s=$request->search) $query->search($s);
        if($k=$request->kategori) $query->where('kategori',$k);
        if($request->has('low_stock')) $query->where('stok','<=',5);
        // Sort: ?sort=kategori_asc|kategori_desc|nama_asc|stok_desc atau ?sort=kategori&dir=asc
        $sortParam = $request->input('sort');
        $sort = $sortParam;
        $dir = $request->input('dir') === 'asc' ? 'asc' : 'desc';
        // Handle combined value like kategori_asc
        if($sortParam && str_contains($sortParam, '_')){
            [$s, $d] = explode('_', $sortParam, 2);
            $sort = $s;
            $dir = $d === 'asc' ? 'asc' : 'desc';
        }
        if($sort === 'kategori'){
            $query->orderBy('kategori', $dir)->orderBy('nama','asc');
        } elseif($sort === 'nama'){
            $query->orderBy('nama', $dir);
        } elseif($sort === 'stok'){
            $query->orderBy('stok', $dir);
        } elseif($sort === 'merk'){
            $query->orderBy('merk', $dir)->orderBy('nama','asc');
        } else {
            $query->orderBy('created_at','desc');
        }
        $barangs = $query->paginate(20)->withQueryString();
        $kategoris = Barang::distinct()->pluck('kategori');
        $pendingCount = Barang::pending()->count();
        return view('barang.index', compact('barangs','kategoris','pendingCount'));
    }
    public function create(){
        $default=['Router','ONT','OLT','Kabel FO','Splitter','Patchcord','Switch','Access Point','Lainnya'];
        $existing=Barang::distinct()->pluck('kategori')->toArray();
        $kategoris=array_values(array_unique(array_merge($default, $existing)));
        return view('barang.create', compact('kategoris'));
    }
    public function store(Request $request){
        $data=$request->validate([
            'nama'=>'required|string|max:255',
            'kategori'=>'required|string|max:100',
            'merk'=>'nullable|string|max:100',
            'spesifikasi'=>'nullable|string',
            'serial_number'=>'nullable|string|max:255',
            'stok'=>'required|integer|min:0',
            'satuan'=>'required|string|max:50',
            'foto'=>'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        if($request->hasFile('foto')) $data['foto']=$request->file('foto')->store('barang','public');
        $barang=Barang::create($data);
        AuditLog::record(auth()->id(),'create_barang',"Tambah barang: {$barang->nama} stok {$barang->stok}");
        return redirect()->route('barang.index')->with('success','Barang berhasil ditambahkan');
    }
    public function show(Barang $barang){ return view('barang.show', compact('barang')); }
    public function edit(Barang $barang){
        $default=['Router','ONT','OLT','Kabel FO','Splitter','Patchcord','Switch','Access Point','Lainnya'];
        $existing=Barang::distinct()->pluck('kategori')->toArray();
        $kategoris=array_values(array_unique(array_merge($default, $existing)));
        return view('barang.edit', compact('barang','kategoris'));
    }
    public function update(Request $request, Barang $barang){
        $data=$request->validate([
            'nama'=>'required|string|max:255',
            'kategori'=>'required|string|max:100',
            'merk'=>'nullable|string|max:100',
            'spesifikasi'=>'nullable|string',
            'serial_number'=>'nullable|string|max:255',
            'stok'=>'required|integer|min:0',
            'satuan'=>'required|string|max:50',
            'foto'=>'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        if($request->hasFile('foto')){
            if($barang->foto) Storage::disk('public')->delete($barang->foto);
            $data['foto']=$request->file('foto')->store('barang','public');
        }
        $barang->update($data);
        AuditLog::record(auth()->id(),'update_barang',"Update barang: {$barang->nama}");
        return redirect()->route('barang.index')->with('success','Barang berhasil diupdate');
    }
    public function destroy(Request $request, Barang $barang){
        if($barang->isPending()){
            return back()->withErrors(['error'=>'Data ini sudah menunggu verifikasi admin']);
        }
        if(auth()->user()->role==='admin'){
            if($barang->foto) Storage::disk('public')->delete($barang->foto);
            $nama=$barang->nama; $barang->delete();
            AuditLog::record(auth()->id(),'delete_barang',"Admin hapus barang: {$nama}");
            return redirect()->route('barang.index')->with('success','Barang berhasil dihapus');
        }
        $barang->update([
            'deletion_status'=>'pending',
            'deletion_requested_by'=>auth()->id(),
            'deletion_requested_at'=>now(),
        ]);
        AuditLog::record(auth()->id(),'request_delete_barang',"Operator minta hapus barang: {$barang->nama} (menunggu verifikasi)");
        return back()->with('success','Permintaan hapus dikirim ke admin untuk verifikasi');
    }

    public function verify(Request $request, Barang $barang){
        if(auth()->user()->role!=='admin') abort(403);
        $action=$request->input('action');
        if(!$barang->isPending()){
            return back()->withErrors(['error'=>'Data tidak dalam status pending']);
        }
        if($action==='approve'){
            if($barang->foto) Storage::disk('public')->delete($barang->foto);
            $nama=$barang->nama;
            AuditLog::record(auth()->id(),'approve_delete_barang',"Admin setujui hapus barang: {$nama} (diminta {$barang->deletionRequester->name})");
            $barang->delete();
            return back()->with('success','Penghapusan disetujui, barang dihapus');
        } else {
            $barang->update(['deletion_status'=>'none','deletion_requested_by'=>null,'deletion_requested_at'=>null]);
            AuditLog::record(auth()->id(),'reject_delete_barang',"Admin tolak hapus barang: {$barang->nama}");
            return back()->with('success','Penghapusan ditolak');
        }
    }

    public function cancelRequest(Barang $barang){
        if($barang->deletion_requested_by !== auth()->id() && auth()->user()->role !== 'admin'){
            abort(403);
        }
        $barang->update(['deletion_status'=>'none','deletion_requested_by'=>null,'deletion_requested_at'=>null]);
        return back()->with('success','Permintaan hapus dibatalkan');
    }

    public function import(Request $request){
        $request->validate(['file'=>'required|file|mimes:xlsx,xls,csv|max:2048']);
        try{
            $import=new BarangImport();
            Excel::import($import, $request->file('file'));
            $count=$import->getImportedCount();
            $errors=$import->getErrors();
            if(!empty($errors)){
                return redirect()->route('barang.index')->with('success',"Import selesai: {$count} berhasil, ".count($errors)." gagal")->with('import_errors',$errors);
            }
            return redirect()->route('barang.index')->with('success',"Import berhasil: {$count} data barang");
        }catch(\Exception $e){
            return back()->withErrors(['file'=>'Gagal import: '.$e->getMessage()]);
        }
    }

    public function template(){
        return Excel::download(new BarangTemplateExport, 'template-barang.xlsx');
    }
}
