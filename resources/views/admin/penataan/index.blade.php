<x-app-layout>
    @section('title', 'e-Penataan - Evaluasi Penataan Wilayah')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <div>
            <h2 class="text-xl font-display font-bold text-ink">Evaluasi Penataan Wilayah Desa</h2>
            <p class="text-muted text-sm mt-1">Lakukan kajian syarat hukum & demografis usulan pemekaran, penggabungan,
                atau penetapan batas desa.</p>
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
                            Pengusul</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Tipe
                            Usulan</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Nama
                            Dusun/Wilayah Baru</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Penduduk
                            / KK</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted tracking-wider uppercase">Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-muted tracking-wider uppercase">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse ($penataans as $pen)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-ink">
                                {{ $pen->desa->nama_desa }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-ink uppercase font-medium">
                                {{ $pen->tipe }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-ink font-semibold">
                                {{ $pen->nama_wilayah_baru }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-muted">
                                {{ $pen->jumlah_penduduk }} Jiwa / {{ $pen->jumlah_kk }} KK
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                @if($pen->status === 'approved')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui</span>
                                @elseif($pen->status === 'rejected')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Evaluasi
                                        Tim</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('admin.penataan.show', $pen) }}"
                                    class="text-primary hover:text-primary-light bg-primary-soft px-3 py-1.5 rounded-md font-medium transition-colors">Tinjau
                                    Kajian</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-muted">Belum ada usulan penataan
                                wilayah masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($penataans->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $penataans->links() }}
            </div>
        @endif
    </div>
</x-app-layout>