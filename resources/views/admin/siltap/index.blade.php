<x-app-layout>
    @section('title', 'e-Siltap - Evaluasi Usulan Siltap')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <div>
            <h2 class="text-xl font-display font-bold text-ink">Evaluasi e-Siltap Desa</h2>
            <p class="text-muted text-sm mt-1">Verifikasi kelengkapan usulan pencairan penghasilan tetap perangkat desa
                bulanan.</p>
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
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Periode
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Tanggal
                            Masuk</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted tracking-wider uppercase">Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-muted tracking-wider uppercase">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse ($siltaps as $siltap)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-ink">
                                {{ $siltap->desa->nama_desa }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-ink">
                                {{ date('F', mktime(0, 0, 0, $siltap->bulan, 10)) }} / {{ $siltap->tahun }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-muted">
                                {{ $siltap->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($siltap->status === 'approved')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui</span>
                                @elseif($siltap->status === 'rejected')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Dievaluasi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.siltap.show', $siltap) }}"
                                    class="text-primary hover:text-primary-light bg-primary-soft px-3 py-1.5 rounded-md transition-colors">Tinjau</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-muted">Belum ada usulan siltap masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($siltaps->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $siltaps->links() }}
            </div>
        @endif
    </div>
</x-app-layout>