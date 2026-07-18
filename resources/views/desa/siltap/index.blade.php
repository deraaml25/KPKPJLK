<x-app-layout>
    @section('title', 'e-Siltap Desa')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-display font-bold text-ink">e-Siltap (Penghasilan Tetap & Tunjangan)</h2>
                <p class="text-muted text-sm mt-1">Ajukan pencairan Siltap bulanan perangkat desa melalui sistem
                    terverifikasi.</p>
            </div>
            <div>
                @if($canSubmitCheck['allowed'])
                    <a href="{{ route('desa.siltap.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajukan Pencairan Bulan Ini
                    </a>
                @else
                    <div class="px-4 py-2 bg-red-50 text-red-700 rounded-btn text-sm border border-red-200">
                        🔒 {{ $canSubmitCheck['reason'] }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Riwayat Pengajuan -->
    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Jml Perangkat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Dokumen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse ($siltaps as $s)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 text-sm font-medium text-ink">{{ $s->nama_bulan }} {{ $s->tahun }}</td>
                            <td class="px-6 py-4 text-sm text-ink">{{ $s->jumlah_perangkat_aktif }} orang</td>
                            <td class="px-6 py-4 text-xs space-y-1">
                                @if($s->rekomendasi_camat_path)<a
                                    href="{{ asset('storage/' . $s->rekomendasi_camat_path) }}" target="_blank"
                                class="text-primary hover:underline block">📄 Rekomendasi Camat</a>@endif
                                @if($s->bukti_bpjs_path)<a href="{{ asset('storage/' . $s->bukti_bpjs_path) }}"
                                target="_blank" class="text-primary hover:underline block">📄 Bukti BPJS</a>@endif
                                @if($s->spj_path)<a href="{{ asset('storage/' . $s->spj_path) }}" target="_blank"
                                class="text-primary hover:underline block">📄 SPJ Bulan Lalu</a>@endif
                            </td>
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
                                        Dikirim BKAD</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">🔵
                                        Menunggu Verifikasi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('desa.siltap.show', $s) }}"
                                    class="text-primary hover:underline font-medium">Detail &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-0">
                                <x-empty-state
                                    icon="<path stroke-linecap='round' stroke-linejoin='round' d='M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z' />"
                                    title="Belum Ada Pengajuan Siltap"
                                    message="Anda belum pernah mengajukan pencairan SPJ Penghasilan Tetap. Silakan klik tombol ajukan di atas untuk memulai." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>