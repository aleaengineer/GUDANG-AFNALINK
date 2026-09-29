@extends('layouts.app')
@section('title', 'Dashboard - Gudang AFNALINK')
@section('page-title')
Halo, {{ auth()->user()->name }}
@endsection
@section('page-subtitle', 'Pilih barang yang akan kamu ambil dari gudang')
@section('content')

<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="glass rounded-2xl p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center">📦</div>
            <span class="text-xs px-2 py-1 rounded-full bg-white/[0.06] text-zinc-400">Tersedia</span>
        </div>
        <div class="text-2xl font-bold text-white">{{ $totalJenis }}</div>
        <div class="text-xs text-zinc-500">Jenis Barang</div>
    </div>
    <div class="glass rounded-2xl p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">📊</div>
            <span class="text-xs px-2 py-1 rounded-full bg-white/[0.06] text-zinc-400">Gudang</span>
        </div>
        <div class="text-2xl font-bold text-white">{{ number_format($totalStok) }}</div>
        <div class="text-xs text-zinc-500">Total Unit</div>
    </div>
</div>

<a href="{{ route('barang-keluar.create') }}" class="block w-full py-4 rounded-2xl bg-gradient-to-r from-red-500 to-orange-600 text-white text-sm font-semibold shadow-lg shadow-red-500/20 mb-6 flex items-center justify-center gap-2">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Catat Pengambilan Barang
</a>

<div class="glass rounded-2xl p-5 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-white">Stok Tersedia</h3>
        <span class="text-xs bg-white/[0.06] text-zinc-400 px-2 py-1 rounded-full">{{ $barangs->count() }} item</span>
    </div>
    <div class="space-y-2">
        @forelse($barangs as $b)
        <a href="{{ route('barang-keluar.create') }}?barang_id={{ $b->id }}" class="flex items-center justify-between bg-[#08080f] rounded-xl px-4 py-3 border border-white/[0.06] hover:border-cyan-500/30 transition">
            <div class="min-w-0">
                <div class="text-sm font-medium text-white truncate">{{ $b->nama }}</div>
                <div class="text-xs text-zinc-500">{{ $b->kategori }} @if($b->merk) • {{ $b->merk }} @endif</div>
            </div>
            <div class="text-right shrink-0 ml-3">
                <div class="text-sm font-bold {{ $b->stok <= 5 ? 'text-amber-400' : 'text-emerald-400' }}">{{ $b->stok }} {{ $b->satuan }}</div>
                <div class="text-[10px] text-cyan-400">Ambil →</div>
            </div>
        </a>
        @empty
        <div class="text-center py-8 text-zinc-500 text-sm">Stok gudang sedang kosong</div>
        @endforelse
    </div>
</div>

<div class="glass rounded-2xl p-5">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-white">Riwayat Pengambilan Saya</h3>
        <div class="flex items-center gap-2">
            <span class="text-xs bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 px-2 py-1 rounded-full">{{ $totalAmbilSaya }} kali</span>
            <a href="{{ route('barang-keluar.index') }}" class="text-xs text-cyan-400 hover:underline">Semua →</a>
        </div>
    </div>
    <div class="space-y-3">
        @forelse($riwayatSaya as $k)
        <div class="flex items-center gap-3 bg-[#08080f] rounded-xl px-3 py-3 border border-white/[0.06]">
            <div class="w-8 h-8 rounded-lg bg-red-500/10 border border-red-500/20 flex items-center justify-center text-[10px] text-red-400 shrink-0">OUT</div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-white truncate">{{ $k->barang->nama ?? '-' }} <span class="font-normal text-red-400">×{{ $k->jumlah }}</span></div>
                <div class="text-xs text-zinc-500">{{ $k->created_at->format('d/m/Y H:i') }} @if($k->keperluan) • {{ $k->keperluan }} @endif</div>
            </div>
        </div>
        @empty
        <div class="text-center py-8">
            <div class="text-3xl mb-2">📤</div>
            <div class="text-zinc-500 text-sm">Kamu belum pernah mengambil barang</div>
        </div>
        @endforelse
    </div>
</div>

@endsection
