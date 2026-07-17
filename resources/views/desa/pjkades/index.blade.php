<x-app-layout>
    @section('title', 'Pj Kades Desa')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-display font-bold text-ink">e-Pj Kades (Pj Kepala Desa)</h2>
                <p class="text-muted text-sm mt-1">Usulan penunjukan Pj Kepala Desa dari unsur PNS untuk menjaga
                    kesinambungan pemerintahan desa.</p>
            </div>
            <div>
                <a href="{{ route('desa.pjkades.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Ajukan Usulan Pj Kades
                </a>
            </div>
        </div>
    </div>

    <!-- Info SLA -->
    <div class="p-4 bg-muted/30 border border-border rounded-md text-xs mb-6 flex items-start gap-2.5">
        <div class="w-6 h-6 rounded bg-primary-soft text-primary flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <strong class="font-bold text-ink">SLA Pengurusan: 14 Hari Kerja</strong>
            <p class="text-muted mt-0.5">Sesuai peraturan, fasilitasi usulan Pj Kepala Desa, mulai dari verifikasi
                berkas PNS, peninjauan sanksi disiplin, hingga penerbitan SK Bupati dilakukan dalam rentang waktu
                maksimal 14 hari kerja.</p>
        </div>
    </div>

    <!-- List -->
    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Nama
                            Calon Pj / NIP</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">
                            Pangkat/Golongan</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Bebas
                            Hukdis</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted tracking-wider uppercase">Status
                            Usulan</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted tracking-wider uppercase">SK
                            Bupati</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse ($pjkades as $pj)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-ink font-display">{{ $pj->nama_pns }}</div>
                                <div class="text-xs text-muted font-mono mt-0.5">NIP. {{ $pj->nip }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-ink">
                                {{ $pj->pangkat }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($pj->status_bebas_hukdis === 'clean')
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800">Bebas</span>
                                @elseif($pj->status_bebas_hukdis === 'has_issues')
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-800">Ada
                                        Temuan</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-800">Pending
                                        Evaluasi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($pj->status === 'approved')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">SK
                                        Terbit</span>
                                @elseif($pj->status === 'rejected')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Dievaluasi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($pj->sk_bupati_path)
                                    <a href="{{ asset('storage/' . $pj->sk_bupati_path) }}" target="_blank"
                                        class="text-primary hover:underline flex items-center gap-1 font-semibold text-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        SK Bupati
                                    </a>
                                @else
                                    <span class="text-muted text-xs font-medium">Dalam Proses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-muted">Belum ada usulan Pj Kepala
                                Desa terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>