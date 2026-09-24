@extends('layouts.app')
@section('title', 'Data Barang - Gudang AFNALINK')
@section('page-title', 'Data Barang')
@section('page-subtitle', 'Kelola inventory gudang')
@section('content')
<div class="flex flex-col lg:flex-row gap-4 mb-6">
    <form method="GET" class="flex-1 flex gap-3">
        <div class="flex-1 relative">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, kategori, merk..." class="w-full pl-10 pr-4 py-3 rounded-xl bg-white/[0.06] border border-white/[0.08] text-sm text-white placeholder:text-zinc-500 focus:border-cyan-500/50 focus:outline-none">
        </div>
        <select name="kategori" onchange="this.form.submit()" class="px-4 py-3 rounded-xl bg-white/[0.06] border border-white/[0.08] text-sm text-white focus:outline-none">
            <option value="" class="bg-[#08080f]">Semua Kategori</option>
            @foreach($kategoris as $k)
                <option value="{{ $k }}" class="bg-[#08080f]" {{ request('kategori')==$k ? 'selected' : '' }}>{{ $k }}</option>
            @endforeach
        </select>
        <select name="sort" onchange="this.form.submit()" class="px-4 py-3 rounded-xl bg-white/[0.06] border border-white/[0.08] text-sm text-white focus:outline-none">
            <option value="" class="bg-[#08080f]">Urutkan: Terbaru</option>
            <option value="kategori_asc" class="bg-[#08080f]" {{ (request('sort')=='kategori_asc' || (request('sort')=='kategori' && request('dir')=='asc')) ? 'selected' : '' }}>Kategori A-Z</option>
            <option value="kategori_desc" class="bg-[#08080f]" {{ (request('sort')=='kategori_desc' || (request('sort')=='kategori' && request('dir')=='desc')) ? 'selected' : '' }}>Kategori Z-A</option>
            <option value="nama_asc" class="bg-[#08080f]" {{ (request('sort')=='nama_asc' || (request('sort')=='nama' && request('dir')=='asc')) ? 'selected' : '' }}>Nama A-Z</option>
            <option value="nama_desc" class="bg-[#08080f]" {{ (request('sort')=='nama_desc' || (request('sort')=='nama' && request('dir')=='desc')) ? 'selected' : '' }}>Nama Z-A</option>
            <option value="stok_asc" class="bg-[#08080f]" {{ (request('sort')=='stok_asc' || (request('sort')=='stok' && request('dir')=='asc')) ? 'selected' : '' }}>Stok Terendah</option>
            <option value="stok_desc" class="bg-[#08080f]" {{ (request('sort')=='stok_desc' || (request('sort')=='stok' && request('dir')=='desc')) ? 'selected' : '' }}>Stok Terbanyak</option>
            <option value="merk_asc" class="bg-[#08080f]" {{ (request('sort')=='merk_asc' || (request('sort')=='merk' && request('dir')=='asc')) ? 'selected' : '' }}>Merk A-Z</option>
            <option value="merk_desc" class="bg-[#08080f]" {{ (request('sort')=='merk_desc' || (request('sort')=='merk' && request('dir')=='desc')) ? 'selected' : '' }}>Merk Z-A</option>
        </select>
        <button type="submit" class="px-6 py-3 rounded-xl bg-white/[0.06] border border-white/[0.08] text-sm text-white hover:bg-white/[0.08]">Cari</button>
    </form>
    <div class="flex gap-3">
        <label class="flex items-center gap-2 px-4 py-3 rounded-xl glass text-sm cursor-pointer">
            <input type="checkbox" onchange="window.location.href='{{ request()->fullUrlWithQuery(['low_stock' => request()->has('low_stock') ? null : 1]) }}'" {{ request()->has('low_stock') ? 'checked' : '' }} class="rounded border-white/20 text-amber-500">
            <span class="text-amber-300 text-xs font-medium">Stok Menipis</span>
        </label>
        <a href="{{ route('barang.export') }}" class="px-4 py-3 rounded-xl glass text-sm text-white hover:bg-white/[0.08] whitespace-nowrap">📥 Export</a>
        <a href="{{ route('barang.create') }}" class="px-6 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 text-white text-sm font-medium whitespace-nowrap">+ Tambah</a>
    </div>
</div>

<!-- Import & Template -->
<div class="flex flex-wrap gap-3 mb-6">
    <form method="POST" action="{{ route('barang.import') }}" enctype="multipart/form-data" class="flex gap-2 items-center glass rounded-xl px-4 py-3">
        @csrf
        <span class="text-xs text-zinc-400 whitespace-nowrap">Import Excel:</span>
        <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="text-sm text-zinc-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-cyan-500/20 file:text-cyan-300 file:text-sm">
        <button type="submit" class="px-4 py-1.5 rounded-lg bg-cyan-500/20 border border-cyan-500/30 text-sm text-cyan-300">📤 Import</button>
    </form>
    <a href="{{ route('barang.template') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl glass text-sm text-white hover:bg-white/[0.08]">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
        Download Template
    </a>
</div>

@if(auth()->user()->role === 'admin' && $pendingCount > 0)
<div class="glass rounded-xl p-4 mb-6 border-amber-500/20 bg-amber-500/10 flex items-center gap-3">
    <div class="w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center">⚠️</div>
    <div class="flex-1">
        <div class="text-sm font-medium text-amber-300">{{ $pendingCount }} permintaan hapus Data Barang menunggu verifikasi</div>
        <div class="text-xs text-amber-200/70">Operator minta hapus, perlu persetujuan admin di halaman Verifikasi</div>
    </div>
    <a href="{{ route('verifikasi.index') }}" class="px-4 py-2 rounded-xl bg-amber-500/20 border border-amber-500/30 text-amber-300 text-xs">Verifikasi →</a>
</div>
@endif

<div class="glass rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-white/[0.06] bg-white/[0.02]">
                    <th class="text-left px-5 py-3 text-xs font-semibold tracking-wider">
                        @php $isNamaSort = request('sort')=='nama' || request('sort')=='nama_asc' || request('sort')=='nama_desc'; $nextNamaDir = (request('sort')=='nama_asc' || (request('sort')=='nama' && request('dir')=='asc')) ? 'nama_desc' : 'nama_asc'; @endphp
                        <a href="{{ request()->fullUrlWithQuery(['sort' => $nextNamaDir, 'page' => 1]) }}" class="inline-flex items-center gap-1 {{ $isNamaSort ? 'text-cyan-400' : 'text-zinc-400 hover:text-zinc-200' }}">
                            BARANG
                            @if($isNamaSort)
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="{{ (request('sort')=='nama_asc' || request('dir')=='asc') ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/></svg>
                            @else
                                <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            @endif
                        </a>
                    </th>
                    <th class="text-left px-5 py-3 text-xs font-semibold tracking-wider">
                        @php $isKategoriSort = request('sort')=='kategori' || request('sort')=='kategori_asc' || request('sort')=='kategori_desc'; $nextDir = (request('sort')=='kategori_asc' || (request('sort')=='kategori' && request('dir')=='asc')) ? 'kategori_desc' : 'kategori_asc'; @endphp
                        <a href="{{ request()->fullUrlWithQuery(['sort' => $nextDir, 'page' => 1]) }}" class="inline-flex items-center gap-1 {{ $isKategoriSort ? 'text-cyan-400' : 'text-zinc-400 hover:text-zinc-200' }}">
                            KATEGORI
                            @if($isKategoriSort)
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="{{ (request('sort')=='kategori_asc' || request('dir')=='asc') ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/></svg>
                            @else
                                <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            @endif
                        </a>
                    </th>
                    <th class="text-left px-5 py-3 text-xs font-semibold tracking-wider">
                        @php $isStokSort = request('sort')=='stok' || request('sort')=='stok_asc' || request('sort')=='stok_desc'; $nextStokDir = (request('sort')=='stok_desc' || (request('sort')=='stok' && request('dir')=='desc')) ? 'stok_asc' : 'stok_desc'; @endphp
                        <a href="{{ request()->fullUrlWithQuery(['sort' => $nextStokDir, 'page' => 1]) }}" class="inline-flex items-center gap-1 {{ $isStokSort ? 'text-cyan-400' : 'text-zinc-400 hover:text-zinc-200' }}">
                            STOK
                            @if($isStokSort)
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="{{ (request('sort')=='stok_asc' || request('dir')=='asc') ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/></svg>
                            @else
                                <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            @endif
                        </a>
                    </th>
                    <th class="text-left px-5 py-3 text-xs font-semibold tracking-wider hidden lg:table-cell">
                        @php $isMerkSort = request('sort')=='merk' || request('sort')=='merk_asc' || request('sort')=='merk_desc'; $nextMerkDir = (request('sort')=='merk_asc' || (request('sort')=='merk' && request('dir')=='asc')) ? 'merk_desc' : 'merk_asc'; @endphp
                        <a href="{{ request()->fullUrlWithQuery(['sort' => $nextMerkDir, 'page' => 1]) }}" class="inline-flex items-center gap-1 {{ $isMerkSort ? 'text-cyan-400' : 'text-zinc-400 hover:text-zinc-200' }}">
                            MERK
                            @if($isMerkSort)
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="{{ (request('sort')=='merk_asc' || request('dir')=='asc') ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/></svg>
                            @else
                                <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            @endif
                        </a>
                    </th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400 tracking-wider">STATUS</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-zinc-400 tracking-wider">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/[0.04]">
                @forelse($barangs as $b)
                <tr class="hover:bg-white/[0.03] transition {{ $b->isPending() ? 'bg-amber-500/[0.04]' : '' }}">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            @if($b->foto)
                                <img src="{{ asset('storage/'.$b->foto) }}" class="w-10 h-10 rounded-xl object-cover border border-white/10">
                            @else
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500/20 to-indigo-600/20 border border-white/10 flex items-center justify-center text-sm">📦</div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-white">{{ $b->nama }}</div>
                                <div class="text-xs text-zinc-500">{{ $b->serial_number ?? Str::limit($b->spesifikasi, 30) ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4"><span class="text-xs px-2.5 py-1 rounded-full bg-white/[0.06] border border-white/[0.06] text-zinc-300">{{ $b->kategori }}</span></td>
                    <td class="px-5 py-4">
                        <span class="text-sm font-bold {{ $b->stok <= 0 ? 'text-red-400' : ($b->stok <= 5 ? 'text-amber-400' : 'text-emerald-400') }}">{{ $b->stok }} {{ $b->satuan }}</span>
                        @if($b->stok <= 5)<div class="text-[10px] {{ $b->stok == 0 ? 'text-red-400' : 'text-amber-400' }}">{{ $b->stok == 0 ? 'Habis!' : 'Menipis' }}</div>@endif
                    </td>
                    <td class="px-5 py-4 hidden lg:table-cell text-sm text-zinc-400">{{ $b->merk ?? '-' }}</td>
                    <td class="px-5 py-4">
                        @if($b->isPending())
                            <span class="text-xs px-2.5 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-300">Menunggu Verifikasi</span>
                            <div class="text-[11px] text-zinc-500 mt-1">Oleh {{ $b->deletionRequester->name ?? '-' }}</div>
                        @else
                            <span class="text-xs px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-300">Aktif</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end gap-1">
                            @if($b->isPending())
                                @if(auth()->user()->role === 'admin')
                                    <form method="POST" action="{{ route('barang.verify', $b) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-xs">✓</button>
                                    </form>
                                    <form method="POST" action="{{ route('barang.verify', $b) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-red-500/10 border border-red-500/20 text-red-300 text-xs">✕</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('barang.cancel', $b) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs px-2.5 py-1.5 rounded-lg glass text-zinc-400">Batalkan</button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('barang.show', $b) }}" class="p-2 rounded-lg hover:bg-white/[0.06] text-zinc-400 hover:text-white" title="Lihat">👁️</a>
                                <a href="{{ route('barang.edit', $b) }}" class="p-2 rounded-lg hover:bg-white/[0.06] text-zinc-400 hover:text-white" title="Edit">✏️</a>
                                <form method="POST" action="{{ route('barang.destroy', $b) }}" onsubmit="return confirm('{{ auth()->user()->role === 'admin' ? 'Hapus barang ini?' : 'Minta hapus ke admin untuk verifikasi?' }}')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-zinc-400 hover:text-red-400" title="{{ auth()->user()->role === 'admin' ? 'Hapus' : 'Minta hapus' }}">🗑️</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-16 text-center">
                    <div class="text-4xl mb-3">📦</div>
                    <div class="text-zinc-500 text-sm">Belum ada barang</div>
                    <a href="{{ route('barang.create') }}" class="inline-block mt-3 text-sm text-cyan-400 hover:underline">+ Tambah barang pertama</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($barangs->hasPages())
    <div class="px-5 py-4 border-t border-white/[0.06]">{{ $barangs->links() }}</div>
    @endif
</div>
@endsection
