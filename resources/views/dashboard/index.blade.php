@extends('layouts.app')
@section('title', 'Dashboard - Gudang AFNALINK')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan stok gudang')
@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="glass rounded-2xl p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center">📦</div>
            <span class="text-xs px-2 py-1 rounded-full bg-white/[0.06] text-zinc-400">Total</span>
        </div>
        <div class="text-2xl font-bold text-white">{{ $totalBarang }}</div>
        <div class="text-xs text-zinc-500">Jenis Barang</div>
    </div>
    <div class="glass rounded-2xl p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">📊</div>
            <span class="text-xs px-2 py-1 rounded-full bg-white/[0.06] text-zinc-400">Stok</span>
        </div>
        <div class="text-2xl font-bold text-white">{{ number_format($totalStok) }}</div>
        <div class="text-xs text-zinc-500">Total Unit</div>
    </div>
    <div class="glass rounded-2xl p-5 {{ $lowStock > 0 ? 'border-amber-500/20 bg-amber-500/[0.03]' : '' }}">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl {{ $lowStock > 0 ? 'bg-amber-500/10 border-amber-500/20' : 'bg-emerald-500/10 border-emerald-500/20' }} border flex items-center justify-center">{{ $lowStock > 0 ? '⚠️' : '✓' }}</div>
            <span class="text-xs px-2 py-1 rounded-full {{ $lowStock > 0 ? 'bg-amber-500/20 text-amber-300' : 'bg-emerald-500/20 text-emerald-300' }}">{{ $lowStock > 0 ? 'Waspada' : 'Aman' }}</span>
        </div>
        <div class="text-2xl font-bold {{ $lowStock > 0 ? 'text-amber-400' : 'text-white' }}">{{ $lowStock }}</div>
        <div class="text-xs text-zinc-500">Stok Menipis (≤5)</div>
    </div>
    <div class="glass rounded-2xl p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">🔄</div>
            <span class="text-xs px-2 py-1 rounded-full bg-white/[0.06] text-zinc-400">Hari ini</span>
        </div>
        <div class="flex gap-4">
            <div><div class="text-lg font-bold text-emerald-400">+{{ $masukHariIni }}</div><div class="text-[10px] text-zinc-500">Masuk</div></div>
            <div><div class="text-lg font-bold text-red-400">-{{ $keluarHariIni }}</div><div class="text-[10px] text-zinc-500">Keluar</div></div>
        </div>
    </div>
</div>

@if(auth()->user()->role === 'admin' && $pendingBarang > 0)
<div class="glass rounded-2xl border-amber-500/20 bg-amber-500/10 p-5 mb-6 flex items-center gap-4">
    <div class="w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center">⚠️</div>
    <div class="flex-1">
        <div class="text-sm font-semibold text-amber-300">{{ $pendingBarang }} permintaan hapus Data Barang menunggu verifikasi</div>
        <div class="text-xs text-amber-200/70">Operator minta hapus, silakan cek di Verifikasi</div>
    </div>
    <a href="{{ route('verifikasi.index') }}" class="px-4 py-2 rounded-xl bg-amber-500/20 border border-amber-500/30 text-amber-300 text-xs">Verifikasi →</a>
</div>
@endif

@if($lowStockItems->count() > 0)
<div class="glass rounded-2xl border-amber-500/20 bg-amber-500/[0.03] p-5 mb-6">
    <div class="flex items-center gap-2 mb-3">
        <span class="text-amber-400">⚠️</span>
        <h3 class="font-semibold text-amber-300 text-sm">Stok Menipis — Perlu Restock</h3>
        <span class="ml-auto text-xs bg-amber-500/20 text-amber-300 px-2 py-1 rounded-full">{{ $lowStockItems->count() }} item</span>
    </div>
    <div class="grid gap-2">
        @foreach($lowStockItems as $item)
        <div class="flex items-center justify-between bg-[#08080f] rounded-xl px-4 py-3 border border-white/[0.06]">
            <div>
                <div class="text-sm font-medium text-white">{{ $item->nama }}</div>
                <div class="text-xs text-zinc-500">{{ $item->kategori }} @if($item->merk) • {{ $item->merk }} @endif</div>
            </div>
            <div class="text-right">
                <div class="text-sm font-bold {{ $item->stok == 0 ? 'text-red-400' : 'text-amber-400' }}">{{ $item->stok }} {{ $item->satuan }}</div>
                <a href="{{ route('barang-masuk.create') }}?barang_id={{ $item->id }}" class="text-[10px] text-cyan-400 hover:underline">+ Tambah stok</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="grid lg:grid-cols-2 gap-6">
    <div class="glass rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-white">Barang Masuk Terbaru</h3>
            <a href="{{ route('barang-masuk.index') }}" class="text-xs text-cyan-400 hover:underline">Lihat semua →</a>
        </div>
        <div class="space-y-3">
            @forelse($recentMasuk as $m)
            <div class="flex items-center gap-3 bg-[#08080f] rounded-xl px-3 py-3 border border-white/[0.06]">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-xs">IN</div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-white truncate">{{ $m->barang->nama ?? '-' }}</div>
                    <div class="text-xs text-zinc-500">+{{ $m->jumlah }} • {{ $m->operator->name ?? '-' }} • {{ $m->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-zinc-500 text-sm">Belum ada data</div>
            @endforelse
        </div>
    </div>
    <div class="glass rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-white">Pengambilan Terbaru</h3>
            <a href="{{ route('barang-keluar.index') }}" class="text-xs text-cyan-400 hover:underline">Lihat semua →</a>
        </div>
        <div class="space-y-3">
            @forelse($recentKeluar as $k)
            <div class="flex items-center gap-3 bg-[#08080f] rounded-xl px-3 py-3 border border-white/[0.06]">
                <div class="w-8 h-8 rounded-lg bg-red-500/10 border border-red-500/20 flex items-center justify-center text-xs">OUT</div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-white truncate">{{ $k->barang->nama ?? '-' }} <span class="font-normal text-zinc-400">×{{ $k->jumlah }}</span></div>
                    <div class="text-xs text-zinc-500">{{ $k->teknisi_nama }} ({{ $k->teknisi_jabatan }}) • {{ $k->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-zinc-500 text-sm">Belum ada data</div>
            @endforelse
        </div>
    </div>
</div>

<div class="mt-6 flex flex-wrap gap-3">
    <a href="{{ route('barang.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 text-white text-sm font-medium shadow-lg shadow-cyan-500/20">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Barang
    </a>
    <a href="{{ route('barang-keluar.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl glass text-white text-sm font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        Catat Pengambilan
    </a>
    <a href="{{ route('barang-masuk.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl glass text-white text-sm font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Barang Masuk
    </a>
</div>
@endsection
