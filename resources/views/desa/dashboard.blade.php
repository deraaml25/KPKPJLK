<x-app-layout>
    @section('title', 'Dashboard Desa')

    <!-- Welcome Section -->
    <div
        class="bg-primary text-white rounded-card p-8 mb-8 relative overflow-hidden shadow-floating border border-primary-light/30">
        <!-- Background decoration -->
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-5 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 right-20 w-32 h-32 bg-primary-light opacity-20 rounded-full blur-xl"></div>

        <div class="relative z-10">
            <h2 class="text-2xl font-display font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}!</h2>
            <p class="text-primary-soft mb-6 max-w-2xl">Kelola data perangkat desa dan pantau status ajuan rekomendasi
                penerbitan SK Kepala Desa secara real-time. Anda tidak perlu lagi datang atau menelepon untuk menanyakan
                progres.</p>

            <a href="{{ route('desa.ajuan.create') }}"
                class="inline-flex items-center px-6 py-3 bg-white text-primary font-medium rounded-btn hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Ajuan Baru
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-surface rounded-card p-6 shadow-sm border border-border flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-muted mb-1">Total Ajuan</p>
                <h3 class="text-3xl font-display font-bold text-ink">{{ $totalAjuan }}</h3>
            </div>
            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-muted">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
            </div>
        </div>

        <div class="bg-surface rounded-card p-6 shadow-sm border border-border flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-muted mb-1">Sedang Diproses</p>
                <h3 class="text-3xl font-display font-bold text-primary">{{ $sedangDiproses }}</h3>
            </div>
            <div class="w-12 h-12 bg-primary-soft rounded-full flex items-center justify-center text-primary">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-surface rounded-card p-6 shadow-sm border border-border flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-muted mb-1">Perlu Tindakan (Kurang)</p>
                <h3 class="text-3xl font-display font-bold text-danger">{{ $perluTindakan }}</h3>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-danger">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Active Submissions -->
    <h3 class="text-lg font-display font-semibold mb-4 text-ink">Ajuan Aktif Anda</h3>
    <div class="bg-surface rounded-card shadow-sm border border-border overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">No.
                            Registrasi</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Jenis
                            Layanan</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">
                            Perangkat Desa</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Tahap /
                            Status</th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-muted uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse($ajuans as $ajuan)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-ink font-medium">
                                {{ $ajuan->no_registrasi }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $badgeColor = match(optional($ajuan->jenisLayanan)->nama) {
                                        'Pengangkatan' => 'bg-primary-soft text-primary',
                                        'Pemberhentian' => 'bg-red-100 text-danger',
                                        'Rotasi' => 'bg-indigo-100 text-indigo-600',
                                        default => 'bg-gray-100 text-muted'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                                    {{ optional($ajuan->jenisLayanan)->nama ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-ink font-medium">
                                    {{ $ajuan->pesertas->first() ? $ajuan->pesertas->first()->perangkatDesa->nama : '-' }}
                                    @if($ajuan->pesertas->count() > 1) <span class="text-primary font-bold">(+{{ $ajuan->pesertas->count() - 1 }})</span> @endif
                                </div>
                                <div class="text-xs text-muted">{{ $ajuan->pesertas->first() ? $ajuan->pesertas->first()->perangkatDesa->jabatan : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ajuan->status === 'draft')
                                    <div class="text-sm text-muted font-medium">Bisa Diajukan</div>
                                    <div class="text-xs text-muted">Berkas Disiapkan</div>
                                @elseif($ajuan->status === 'revisi')
                                    <div class="text-sm text-danger font-medium">Perlu Revisi</div>
                                    <div class="text-xs text-danger">Mohon lengkapi berkas kurang</div>
                                @elseif($ajuan->status === 'selesai')
                                    <div class="text-sm text-success font-medium">Selesai</div>
                                    <div class="text-xs text-success">SK Bupati Terbit</div>
                                @else
                                    <div class="text-sm text-ink font-medium">Tahap {{ $ajuan->milestoneTrackings->where('tgl_selesai', null)->min('tahap') ?: 1 }} / 4</div>
                                    <div class="text-xs text-warning">Sedang diproses</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($ajuan->status === 'revisi')
                                    <a href="{{ route('desa.ajuan.show', $ajuan) }}"
                                        class="text-white bg-danger hover:bg-red-600 px-3 py-1.5 rounded-md transition-colors shadow-sm inline-block">Upload Ulang</a>
                                @else
                                    <a href="{{ route('desa.ajuan.show', $ajuan) }}"
                                        class="text-primary hover:text-primary-light bg-primary-soft px-3 py-1.5 rounded-md transition-colors inline-block">Detail</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-0">
                                <x-empty-state 
                                    icon="<path stroke-linecap='round' stroke-linejoin='round' d='M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2' />"
                                    title="Belum Ada Ajuan Aktif"
                                    message="Anda belum memiliki pengajuan rekomendasi yang sedang berproses."
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>