@extends('layouts.app')
@section('title', 'Barang Masuk - Gudang AFNALINK')
@section('page-title', 'Barang Masuk')
@section('page-subtitle', 'Stok masuk & import Excel')
@section('content')
<div class="flex flex-wrap gap-3 mb-6">
    <a href="{{ route('barang-masuk.create') }}" class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-sm font-medium">+ Tambah Masuk</a>
    <a href="{{ route('barang-masuk.export') }}" class="px-5 py-3 rounded-xl glass text-sm text-white">📥 Export Excel</a>
    <form method="POST" action="{{ route('barang-masuk.import') }}" enctype="multipart/form-data" class="flex gap-2 items-center">
        @csrf
        <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="text-sm text-zinc-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-cyan-500/20 file:text-cyan-300 file:text-sm">
        <button type="submit" class="px-5 py-3 rounded-xl glass text-sm text-white whitespace-nowrap">📤 Import Excel</button>
    </form>
</div>

<div class="glass rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="border-b border-white/[0.06] bg-white/[0.02]"><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">TANGGAL</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">BARANG</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">JUMLAH</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">SUMBER</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">OPERATOR</th><th class="text-right px-5 py-3 text-xs font-semibold text-zinc-400">AKSI</th></tr></thead>
            <tbody class="divide-y divide-white/[0.04]">
                @forelse($masuks as $m)
                <tr class="hover:bg-white/[0.03]">
                    <td class="px-5 py-4 text-sm text-zinc-300">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-5 py-4"><div class="text-sm font-medium text-white">{{ $m->barang->nama ?? '-' }}</div><div class="text-xs text-zinc-500">{{ $m->barang->kategori ?? '-' }}</div></td>
                    <td class="px-5 py-4"><span class="text-sm font-bold text-emerald-400">+{{ $m->jumlah }} {{ $m->barang->satuan ?? 'pcs' }}</span></td>
                    <td class="px-5 py-4 text-sm text-zinc-400">{{ $m->sumber ?? '-' }}</td>
                    <td class="px-5 py-4 text-sm text-zinc-400">{{ $m->operator->name ?? '-' }}</td>
                    <td class="px-5 py-4 text-right">
                        <form method="POST" action="{{ route('barang-masuk.destroy', $m) }}" onsubmit="return confirm('Hapus & kurangi stok?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-zinc-400 hover:text-red-400">🗑️</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-16 text-center text-zinc-500 text-sm">Belum ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($masuks->hasPages())<div class="px-5 py-4 border-t border-white/[0.06]">{{ $masuks->links() }}</div>@endif
</div>
@endsection
