<x-app-layout>
    @section('title', 'Data BPD')
    <div x-data="{ tab: '{{ request()->has('pending_page') ? 'verifikasi' : 'data' }}' }">
        <!-- Tabs Nav -->
        <div class="border-b border-border mb-6">
            <nav class="flex space-x-8" aria-label="Tabs">
                <button @click="tab = 'data'" 
                        :class="tab === 'data' ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-ink hover:border-gray-300'"
                        class="border-b-2 py-4 px-1 text-sm font-semibold transition-colors">
                    Semua Data
                </button>
                <button @click="tab = 'verifikasi'" 
                        :class="tab === 'verifikasi' ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-ink hover:border-gray-300'"
                        class="border-b-2 py-4 px-1 text-sm font-semibold transition-colors relative flex items-center">
                    Verifikasi Usulan
                    @if($pending->total() > 0)
                        <span class="relative flex h-2.5 w-2.5 ml-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                        </span>
                    @endif
                </button>
            </nav>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-lg border border-green-200 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tab Content: Data BPD -->
        <div x-show="tab === 'data'" style="display: none;" x-transition>
            <div class="mb-6 flex justify-end">
                <form action="{{ route('admin.bpd.index') }}" method="GET" class="w-full md:flex-1">
                    <div class="relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="material-symbols-outlined text-gray-400 text-[20px]">search</span>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, jabatan, desa..."
                            class="block w-full rounded-md border-0 py-2 pl-10 pr-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6">
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($bpds as $p)
                    <div class="bg-white rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] p-6 flex flex-col hover:shadow-[0_8px_25px_-5px_rgba(0,0,0,0.08)] transition-shadow border border-slate-100">
                        <div class="flex justify-between items-start mb-8">
                            <div class="w-[72px] h-[72px] rounded-full bg-slate-200 flex flex-shrink-0 items-center justify-center overflow-hidden">
                                <span class="material-symbols-outlined text-slate-400 text-[36px]">person</span>
                            </div>
                            @if($p->status_aktif)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-600 border border-green-200/80">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-200/80">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2"></span> Nonaktif
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex-1 flex flex-col justify-end">
                            <h3 class="text-[17px] font-black text-slate-800 leading-snug uppercase mb-1.5">{{ $p->nama }}</h3>
                            <p class="text-[14px] text-slate-600">{{ $p->jabatan }}</p>
                            <p class="text-[14px] text-slate-600">{{ $p->desa->nama_desa ?? 'Desa' }}, {{ $p->desa->kecamatan->nama_kecamatan ?? 'Kecamatan' }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="bg-white rounded-card shadow-sm border border-border p-8 text-center">
                            <span class="material-symbols-outlined text-slate-300 text-5xl mb-3 block">group_off</span>
                            <h3 class="text-lg font-bold text-slate-900 mb-1">Data BPD Kosong</h3>
                            <p class="text-slate-500">Belum ada BPD yang terdaftar.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $bpds->links() }}
            </div>
        </div>

        <!-- Tab Content: Verifikasi Usulan -->
        <div x-show="tab === 'verifikasi'" style="display: none;" x-transition>
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
                                            <form action="{{ route('admin.bpd.approve', $item->id) }}" method="POST" onsubmit="return confirm('Setujui usulan ini?');">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-xs font-bold rounded hover:bg-green-700">Setujui</button>
                                            </form>
                                            <form action="{{ route('admin.bpd.reject', $item->id) }}" method="POST" onsubmit="return confirm('Tolak usulan ini?');">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded hover:bg-red-700">Tolak</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-muted">
                                        Tidak ada usulan BPD yang menunggu verifikasi.
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
        </div>
    </div>
</x-app-layout>
