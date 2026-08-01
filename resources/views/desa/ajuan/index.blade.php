<x-app-layout>
    @section('title', 'Daftar Ajuan Saya')

    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-muted text-sm mt-1">Kelola seluruh ajuan rekomendasi dari desa Anda.</p>
        </div>
        
        <a href="{{ route('desa.ajuan.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Ajuan Baru
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-card bg-green-50 border border-green-200 text-green-700 flex items-start shadow-sm">
            <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <!-- Ajuan Table -->
    <div class="bg-surface rounded-card border border-border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">No. Registrasi / Tgl</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Jenis Layanan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Perangkat Desa</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Progres & Tahap</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($ajuans as $ajuan)
                        @php
                            $slaHariBerjalan = $ajuan->tgl_diajukan ? now()->diffInDaysFiltered(fn($d) => !$d->isWeekend(), $ajuan->tgl_diajukan) : 0;
                            $tahapAktif = $ajuan->milestoneTrackings->where('tgl_selesai', null)->min('tahap')
                                ?? ($ajuan->milestoneTrackings->max('tahap') + 1 ?: 1);
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-mono text-sm font-semibold text-ink">{{ $ajuan->no_registrasi }}</div>
                                <div class="text-xs text-muted">{{ \Carbon\Carbon::parse($ajuan->tgl_diajukan)->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $badgeColor = match($ajuan->jenisLayanan->nama) {
                                        'Pengangkatan' => 'bg-primary-soft text-primary',
                                        'Pemberhentian' => 'bg-red-100 text-danger',
                                        'Rotasi' => 'bg-indigo-100 text-indigo-600',
                                        default => 'bg-gray-100 text-muted'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                                    {{ $ajuan->jenisLayanan->nama }}
                                </span>
                                <div class="text-xs text-muted mt-1 uppercase font-bold">{{ $ajuan->metode }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-ink">
                                    {{ $ajuan->pesertas->first() ? $ajuan->pesertas->first()->perangkatDesa->nama : '-' }}
                                    @if($ajuan->pesertas->count() > 1) 
                                        <span class="text-primary font-bold ml-1">(+{{ $ajuan->pesertas->count() - 1 }})</span>
                                    @endif
                                </div>
                                <div class="text-xs text-muted">{{ $ajuan->pesertas->first() ? $ajuan->pesertas->first()->perangkatDesa->jabatan : '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 mb-1">
                                    @php
                                        $statusLabel = ['submitted' => 'Menunggu Verifikasi', 'direvisi' => 'Perlu Perbaikan', 'diproses' => 'Sedang Diproses', 'selesai' => 'Selesai', 'draft' => 'Draft'];
                                        $statusWarna = ['submitted' => 'bg-blue-100 text-blue-700', 'direvisi' => 'bg-red-100 text-danger', 'diproses' => 'bg-yellow-100 text-warning', 'selesai' => 'bg-green-100 text-success', 'draft' => 'bg-gray-100 text-muted'];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium {{ $statusWarna[$ajuan->status] ?? 'bg-gray-100 text-muted' }}">
                                        {{ $statusLabel[$ajuan->status] ?? $ajuan->status }}
                                    </span>
                                </div>
                                @if(in_array($ajuan->status, ['submitted', 'direvisi', 'diproses']))
                                    <p class="text-xs text-ink font-medium">Tahap {{ $tahapAktif <= 9 ? $tahapAktif : 9 }}/9</p>
                                    <p class="text-xs text-muted mt-0.5">Berjalan: {{ $slaHariBerjalan }} HK (Maks 20 HK)</p>
                                @elseif($ajuan->status === 'selesai')
                                    <p class="text-xs text-success font-medium">Rekomendasi Terbit</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('desa.ajuan.show', $ajuan) }}" class="inline-flex items-center px-4 py-2 bg-primary-soft text-primary text-sm font-medium rounded-btn hover:bg-primary hover:text-white transition-all">
                                    Lihat & Upload Dokumen
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-0">
                                <x-empty-state 
                                    icon="<path stroke-linecap='round' stroke-linejoin='round' d='M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' />"
                                    title="Belum ada ajuan yang dibuat"
                                    message="Mulai dengan membuat ajuan baru untuk memproses rekomendasi perangkat desa."
                                >
                                    <x-slot name="action">
                                        <a href="{{ route('desa.ajuan.create') }}" class="px-6 py-2.5 bg-primary text-white rounded-full text-sm font-bold shadow-md hover:bg-primary-light hover:shadow-lg hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            Buat Ajuan Sekarang
                                        </a>
                                    </x-slot>
                                </x-empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($ajuans->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $ajuans->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
