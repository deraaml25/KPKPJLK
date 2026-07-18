<x-app-layout>
    @section('title', 'e-Siltap Admin')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <h2 class="text-xl font-display font-bold text-ink">e-Siltap — Verifikasi Pencairan</h2>
        <p class="text-muted text-sm mt-1">Verifikasi kelengkapan dokumen pencairan penghasilan tetap & tunjangan
            perangkat desa.</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-card p-6 shadow-sm border border-border">
            <p class="text-sm font-medium text-muted mb-1">Total Pengajuan</p>
            <h3 class="text-3xl font-display font-bold text-ink">{{ $siltaps->total() }}</h3>
        </div>
        <div class="bg-white rounded-card p-6 shadow-sm border border-border">
            <p class="text-sm font-medium text-muted mb-1">Menunggu Verifikasi</p>
            <h3 class="text-3xl font-display font-bold text-yellow-600">{{ $totalMenunggu }}</h3>
        </div>
        <div class="bg-white rounded-card p-6 shadow-sm border border-border">
            <p class="text-sm font-medium text-muted mb-1">Sudah Disetujui</p>
            <h3 class="text-3xl font-display font-bold text-green-600">{{ $totalDisetujui }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Desa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Perangkat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse ($siltaps as $s)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 text-sm font-medium text-ink">{{ $s->desa->nama_desa ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-ink">{{ $s->nama_bulan }} {{ $s->tahun }}</td>
                            <td class="px-6 py-4 text-sm text-ink">{{ $s->jumlah_perangkat_aktif }} orang</td>
                            <td class="px-6 py-4">
                                @if($s->status === 'disetujui')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">✅
                                        Disetujui</span>
                                @elseif($s->status === 'ditolak')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">❌
                                        Ditolak</span>
                                @elseif($s->status === 'dikirim_bkad')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-800 text-white">⚫
                                        BKAD</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">🔵
                                        Menunggu</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.siltap.show', $s) }}"
                                    class="text-primary hover:underline font-medium">Tinjau &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-muted">Belum ada pengajuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($siltaps->hasPages())
            <div class="px-6 py-4 border-t border-border">{{ $siltaps->links() }}</div>
        @endif
    </div>
</x-app-layout>