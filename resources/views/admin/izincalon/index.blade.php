<x-app-layout>
    @section('title', 'e-Izin Calon - Evaluasi Izin Pencalonan')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <div>
            <h2 class="text-xl font-display font-bold text-ink">Evaluasi Izin Pencalonan (e-Izin Calon)</h2>
            <p class="text-muted text-sm mt-1">Evaluasi seleksi administrasi dan pengecekan track record temuan kerugian
                daerah Inspektorat.</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Desa
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Nama
                            Calon / Jabatan</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">
                            Pencalonan</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted tracking-wider uppercase">Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-muted tracking-wider uppercase">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse ($izins as $izin)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-ink">
                                {{ $izin->desa->nama_desa }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-sm text-ink">
                                <div>{{ $izin->nama_calon }}</div>
                                <div class="text-xs text-muted mt-0.5">{{ $izin->jabatan_sekarang }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-ink capitalize">
                                {{ $izin->jenis_calon }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($izin->status === 'approved')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Izin
                                        Disetujui</span>
                                @elseif($izin->status === 'rejected')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Menunggu
                                        Verifikasi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.izincalon.show', $izin) }}"
                                    class="text-primary hover:text-primary-light bg-primary-soft px-3 py-1.5 rounded-md transition-colors">Tinjau</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-muted">Belum ada usulan izin
                                pencalonan masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>