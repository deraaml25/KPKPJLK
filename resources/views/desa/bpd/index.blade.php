<x-app-layout>
    @section('title', 'Data BPD Saya')

    <div
        class="bg-white rounded-card p-6 shadow-sm border border-border mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-xl font-display font-bold text-ink">Data BPD</h2>
            <p class="text-muted text-sm mt-1">
                Data master bpd aktif di <strong>{{ auth()->user()->desa->nama_desa ?? 'Desa Anda' }}</strong>.
                Data ini difilter otomatis oleh sistem (hanya menampilkan wilayah Anda).
            </p>
        </div>
        <div class="bg-primary/10 text-primary px-4 py-2 rounded-lg border border-primary/20 text-center shadow-sm">
            <span class="block text-2xl font-black font-display leading-none">{{ $totalAktif }}</span>
            <span class="text-[10px] font-bold uppercase tracking-wider">BPD Aktif</span>
        </div>
    </div>

    <!-- Toolbar / Pencarian -->
    <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <form method="GET" action="{{ route('desa.bpd.index') }}" class="w-full md:w-1/3 relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / jabatan..."
                class="w-full text-sm rounded bg-white border border-border shadow-sm pl-10 h-10 focus:border-primary focus:ring-1 focus:ring-primary transition-all">
        </form>
        <div class="flex items-center gap-2">
            <a href="{{ route('desa.bpd.create') }}"
                class="inline-flex items-center px-4 h-10 bg-primary text-white text-sm font-bold rounded-btn hover:bg-primary-light transition-colors shadow-sm whitespace-nowrap">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah BPD
            </a>
        </div>
    </div>

    <!-- Tabel -->
    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Jabatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Nomor SK</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Mulai Menjabat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-muted uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border text-sm">
                    @forelse ($bpd as $row)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-ink">{{ $row->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-muted">{{ $row->jabatan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-muted">{{ $row->no_sk_terakhir ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-muted">
                                {{ $row->tgl_mulai_jabatan ? $row->tgl_mulai_jabatan->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($row->status_aktif)
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Tidak
                                        Aktif</span>
                                @endif
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('desa.bpd.edit', $row) }}"
                                        class="text-primary hover:underline font-medium">Edit</a>
                                    @if($row->status_aktif)
                                        <form action="{{ route('desa.bpd.destroy', $row) }}" method="POST"
                                            class="w-24 text-right"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan bpd ini? (Soft Delete)');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-danger hover:underline font-medium">Nonaktifkan</button>
                                        </form>
                                    @else
                                        <form action="{{ route('desa.bpd.activate', $row) }}" method="POST"
                                            class="w-24 text-right"
                                            onsubmit="return confirm('Apakah Anda yakin ingin mengaktifkan bpd ini kembali?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="text-green-600 hover:underline font-medium">Aktifkan</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-0">
                                <x-empty-state
                                    icon="<path stroke-linecap='round' stroke-linejoin='round' d='M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z' />"
                                    title="Data BPD Kosong"
                                    message="Belum ada bpd desa yang terdaftar. Data master ini umumnya disinkronkan dan dikelola bersama Kabupaten." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bpd->hasPages())
            <div class="px-6 py-4 border-t border-border">{{ $bpd->links() }}</div>
        @endif
    </div>
</x-app-layout>