<x-app-layout>
    @section('title', 'e-Penataan - Daftar Proposal')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <h2 class="text-xl font-display font-bold text-ink">Buku Register Penataan Desa</h2>
        <p class="text-muted text-sm mt-1">Daftar proposal pembentukan Desa Persiapan dan pengajuan Kode Kemendagri
            untuk Desa Definitif.</p>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm mb-6 font-medium">
            {{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Wilayah Induk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Demografi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Status Registrasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Timeline Uji Coba</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-muted uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse ($penataan as $item)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-ink">{{ $item->desa->nama_desa }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                <span class="text-ink font-medium">{{ number_format($item->jumlah_penduduk) }}
                                    Jiwa</span><br>
                                <span class="text-muted">{{ number_format($item->jumlah_kk) }} KK</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->status === 'diajukan')
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-800">Menunggu
                                        Kajian UU</span>
                                @elseif($item->status === 'persiapan')
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-yellow-100 text-yellow-800">Desa
                                        Persiapan</span>
                                @elseif($item->status === 'definitif')
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800">Definitif
                                        (Bercatat)</span>
                                @else
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-800">Ditolak
                                        UU</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                @if($item->status === 'persiapan')
                                    @php $sisa = $item->sisaHariPersiapan(); @endphp
                                    @if($item->isHampirBatasPersiapan())
                                        <span class="text-red-600 font-bold animate-pulse">Sisa {{ $sisa }} Hari (Kritis!)</span>
                                    @else
                                        <span class="text-green-700 font-medium">Sisa {{ $sisa }} Hari</span>
                                    @endif
                                @elseif($item->status === 'definitif')
                                    <span class="text-primary font-bold">{{ $item->kode_desa_kemendagri }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('admin.penataan.show', $item) }}"
                                    class="text-primary hover:text-primary-light bg-primary-soft px-3 py-1.5 rounded-md font-medium transition-colors">Verifikasi
                                    GIS</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-muted">Belum ada pendaftaran wilayah
                                desa/kelurahan baru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($penataan->hasPages())
            <div class="px-6 py-4 border-t border-border">{{ $penataan->links() }}</div>
        @endif
    </div>
</x-app-layout>