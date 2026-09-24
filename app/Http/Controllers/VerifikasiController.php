<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $pendings = Barang::with('deletionRequester')
            ->where('deletion_status','pending')
            ->latest('deletion_requested_at')
            ->paginate(20);
        $totalPending = Barang::where('deletion_status','pending')->count();
        return view('verifikasi.index', compact('pendings','totalPending'));
    }
}
