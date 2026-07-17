<x-app-layout>
    @section('title', 'Penataan Wilayah Desa')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-display font-bold text-ink">e-Penataan Desa (Penataan Wilayah)</h2>
                <p class="text-muted text-sm mt-1">Kelola usulan pemekaran, penggabungan, atau perubahan status/batas
                    desa secara terorganisir.</p>
            </div>
            <div>
                <a href="{{ route('desa.penataan.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Ajukan Penataan Wilayah
                </a>
            </div>
        </div>
    </div>

    <!-- UU Kelayakan Alert / Calculator info -->
    <div class="p-4 bg-muted/40 border border-border rounded-md text-xs mb-6 flex items-start gap-2.5">
        <div class="w-6 h-6 rounded bg-primary-soft text-primary flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z">
                </path>
            </svg>
        </div>
        <div>
            <strong class="font-bold text-ink">Kalkulator Kelayakan Hukum Pemekaran Wilayah (Undang-Undang
                Desa):</strong>
            <p class="text-muted mt-0.5">Berdasarkan regulasi UU Desa terbaru, pemekaran wilayah mewajibkan batas
                minimum jumlah penduduk di wilayah Jawa sebesar 6.000 jiwa (atau 1.200 KK). Bila input data KK /
                penduduk di bawah syarat tersebut, pengajuan akan secara otomatis dikunci (locked) oleh sistem.</p>
        </div>
    </div>

    <!-- List -->
    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Tipe
                            Usulan</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Target
                            Wilayah / Dusun</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">
                            Kelayakan Penduduk</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted tracking-wider uppercase">Status
                            Evaluasi</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted tracking-wider uppercase">Doc
                            Rekomendasi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse ($penataans as $pen)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-ink uppercase">
                                {{ $pen->tipe }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-ink">
                                {{ $pen->nama_wilayah_baru }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs font-medium text-ink">{{ $pen->jumlah_penduduk }} Jiwa /
                                    {{ $pen->jumlah_kk }} KK</div>
                                @if($pen->jumlah_penduduk >= 6000)
                                    <span
                                        class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-green-100 text-green-800">Lolos
                                        Syarat UU</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-100 text-red-800">Di
                                        Bawah Batas Minimal</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($pen->status === 'approved')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui</span>
                                @elseif($pen->status === 'rejected')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Fasilitasi
                                        Tim Kab</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($pen->rekomendasi_dinas_path)
                                    <a href="{{ asset('storage/' . $pen->rekomendasi_dinas_path) }}" target="_blank"
                                        class="text-primary hover:underline flex items-center gap-1 font-semibold text-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        Rekomendasi
                                    </a>
                                @else
                                    <span class="text-muted text-xs font-medium">Dalam Kajian</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-muted">Belum ada usulan penataan
                                wilayah terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>