<x-app-layout>
    @section('title', 'e-Pj Kades - Daftar Usulan')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <div>
            <h2 class="text-xl font-display font-bold text-ink">Daftar Usulan Pj Kepala Desa</h2>
            <p class="text-muted text-sm mt-1">Fasilitasi penunjukan penjabat kepala desa dari unsur aparatur sipil
                negara (ASN/PNS).</p>
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
                            Calon Pj / NIP</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Golongan
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Bebas
                            Hukdis</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted tracking-wider uppercase">Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-muted tracking-wider uppercase">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse ($pjkades as $pj)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-ink">
                                {{ $pj->desa->nama_desa }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-sm text-ink">
                                <div>{{ $pj->nama_pns }}</div>
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
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-800">Belum
                                        Diverifikasi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($pj->status === 'approved')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Persetujuan
                                        Terbit</span>
                                @elseif($pj->status === 'rejected')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Proses
                                        Evaluasi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('admin.pjkades.show', $pj) }}"
                                    class="text-primary hover:text-primary-light bg-primary-soft px-3 py-1.5 rounded-md font-medium transition-colors">Tinjau</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-muted">Belum ada usulan Pj Kades yang
                                masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pjkades->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $pjkades->links() }}
            </div>
        @endif
    </div>
</x-app-layout>