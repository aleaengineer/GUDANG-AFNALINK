@extends('layouts.app')
@section('title', 'Data Pegawai - Gudang AFNALINK')
@section('page-title', 'Data Pegawai')
@section('page-subtitle', 'Daftar nama & jabatan: Teknisi, NOC, Marketing, Kasir, CEO, CFO, CMO')
@section('content')
<div class="flex flex-col lg:flex-row gap-4 mb-6">
    <form method="GET" class="flex-1 flex gap-3">
        <div class="flex-1 relative">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pegawai..." class="w-full pl-10 pr-4 py-3 rounded-xl bg-white/[0.06] border border-white/[0.08] text-sm text-white placeholder:text-zinc-500 focus:border-cyan-500/50 focus:outline-none">
        </div>
        <select name="jabatan" onchange="this.form.submit()" class="px-4 py-3 rounded-xl bg-white/[0.06] border border-white/[0.08] text-sm text-white focus:outline-none">
            <option value="" class="bg-[#08080f]">Semua Jabatan</option>
            @foreach($jabatans as $j)
                <option value="{{ $j }}" class="bg-[#08080f]" {{ request('jabatan')==$j ? 'selected' : '' }}>{{ $j }}</option>
            @endforeach
        </select>
        <select name="status" onchange="this.form.submit()" class="px-4 py-3 rounded-xl bg-white/[0.06] border border-white/[0.08] text-sm text-white focus:outline-none">
            <option value="" class="bg-[#08080f]">Semua Status</option>
            <option value="aktif" class="bg-[#08080f]" {{ request('status')=='aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" class="bg-[#08080f]" {{ request('status')=='nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <button type="submit" class="px-6 py-3 rounded-xl bg-white/[0.06] border border-white/[0.08] text-sm text-white hover:bg-white/[0.08]">Cari</button>
    </form>
    <div class="flex gap-3">
        <a href="{{ route('karyawan.export') }}" class="px-4 py-3 rounded-xl glass text-sm text-white hover:bg-white/[0.08] whitespace-nowrap">📥 Export</a>
        <a href="{{ route('karyawan.create') }}" class="px-6 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 text-white text-sm font-medium whitespace-nowrap">+ Tambah Pegawai</a>
    </div>
</div>

<div class="flex flex-wrap gap-3 mb-6">
    <form method="POST" action="{{ route('karyawan.import') }}" enctype="multipart/form-data" class="flex gap-2 items-center glass rounded-xl px-4 py-3">
        @csrf
        <span class="text-xs text-zinc-400 whitespace-nowrap">Import Excel:</span>
        <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="text-sm text-zinc-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-cyan-500/20 file:text-cyan-300 file:text-sm">
        <button type="submit" class="px-4 py-1.5 rounded-lg bg-cyan-500/20 border border-cyan-500/30 text-sm text-cyan-300">📤 Import</button>
    </form>
    <a href="{{ route('karyawan.template') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl glass text-sm text-white hover:bg-white/[0.08]">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
        Download Template
    </a>
</div>

<!-- Ringkasan jabatan - compact -->
<div class="glass rounded-xl px-3 py-2.5 mb-6 flex flex-wrap items-center gap-2">
    <span class="text-xs font-medium text-zinc-500 mr-1">Total per Jabatan:</span>
    @foreach($jabatans as $j)
        <a href="{{ route('karyawan.index', ['jabatan' => $j]) }}" class="inline-flex items-center gap-2.5 px-5 py-1.5 rounded-full border text-xs font-medium hover:opacity-80 transition {{ request('jabatan')==$j ? 'bg-cyan-500/15 border-cyan-500/30 text-cyan-300' : 'bg-white/[0.04] border-white/[0.06] text-zinc-400 hover:text-zinc-300 hover:bg-white/[0.06]' }}">
            {{ $j }}
            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold {{ request('jabatan')==$j ? 'bg-cyan-500/20 text-cyan-200' : 'bg-white/[0.06] text-zinc-300' }}">{{ \App\Models\Karyawan::where('jabatan',$j)->count() }}</span>
        </a>
    @endforeach
    @if(request('jabatan'))
        <a href="{{ route('karyawan.index') }}" class="ml-auto text-xs text-zinc-500 hover:text-white">Reset ×</a>
    @endif
</div>

<div class="glass rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-white/[0.06] bg-white/[0.02]">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400 tracking-wider">NAMA</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400 tracking-wider">JABATAN</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400 tracking-wider hidden lg:table-cell">KONTAK</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400 tracking-wider">STATUS</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-zinc-400 tracking-wider">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/[0.04]">
                @forelse($karyawans as $k)
                <tr class="hover:bg-white/[0.03] transition">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500/20 to-indigo-600/20 border border-white/10 flex items-center justify-center text-sm font-bold text-white">{{ strtoupper(substr($k->nama,0,1)) }}{{ strtoupper(substr(explode(' ',$k->nama)[1]??'',0,1)) }}</div>
                            <div>
                                <div class="text-sm font-medium text-white">{{ $k->nama }}</div>
                                <div class="text-xs text-zinc-500 lg:hidden">{{ $k->no_hp ?? $k->email ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        @php
                            $colors = ['Teknisi'=>'bg-cyan-500/10 border-cyan-500/20 text-cyan-300','NOC'=>'bg-indigo-500/10 border-indigo-500/20 text-indigo-300','Marketing'=>'bg-pink-500/10 border-pink-500/20 text-pink-300','Kasir'=>'bg-amber-500/10 border-amber-500/20 text-amber-300','CEO'=>'bg-purple-500/10 border-purple-500/20 text-purple-300','CFO'=>'bg-emerald-500/10 border-emerald-500/20 text-emerald-300','CMO'=>'bg-orange-500/10 border-orange-500/20 text-orange-300','Finance'=>'bg-yellow-500/10 border-yellow-500/20 text-yellow-300'];
                        @endphp
                        <span class="text-xs px-2.5 py-1 rounded-full border {{ $colors[$k->jabatan] ?? 'bg-white/[0.06] border-white/[0.06] text-zinc-300' }}">{{ $k->jabatan }}</span>
                    </td>
                    <td class="px-5 py-4 hidden lg:table-cell">
                        <div class="text-sm text-zinc-300">{{ $k->no_hp ?? '-' }}</div>
                        <div class="text-xs text-zinc-500">{{ $k->email ?? '-' }}</div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-xs px-2.5 py-1 rounded-full border {{ $k->status=='aktif' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-300' : 'bg-red-500/10 border-red-500/20 text-red-300' }}">{{ ucfirst($k->status) }}</span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('karyawan.edit', $k) }}" class="p-2 rounded-lg hover:bg-white/[0.06] text-zinc-400 hover:text-white" title="Edit">✏️</a>
                            <form method="POST" action="{{ route('karyawan.destroy', $k) }}" onsubmit="return confirm('Hapus {{ $k->nama }}?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-zinc-400 hover:text-red-400" title="Hapus">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-16 text-center">
                    <div class="text-4xl mb-3">👥</div>
                    <div class="text-zinc-500 text-sm">Belum ada data pegawai</div>
                    <a href="{{ route('karyawan.create') }}" class="inline-block mt-3 text-sm text-cyan-400 hover:underline">+ Tambah pegawai pertama</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($karyawans->hasPages())
    <div class="px-5 py-4 border-t border-white/[0.06]">{{ $karyawans->links() }}</div>
    @endif
</div>
@endsection
