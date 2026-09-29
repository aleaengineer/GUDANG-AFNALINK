<?php
namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class BarangKeluarController extends Controller {
    public function index(){
        $keluars=BarangKeluar::with(['barang','operator','takenBy'])->latest()->paginate(20);
        // jumlah item per batch, untuk badge "1 pencatatan = N barang"
        $groups=BarangKeluar::whereNotNull('group_uuid')
            ->whereIn('group_uuid',$keluars->pluck('group_uuid')->filter())
            ->select('group_uuid')
            ->selectRaw('COUNT(*) as c')
            ->groupBy('group_uuid')
            ->pluck('c','group_uuid');
        return view('barang-keluar.index', compact('keluars','groups'));
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
            'items'=>'required|array|min:1|max:30',
            'items.*.barang_id'=>'required|integer|exists:barangs,id',
            'items.*.jumlah'=>'required|integer|min:1',
            'items.*.serial_number'=>'nullable|string|max:255',
            'items.*.keperluan'=>'nullable|string',
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

        // Kompatibilitas: form lama (satu barang) -> dibungkus jadi satu item
        $items=array_values(array_filter($data['items'], fn($i)=>!empty($i['barang_id'])));
        if($items===[]){
            return back()->withErrors(['items'=>'Pilih minimal 1 barang.'])->withInput();
        }

        // Jumlahkan per barang: barang sama di 2 baris tidak boleh melebihi stok
        $total=[];
        foreach($items as $it) $total[$it['barang_id']]=($total[$it['barang_id']]??0)+(int)$it['jumlah'];
        foreach($total as $barangId=>$qty){
            $b=Barang::find($barangId);
            if($qty > $b->stok){
                return back()->withErrors(['items'=>"Stok tidak cukup untuk \"{$b->nama}\"! Diminta {$qty}, sisa stok {$b->stok} {$b->satuan}."])->withInput();
            }
        }

        $nama=$isTeknisi?auth()->user()->name:$data['teknisi_nama'];
        $jabatan=$isTeknisi?auth()->user()->jabatan:$data['teknisi_jabatan'];
        $takenBy=$isTeknisi?auth()->id():null;
        $group=(string)Str::uuid();
        $totalUnit=array_sum($total);
        $ringkasan=[];

        DB::transaction(function() use ($items,$total,$nama,$jabatan,$takenBy,$group,&$ringkasan){
            foreach($items as $it){
                $barang=Barang::lockForUpdate()->find($it['barang_id']);
                // cek ulang di dalam transaksi (anti race condition)
                if((int)$it['jumlah'] > $barang->stok){
                    throw new \RuntimeException("Stok tidak cukup untuk \"{$barang->nama}\"! Sisa stok: {$barang->stok} {$barang->satuan}");
                }
                BarangKeluar::create([
                    'barang_id'=>$barang->id,
                    'jumlah'=>(int)$it['jumlah'],
                    'teknisi_nama'=>$nama,
                    'teknisi_jabatan'=>$jabatan,
                    'serial_number'=>$it['serial_number']??null,
                    'keperluan'=>$it['keperluan']??null,
                    'operator_id'=>auth()->id(),
                    'taken_by'=>$takenBy,
                    'group_uuid'=>$group,
                ]);
                $barang->decrement('stok',(int)$it['jumlah']);
                $ringkasan[]="{$barang->nama} {$it['jumlah']} {$barang->satuan}";
            }
        });

        AuditLog::record(auth()->id(),'barang_keluar',sprintf(
            'Pengambilan (%d jenis / %d unit): %s oleh %s (%s)',
            count($items),$totalUnit,implode(', ',$ringkasan),$nama,$jabatan
        ));

        $pesan=count($items)>1
            ? sprintf('Pengambilan %d barang (%d unit) berhasil dicatat, stok berkurang',count($items),$totalUnit)
            : 'Pengambilan berhasil dicatat, stok berkurang';
        return redirect()->route('barang-keluar.index')->with('success',$pesan);
    }
    public function destroy(BarangKeluar $barangKeluar){
        if(auth()->user()->isTeknisi()) abort(403, 'Akses ditolak');
        // Hapus seluruh isi pencatatan yang sama (bisa lebih dari 1 barang)
        $query=BarangKeluar::where('id',$barangKeluar->id);
        if($barangKeluar->group_uuid){
            $query->orWhere('group_uuid',$barangKeluar->group_uuid);
        }
        $rows=$query->get();
        $nama=[];$unit=0;
        DB::transaction(function() use ($rows,&$nama,&$unit){
            foreach($rows as $r){
                $barang=$r->barang;
                if($barang) $barang->increment('stok',$r->jumlah);
                $nama[]=$barang->nama.' +'.$r->jumlah;
                $unit+=$r->jumlah;
                $r->delete();
            }
        });
        AuditLog::record(auth()->id(),'delete_keluar',sprintf(
            'Hapus pengambilan (%d item / %d unit): %s',count($rows),$unit,implode(', ',$nama)
        ));
        return back()->with('success',count($rows)>1
            ? 'Pencatatan '.$unit.' unit ('.count($rows).' barang) dihapus, stok dikembalikan'
            : 'Data dihapus, stok dikembalikan');
    }
}
