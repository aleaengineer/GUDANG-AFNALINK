@extends('layouts.app')
@section('title', $barang->nama.' - Gudang AFNALINK')
@section('page-title', $barang->nama)
@section('page-subtitle', $barang->kategori.' • '.$barang->merk)
@section('content')
<div class="max-w-3xl space-y-6">
    <div class="glass rounded-2xl p-6">
        <div class="flex flex-col lg:flex-row gap-6">
            @if($barang->foto)
                <img src="{{ asset('storage/'.$barang->foto) }}" class="w-full lg:w-64 h-64 rounded-2xl object-cover border border-white/10 flex-shrink-0">
            @else
                <div class="w-full lg:w-64 h-64 rounded-2xl bg-gradient-to-br from-cyan-500/10 to-indigo-600/10 border border-white/10 flex items-center justify-center text-5xl flex-shrink-0">📦</div>
            @endif
            <div class="flex-1 space-y-4">
                <div>
                    <h2 class="text-xl font-bold text-white">{{ $barang->nama }}</h2>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="text-xs px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-300">{{ $barang->kategori }}</span>
                        @if($barang->merk)<span class="text-xs px-3 py-1 rounded-full bg-white/[0.06] border border-white/[0.06] text-zinc-400">{{ $barang->merk }}</span>@endif
                        <span class="text-xs px-3 py-1 rounded-full {{ $barang->stok <= 5 ? 'bg-amber-500/10 border-amber-500/20 text-amber-300' : 'bg-emerald-500/10 border-emerald-500/20 text-emerald-300' }}">{{ $barang->stok }} {{ $barang->satuan }}</span>
                    </div>
                </div>
                @if($barang->serial_number)<div class="text-sm"><span class="text-zinc-500">SN:</span> <span class="font-mono text-white">{{ $barang->serial_number }}</span></div>@endif
                @if($barang->spesifikasi)<div class="text-sm text-zinc-400">{{ $barang->spesifikasi }}</div>@endif
                <div class="text-xs text-zinc-500">Ditambahkan {{ $barang->created_at->format('d M Y H:i') }} • Update {{ $barang->updated_at->diffForHumans() }}</div>
                <div class="flex gap-3 pt-2">
                    <a href="{{ route('barang.edit', $barang) }}" class="px-5 py-2.5 rounded-xl bg-white/[0.06] border border-white/[0.08] text-sm text-white">Edit</a>
                    <a href="{{ route('barang-masuk.create') }}?barang_id={{ $barang->id }}" class="px-5 py-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-sm text-emerald-300">+ Masuk</a>
                    <a href="{{ route('barang-keluar.create') }}?barang_id={{ $barang->id }}" class="px-5 py-2.5 rounded-xl bg-red-500/10 border border-red-500/20 text-sm text-red-300">- Keluar</a>
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('barang.index') }}" class="inline-flex items-center gap-2 text-sm text-zinc-400 hover:text-white">← Kembali ke Data Barang</a>
</div>
@endsection
