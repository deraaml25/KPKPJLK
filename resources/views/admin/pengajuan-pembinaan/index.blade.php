<x-app-layout>
    @section('title', 'Pembinaan')
    @section('page-description', 'Daftar permohonan narasumber dan pembinaan yang diajukan oleh desa-desa.')
    @section('page-actions')
        <div class="flex items-center gap-3">
            <div class="bg-yellow-50 border border-yellow-200 px-3 py-2 rounded-lg text-center">
                <p class="text-xs text-muted">Menunggu</p>
                <p class="text-xl font-bold text-yellow-700">{{ $totalMenunggu }}</p>
            </div>
            <div class="bg-blue-50 border border-blue-200 px-3 py-2 rounded-lg text-center">
                <p class="text-xs text-muted">Disetujui</p>
                <p class="text-xl font-bold text-blue-700">{{ $totalDisetujui }}</p>
            </div>
        </div>
    @endsection

    <!-- Tabs Nav -->
    <div class="border-b border-border mb-6">
        <nav class="flex space-x-8" aria-label="Tabs">
            <a href="{{ route('admin.bimtek-informasi.index') }}"
               class="border-b-2 py-4 px-1 text-sm font-semibold {{ request()->routeIs('admin.bimtek-informasi.*') ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-ink hover:border-gray-300' }}">
                📰 Berita & Informasi Pembinaan
            </a>
            <a href="{{ route('admin.pengajuan-pembinaan.index') }}"
               class="border-b-2 py-4 px-1 text-sm font-semibold {{ request()->routeIs('admin.pengajuan-pembinaan.*') ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-ink hover:border-gray-300' }}">
                📨 Pengajuan Pembinaan Desa
            </a>
        </nav>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-card text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Desa</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Judul Kegiatan</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Tanggal Diajukan</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Dokumen</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse ($pengajuans as $p)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-5 py-4 text-sm font-medium text-ink">{{ $p->desa->nama_desa ?? '-' }}</td>
                            <td class="px-5 py-4">
                                <div class="text-sm text-ink font-medium">{{ $p->judul_kegiatan }}</div>
                                @if($p->deskripsi)
                                    <div class="text-xs text-muted mt-0.5">{{ Str::limit($p->deskripsi, 80) }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-sm text-muted whitespace-nowrap">
                                {{ $p->tanggal_diajukan->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-col gap-1">
                                    @if($p->file_surat_permohonan)
                                        <a href="{{ asset('storage/' . $p->file_surat_permohonan) }}" target="_blank"
                                            class="text-primary text-xs hover:underline">📄 Surat Permohonan</a>
                                    @endif
                                    @if($p->file_undangan)
                                        <a href="{{ asset('storage/' . $p->file_undangan) }}" target="_blank"
                                            class="text-primary text-xs hover:underline">📄 Surat Undangan</a>
                                    @endif
                                    @if(!$p->file_surat_permohonan && !$p->file_undangan)
                                        <span class="text-xs text-muted italic">Tidak ada dokumen</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $p->status_color }}">
                                    {{ $p->status_label }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.pengajuan-pembinaan.show', $p) }}"
                                    class="text-primary text-sm hover:underline font-medium">
                                    Detail & Balas →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-sm text-muted">
                                Belum ada pengajuan pembinaan dari desa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pengajuans->hasPages())
            <div class="px-5 py-4 border-t border-border">{{ $pengajuans->links() }}</div>
        @endif
    </div>
</x-app-layout>
