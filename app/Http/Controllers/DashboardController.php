<?php
namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
class DashboardController extends Controller {
    public function index(){
        if(auth()->user()->isTeknisi()){
            $totalJenis=Barang::count();
            $totalStok=Barang::sum('stok');
            $barangs=Barang::where('stok','>',0)->orderBy('nama')->get();
            $totalAmbilSaya=BarangKeluar::where('taken_by',auth()->id())->count();
            $riwayatSaya=BarangKeluar::with(['barang'])->where('taken_by',auth()->id())->latest()->take(10)->get();
            return view('dashboard.teknisi', compact('totalJenis','totalStok','barangs','totalAmbilSaya','riwayatSaya'));
        }
        $totalBarang = Barang::count();
        $totalStok = Barang::sum('stok');
        $lowStock = Barang::where('stok','<=',5)->count();
        $masukHariIni = BarangMasuk::whereDate('created_at', today())->count();
        $keluarHariIni = BarangKeluar::whereDate('created_at', today())->count();
        $recentMasuk = BarangMasuk::with(['barang','operator'])->latest()->take(5)->get();
        $recentKeluar = BarangKeluar::with(['barang','operator'])->latest()->take(5)->get();
        $lowStockItems = Barang::where('stok','<=',5)->orderBy('stok')->take(5)->get();
        $pendingBarang = Barang::where('deletion_status','pending')->count();
        return view('dashboard.index', compact('totalBarang','totalStok','lowStock','masukHariIni','keluarHariIni','recentMasuk','recentKeluar','lowStockItems','pendingBarang'));
    }
}
