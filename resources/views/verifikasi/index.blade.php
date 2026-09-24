@extends('layouts.app')
@section('title', 'Verifikasi Hapus - Gudang AFNALINK')
@section('page-title', 'Verifikasi Hapus')
@section('page-subtitle', 'Persetujuan admin untuk hapus Data Barang')
@section('content')
<div class="glass rounded-2xl p-5 mb-6 flex items-center gap-4">
    <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center">🛡️</div>
    <div>
        <div class="text-sm font-semibold text-white">Verifikasi Admin</div>
        <div class="text-xs text-zinc-500">Operator minta hapus Data Barang harus disetujui admin dulu.</div>
    </div>
    <div class="ml-auto text-right">
        <div class="text-2xl font-bold {{ $totalPending > 0 ? 'text-amber-400' : 'text-emerald-400' }}">{{ $totalPending }}</div>
        <div class="text-xs text-zinc-500">Menunggu</div>
    </div>
</div>

<div class="glass rounded-2xl overflow-hidden">
    <div class="px-5 py-4 border-b border-white/[0.06] flex items-center justify-between">
        <h3 class="font-semibold text-white text-sm">Permintaan Hapus Data Barang</h3>
        <span class="text-xs px-2.5 py-1 rounded-full {{ $totalPending > 0 ? 'bg-amber-500/10 border-amber-500/20 text-amber-300' : 'bg-emerald-500/10 border-emerald-500/20 text-emerald-300' }}">{{ $totalPending }} pending</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="border-b border-white/[0.06] bg-white/[0.02]"><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">BARANG</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">KATEGORI</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">STOK</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">DIMINTA OLEH</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">WAKTU MINTA</th><th class="text-right px-5 py-3 text-xs font-semibold text-zinc-400">AKSI</th></tr></thead>
            <tbody class="divide-y divide-white/[0.04]">
                @forelse($pendings as $p)
                <tr class="bg-amber-500/[0.04] hover:bg-amber-500/[0.06]">
                    <td class="px-5 py-4"><div class="text-sm font-medium text-white">{{ $p->nama }}</div><div class="text-xs text-zinc-500">{{ $p->merk ?? '-' }} • SN: {{ $p->serial_number ?? '-' }}</div></td>
                    <td class="px-5 py-4"><span class="text-xs px-2.5 py-1 rounded-full bg-white/[0.06] border border-white/[0.06] text-zinc-300">{{ $p->kategori }}</span></td>
                    <td class="px-5 py-4"><span class="text-sm font-bold {{ $p->stok <= 5 ? 'text-amber-400' : 'text-white' }}">{{ $p->stok }} {{ $p->satuan }}</span></td>
                    <td class="px-5 py-4"><div class="text-sm font-medium text-white">{{ $p->deletionRequester->name ?? '-' }}</div><div class="text-xs text-zinc-500">{{ $p->deletionRequester->jabatan ?? '-' }} • {{ $p->deletionRequester->email ?? '-' }}</div></td>
                    <td class="px-5 py-4 text-sm text-zinc-400">{{ $p->deletion_requested_at ? $p->deletion_requested_at->format('d/m/Y H:i') : '-' }}</td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <form method="POST" action="{{ route('barang.verify', $p) }}" class="inline">
                                @csrf
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-xs font-medium hover:bg-emerald-500/30">✓ Setujui Hapus</button>
                            </form>
                            <form method="POST" action="{{ route('barang.verify', $p) }}" class="inline">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="px-4 py-2 rounded-xl bg-red-500/10 border border-red-500/20 text-red-300 text-xs hover:bg-red-500/20">✕ Tolak</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-16 text-center">
                    <div class="text-3xl mb-3">✅</div>
                    <div class="text-sm font-medium text-white">Tidak ada permintaan pending</div>
                    <div class="text-xs text-zinc-500 mt-1">Semua aman. Jika operator minta hapus Data Barang, akan muncul di sini untuk Anda verifikasi.</div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pendings->hasPages())<div class="px-5 py-4 border-t border-white/[0.06]">{{ $pendings->links() }}</div>@endif
</div>

<div class="mt-6 glass rounded-xl p-4 border border-cyan-500/10 bg-cyan-500/[0.03]">
    <div class="text-xs font-semibold text-cyan-300 mb-1">Cara kerja:</div>
    <div class="text-xs text-zinc-500">1. Operator klik 🗑️ di Data Barang → status jadi <span class="text-amber-300">Menunggu Verifikasi</span> (tidak langsung terhapus)<br>2. Admin buka halaman ini → klik <span class="text-emerald-300">Setujui</span> (hapus permanen) atau <span class="text-red-300">Tolak</span> (batalkan permintaan)</div>
</div>
@endsection
