<x-app-layout>
    @section('title', 'e-Regulasi Desa')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-display font-bold text-ink">Draft Regulasi</h2>
                <p class="text-muted text-sm mt-1">Fasilitasi dan pendampingan draf produk hukum desa (Perdes, Perkades,
                    SK Kades).</p>
            </div>
            <div>
                <a href="{{ route('desa.regulasi.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Ajukan Regulasi Baru
                </a>
            </div>
        </div>
    </div>

    <!-- List -->
    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">No.
                            Registrasi</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Judul /
                            Tipe</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Tanggal
                            Diajukan</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted tracking-wider uppercase">Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted tracking-wider uppercase">Revisi /
                            Catatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse ($regulasis as $reg)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-ink font-medium">
                                {{ $reg->no_regulasi }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-ink font-display">{{ $reg->judul }}</div>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-primary-soft text-primary mt-1 capitalize">{{ $reg->tipe }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-muted">
                                {{ $reg->tgl_diajukan ? $reg->tgl_diajukan->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($reg->status === 'disahkan')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disahkan</span>
                                @elseif($reg->status === 'direvisi')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Perlu
                                        Revisi</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Menunggu
                                        Verifikasi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <a href="{{ route('desa.regulasi.show', $reg) }}"
                                    class="text-primary hover:text-primary-dark transition-colors inline-flex items-center">
                                    Lihat Detail &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-0">
                                <x-empty-state
                                    icon="<path stroke-linecap='round' stroke-linejoin='round' d='M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' />"
                                    title="Regulasi Kosong"
                                    message="Belum ada usulan produk hukum yang diajukan oleh desa Anda." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($regulasis->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $regulasis->links() }}
            </div>
        @endif
    </div>
</x-app-layout>