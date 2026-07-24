<x-app-layout>
    @section('title', 'e-Pj Kades')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-display font-bold text-ink">SK Kades</h2>
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

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm mb-6 font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Nama
                            Calon Pj / NIP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Pangkat
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Bebas
                            Hukdis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Masa
                            Jabatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">SK
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-ink">{{ $pj->pangkat }}</td>
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
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-800">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($pj->status === 'approved')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Pj
                                        Kades Aktif</span>
                                @elseif($pj->status === 'rejected')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Menunggu
                                        Verifikasi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-ink">
                                @if($pj->tgl_mulai && $pj->tgl_selesai)
                                    <div>{{ $pj->tgl_mulai->format('d/m/Y') }} — {{ $pj->tgl_selesai->format('d/m/Y') }}</div>
                                    @if($pj->status === 'approved')
                                        @if($pj->sudah_berakhir)
                                            <span class="text-red-600 font-bold">Sudah Berakhir</span>
                                        @elseif($pj->hampir_berakhir)
                                            <span class="text-yellow-600 font-bold">Sisa {{ $pj->sisa_hari }} hari</span>
                                        @else
                                            <span class="text-green-600 font-medium">Sisa {{ $pj->sisa_hari }} hari</span>
                                        @endif
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
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
                                    <span class="text-muted text-xs font-medium">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-0">
                                <x-empty-state
                                    icon="<path stroke-linecap='round' stroke-linejoin='round' d='M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' />"
                                    title="Pj Kepala Desa Kosong"
                                    message="Belum ada usulan Pj Kepala Desa yang terdaftar. Tambahkan data dari tombol Ajukan." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>