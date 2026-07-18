<x-app-layout>
    @section('title', 'e-Izin Calon Kades')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-display font-bold text-ink">e-Izin Calon Kepala Desa</h2>
                <p class="text-muted text-sm mt-1">Permohonan izin resmi dari Bupati untuk maju sebagai calon Kepala
                    Desa.</p>
            </div>
            <div>
                <a href="{{ route('desa.izincalon.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Ajukan Izin Pencalonan
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm mb-6 font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Nama
                            Calon / Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Jabatan
                            Sekarang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Surat
                            Izin Bupati</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Tanggal
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse ($izins as $izin)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-ink font-display">{{ $izin->nama_calon }}</div>
                                <div class="text-xs text-muted mt-0.5">{{ $izin->label_jenis_calon }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-ink">{{ $izin->jabatan_sekarang }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($izin->status === 'approved')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">✅
                                        Izin Diterbitkan</span>
                                @elseif($izin->status === 'rejected')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">❌
                                        Ditolak</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">🔵
                                        Menunggu Validasi Dinas</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($izin->surat_izin_bupati_path)
                                    <a href="{{ asset('storage/' . $izin->surat_izin_bupati_path) }}" target="_blank"
                                        class="text-primary hover:underline flex items-center gap-1 font-semibold text-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        Unduh Surat Izin Bupati
                                    </a>
                                @else
                                    <span class="text-muted text-xs">Belum diterbitkan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-muted">
                                {{ $izin->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-0">
                                <x-empty-state
                                    icon="<path stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z' />"
                                    title="Izin Pencalonan Kosong"
                                    message="Belum ada permohonan izin pencalonan untuk Pilkades mendatang." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>