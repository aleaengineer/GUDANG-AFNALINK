@extends('layouts.app')
@section('title', 'Barang Keluar - Gudang AFNALINK')
@section('page-title', 'Barang Keluar')
@section('page-subtitle', 'Pengambilan oleh teknisi')
@section('content')
<div class="flex flex-wrap gap-3 mb-6">
    <a href="{{ route('barang-keluar.create') }}" class="px-6 py-3 rounded-xl bg-gradient-to-r from-red-500 to-orange-600 text-white text-sm font-medium">+ Catat Pengambilan</a>
    @if(!auth()->user()->isTeknisi())
    <a href="{{ route('laporan.export') }}" class="px-5 py-3 rounded-xl glass text-sm text-white">📥 Export Laporan</a>
    @endif
</div>
<div class="glass rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="border-b border-white/[0.06] bg-white/[0.02]"><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">TANGGAL</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">BARANG</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">JUMLAH</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">TEKNISI</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400 hidden lg:table-cell">KEPERLUAN</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">OPERATOR</th><th class="text-right px-5 py-3 text-xs font-semibold text-zinc-400">AKSI</th></tr></thead>
            <tbody class="divide-y divide-white/[0.04]">
                @forelse($keluars as $k)
                @php
                    $batch = $k->group_uuid ? (int)($groups[$k->group_uuid] ?? 1) : 1;
                @endphp
                <tr class="hover:bg-white/[0.03] {{ $batch > 1 ? 'bg-red-500/[0.03]' : '' }}">
                    <td class="px-3 sm:px-5 py-4 text-sm text-zinc-300 whitespace-nowrap">{{ $k->created_at->format('d/m/Y H:i') }}
                        @if($batch > 1)
                        <div class="mt-1 inline-flex items-center gap-1 text-[10px] text-red-300 bg-red-500/10 border border-red-500/20 px-1.5 py-0.5 rounded-full">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10m0-10L4 7"/></svg>
                            {{ $batch }} barang / 1 pencatatan
                        </div>
                        @endif
                    </td>
                    <td class="px-3 sm:px-5 py-4"><div class="text-sm font-medium text-white">{{ $k->barang->nama ?? '-' }}</div><div class="text-xs text-zinc-500">{{ $k->barang->kategori ?? '-' }} @if($k->serial_number) • SN: {{ $k->serial_number }} @endif</div></td>
                    <td class="px-3 sm:px-5 py-4"><span class="text-sm font-bold text-red-400">-{{ $k->jumlah }} {{ $k->barang->satuan ?? 'pcs' }}</span></td>
                    <td class="px-3 sm:px-5 py-4"><div class="text-sm font-medium text-white">{{ $k->teknisi_nama }}</div><div class="text-xs px-2 py-0.5 rounded-full bg-white/[0.06] border border-white/[0.06] inline-block mt-1">{{ $k->teknisi_jabatan }}</div></td>
                    <td class="px-3 sm:px-5 py-4 hidden lg:table-cell text-sm text-zinc-400 max-w-[200px] truncate">{{ $k->keperluan ?? '-' }}</td>
                    <td class="px-3 sm:px-5 py-4 text-sm text-zinc-400">@if($k->taken_by && $k->taken_by === $k->operator_id)<span class="text-zinc-500">Mandiri</span>@else{{ $k->operator->name ?? '-' }}@endif</td>
                    <td class="px-3 sm:px-5 py-4 text-right">
                        @if(!auth()->user()->isTeknisi())
                        <form method="POST" action="{{ route('barang-keluar.destroy', $k) }}" onsubmit="return confirm(@if($batch > 1)'Hapus {{ $batch }} barang dalam 1 pencatatan ini & kembalikan stok?'@else'Hapus & kembalikan stok?'@endif)" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-zinc-400 hover:text-red-400" title="{{ $batch > 1 ? 'Hapus '.$batch.' barang sekaligus' : 'Hapus' }}">🗑️</button>
                        </form>
                        @else
                        <span class="text-xs text-zinc-600">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-16 text-center">
                    <div class="text-4xl mb-3">📤</div>
                    <div class="text-zinc-500 text-sm">Belum ada pengambilan</div>
                    <a href="{{ route('barang-keluar.create') }}" class="inline-block mt-3 text-sm text-cyan-400 hover:underline">+ Catat pengambilan pertama</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($keluars->hasPages())<div class="px-5 py-4 border-t border-white/[0.06]">{{ $keluars->links() }}</div>@endif
</div>
@endsection
