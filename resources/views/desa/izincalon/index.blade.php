<x-app-layout>
    @section('title', 'Izin Pencalonan Aparatur')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-display font-bold text-ink">e-Izin Calon (Seleksi & Pencalonan)</h2>
                <p class="text-muted text-sm mt-1">Urus izin pencalonan kades atau perangkat desa bagi aparatur aktif,
                    serta tinjau rekam bebas temuan kerugian daerah Dinas.</p>
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

    <!-- Rule Board -->
    <div
        class="p-4 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-md text-xs mb-6 flex items-start gap-2.5">
        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
            </path>
        </svg>
        <div>
            <strong class="font-bold">Regulasi Kepatuhan Administrasi:</strong>
            <p class="mt-0.5">Berdasarkan peraturan daerah, bakal calon kades/perangkat desa dari unsur aparatur wajib
                memiliki Surat Keterangan Bebas Temuan Kerugian Desa dari Inspektorat. Calon terdeteksi memiliki temuan
                kerugian daerah tidak dapat disahkan administrasinya.</p>
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
                            Calon / Jabatan</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Kategori
                            Pencalonan</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Berkas
                            Inspektorat</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted tracking-wider uppercase">Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted tracking-wider uppercase">Catatan
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse ($izins as $izin)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-ink font-display">{{ $izin->nama_calon }}</div>
                                <div class="text-xs text-muted mt-0.5">{{ $izin->jabatan_sekarang }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-ink capitalize">
                                {{ $izin->jenis_calon }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ asset('storage/' . $izin->bebas_temuan_inspektorat_path) }}" target="_blank"
                                    class="text-primary hover:underline font-semibold text-xs">Unduh Berkas</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($izin->status === 'approved')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Izin
                                        Disetujui</span>
                                @elseif($izin->status === 'rejected')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Izin
                                        Ditolak</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Ditinjau</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-muted max-w-xs truncate">
                                {{ $izin->catatan_inspektorat ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-muted">Belum ada usulan izin
                                pencalonan terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>