<x-app-layout>
    @section('title', 'Draft Regulasi')

    <!-- List -->
    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Desa
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">No.
                            Regulasi</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Judul /
                            Tipe</th>
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
                    @forelse ($regulasis as $reg)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-ink font-semibold">
                                {{ $reg->desa->nama_desa }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-ink font-medium">
                                {{ $reg->no_regulasi ?? '-' }}</td>
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
                                @elseif($reg->status === 'perlu_revisi')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Perlu
                                        Revisi</span>
                                @elseif($reg->status === 'disetujui')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Menunggu Desa Sahkan</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Menunggu
                                        Verifikasi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.regulasi.show', $reg) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium rounded bg-primary text-white hover:bg-primary-light transition-all hover:scale-105 shadow-sm">
                                        Tinjau
                                    </a>
                                    <form action="{{ route('admin.regulasi.destroy', $reg->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus regulasi ini secara permanen? Semua berkas terkait akan ikut terhapus.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 text-xs font-medium rounded border border-red-200 transition-all hover:scale-105" title="Hapus">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-muted">Belum ada usulan produk hukum
                                masuk.</td>
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