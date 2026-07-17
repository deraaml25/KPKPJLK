<x-app-layout>
    @section('title', 'Pencairan Siltap')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-display font-bold text-ink">e-Siltap (Penghasilan Tetap Aparatur)</h2>
                <p class="text-muted text-sm mt-1">Mengurus pengajuan pencairan penghasilan tetap bulanan, BPJS, dan SPJ
                    perangkat desa.</p>
            </div>
            <div>
                <a href="{{ route('desa.siltap.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Ajukan Siltap Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Alert / Auto-Lock Info -->
    <div class="p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-md text-xs mb-6 flex items-start gap-2.5">
        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <strong class="font-bold">Informasi Kepatuhan Auto-Lock System:</strong>
            <p class="mt-0.5">Sistem akan menguji ketertiban pelaporan SPJ bulan sebelumnya secara otomatis. Pengajuan
                tidak dapat diproses jika Laporan Pertanggungjawaban (SPJ) belum disahkan atau terlambat diunggah.</p>
        </div>
    </div>

    <!-- List -->
    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Periode
                            (Bulan/Tahun)</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Tanggal
                            Pengajuan</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Unduh
                            SP2D</th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-muted uppercase tracking-wider">Detail
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse ($siltaps as $siltap)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-ink">
                                {{ date('F', mktime(0, 0, 0, $siltap->bulan, 10)) }} / {{ $siltap->tahun }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-muted">
                                {{ $siltap->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($siltap->status === 'approved')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">SP2D
                                        Terbit</span>
                                @elseif($siltap->status === 'rejected')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Dievaluasi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($siltap->sp2d_path)
                                    <a href="{{ asset('storage/' . $siltap->sp2d_path) }}" target="_blank"
                                        class="text-primary hover:underline flex items-center gap-1 font-medium text-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Download SP2D
                                    </a>
                                @else
                                    <span class="text-muted text-xs font-medium">Menunggu Verifikasi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('desa.siltap.show', $siltap) }}"
                                    class="text-ink hover:text-black font-semibold text-xs border border-border px-3 py-1.5 rounded bg-gray-50/50 hover:bg-gray-100 transition-colors">Tinjau</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-muted">Belum ada usulan siltap
                                terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>