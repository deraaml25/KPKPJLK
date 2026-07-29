<x-app-layout>
    @section('title', 'Verifikasi Data Kepala dan Perangkat Desa')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <h2 class="text-xl font-display font-bold text-ink">Verifikasi Perubahan Data Kepala dan Perangkat Desa</h2>
        <p class="text-muted text-sm mt-1">Daftar usulan penambahan, pengubahan, dan penonaktifan data kepala dan perangkat desa.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-lg border border-green-200 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 border-b border-border">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-ink">Desa</th>
                        <th class="px-6 py-4 font-semibold text-ink">Data Lama / Saat Ini</th>
                        <th class="px-6 py-4 font-semibold text-ink">Jenis Usulan</th>
                        <th class="px-6 py-4 font-semibold text-ink">Data Baru (Draft)</th>
                        <th class="px-6 py-4 font-semibold text-ink text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($pending as $item)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4">
                                <div class="font-bold text-ink">{{ $item->desa->nama_desa ?? '-' }}</div>
                                <div class="text-xs text-muted">{{ $item->desa->kecamatan->nama_kecamatan ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status_verifikasi === 'pending_tambah')
                                    <span class="text-muted italic">Data Baru</span>
                                @else
                                    <div class="font-medium text-ink">{{ $item->nama }}</div>
                                    <div class="text-xs text-muted">{{ $item->jabatan }}</div>
                                    <div class="text-xs text-muted">Mulai: {{ $item->tgl_mulai_jabatan ? $item->tgl_mulai_jabatan->format('d/m/Y') : '-' }}</div>
                                    <div class="text-xs text-muted">SK: {{ $item->no_sk_terakhir ?? '-' }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status_verifikasi === 'pending_tambah')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold">Tambah Data</span>
                                @elseif($item->status_verifikasi === 'pending_ubah')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-bold">Ubah Data</span>
                                @elseif($item->status_verifikasi === 'pending_nonaktif')
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-bold">Nonaktifkan</span>
                                @elseif($item->status_verifikasi === 'pending_aktif')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold">Aktifkan Kembali</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status_verifikasi === 'pending_ubah' && $item->draft_perubahan)
                                    <div class="font-medium text-ink">{{ $item->draft_perubahan['nama'] ?? $item->nama }}</div>
                                    <div class="text-xs text-muted">{{ $item->draft_perubahan['jabatan'] ?? $item->jabatan }}</div>
                                    <div class="text-xs text-muted">Mulai: {{ isset($item->draft_perubahan['tgl_mulai_jabatan']) ? date('d/m/Y', strtotime($item->draft_perubahan['tgl_mulai_jabatan'])) : '-' }}</div>
                                    <div class="text-xs text-muted">SK: {{ $item->draft_perubahan['no_sk_terakhir'] ?? '-' }}</div>
                                @elseif($item->status_verifikasi === 'pending_tambah')
                                    <div class="font-medium text-ink">{{ $item->nama }}</div>
                                    <div class="text-xs text-muted">{{ $item->jabatan }}</div>
                                    <div class="text-xs text-muted">Mulai: {{ $item->tgl_mulai_jabatan ? $item->tgl_mulai_jabatan->format('d/m/Y') : '-' }}</div>
                                    <div class="text-xs text-muted">SK: {{ $item->no_sk_terakhir ?? '-' }}</div>
                                @else
                                    <span class="text-muted italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.perangkat.approve', $item->id) }}" method="POST" onsubmit="return confirm('Setujui usulan ini?');">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-xs font-bold rounded hover:bg-green-700">Setujui</button>
                                    </form>
                                    <form action="{{ route('admin.perangkat.reject', $item->id) }}" method="POST" onsubmit="return confirm('Tolak usulan ini?');">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded hover:bg-red-700">Tolak</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-muted">
                                Tidak ada usulan perangkat desa yang menunggu verifikasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pending->hasPages())
            <div class="p-4 border-t border-border bg-gray-50">
                {{ $pending->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
