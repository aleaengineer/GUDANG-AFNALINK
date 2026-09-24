<?php
namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;
class LaporanController extends Controller {
    public function index(Request $request){
        $from=$request->from ? $request->from.' 00:00:00' : null;
        $to=$request->to ? $request->to.' 23:59:59' : null;
        $bulan=$request->bulan; // format YYYY-MM
        $query=AuditLog::with('user')->latest();
        if($bulan){
            $year = substr($bulan, 0, 4);
            $month = substr($bulan, 5, 2);
            $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
        } else {
            if($from) $query->where('created_at','>=',$from);
            if($to) $query->where('created_at','<=',$to);
        }
        if($request->aksi) $query->where('aksi',$request->aksi);
        // Operator hanya lihat log barang masuk, barang keluar, tambah & hapus barang
        if(auth()->user()->role === 'operator'){
            $allowed = ['barang_masuk','barang_keluar','create_barang','delete_barang','create_barang_custom'];
            $query->whereIn('aksi', $allowed);
        }
        $logs=$query->paginate(10)->withQueryString();
        $masuksQuery=BarangMasuk::with(['barang','operator'])->latest();
        $keluarsQuery=BarangKeluar::with(['barang','operator'])->latest();
        if($bulan){
            $year = substr($bulan, 0, 4);
            $month = substr($bulan, 5, 2);
            $masuksQuery->whereYear('created_at', $year)->whereMonth('created_at', $month);
            $keluarsQuery->whereYear('created_at', $year)->whereMonth('created_at', $month);
        } else {
            if($from) $masuksQuery->where('created_at','>=',$from);
            if($to) $masuksQuery->where('created_at','<=',$to);
            if($from) $keluarsQuery->where('created_at','>=',$from);
            if($to) $keluarsQuery->where('created_at','<=',$to);
        }
        $masuks=$masuksQuery->get();
        $keluars=$keluarsQuery->get();
        return view('laporan.index', compact('logs','masuks','keluars'));
    }
    public function export(Request $request){
        return Excel::download(new LaporanExport($request->from,$request->to,$request->bulan), 'laporan-gudang-'.($request->bulan ?? date('Y-m-d')).'.xlsx');
    }
}
