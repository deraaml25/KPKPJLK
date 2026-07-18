<x-app-layout>
    @section('title', 'e-Bimtek Admin')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-display font-bold text-ink">Agenda Pembinaan & Bimtek</h2>
                <p class="text-muted text-sm mt-1">Kelola agenda peningkatan kapasitas aparatur desa dan monitoring
                    pengumpulan RTL.</p>
            </div>
            <div>
                <a href="{{ route('admin.bimtek.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Agenda Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-card p-6 shadow-sm border border-border flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-muted mb-1">Total Bimtek</p>
                <h3 class="text-3xl font-display font-bold text-ink">{{ $bimteks->total() }}</h3>
            </div>
        </div>
        <div class="bg-white rounded-card p-6 shadow-sm border border-border flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-muted mb-1">Total Peserta Terdaftar</p>
                <h3 class="text-3xl font-display font-bold text-primary">{{ $totalPeserta }}</h3>
            </div>
        </div>
        <div class="bg-white rounded-card p-6 shadow-sm border border-border flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-muted mb-1">Laporan RTL Tuntas</p>
                <h3 class="text-3xl font-display font-bold text-success">{{ $rtlUploads }}</h3>
            </div>
        </div>
        <div class="bg-white rounded-card p-6 shadow-sm border border-border flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-muted mb-1">RTL Menunggu Validasi</p>
                <h3 class="text-3xl font-display font-bold text-yellow-600">{{ $rtlMenunggu }}</h3>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Judul
                            Agenda</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">
                            Pelaksanaan</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Tempat
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">
                            Pendaftar</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse ($bimteks as $bim)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-ink font-display">{{ $bim->judul }}</div>
                                <div class="text-xs text-muted mt-1 class-truncate max-w-md">{{ $bim->deskripsi }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-muted">
                                {{ $bim->tanggal_pelaksanaan ? $bim->tanggal_pelaksanaan->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-ink">
                                {{ $bim->tempat ?? 'Daring' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-ink font-semibold">
                                {{ $bim->pendaftarans_count ?? 0 }} / {{ $bim->kuota }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.bimtek.show', $bim) }}"
                                    class="text-primary hover:underline font-medium">Detail &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-muted">Belum ada agenda pelatihan /
                                bimtek tersimpan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bimteks->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $bimteks->links() }}
            </div>
        @endif
    </div>
</x-app-layout>