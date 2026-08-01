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
        <form method="GET" action="{{ route('desa.bpd.index') }}" class="w-full md:flex-1 relative">
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
    <div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($bpd as $row)
                <div class="bg-white rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] p-6 flex flex-col hover:shadow-[0_8px_25px_-5px_rgba(0,0,0,0.08)] transition-shadow border border-slate-100">
                    <div class="flex justify-between items-start mb-8">
                        <div class="w-[72px] h-[72px] rounded-full bg-slate-200 flex flex-shrink-0 items-center justify-center overflow-hidden">
                            <svg class="w-12 h-12 text-slate-400 mt-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            @if($row->status_aktif)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-600 border border-green-200/80">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-200/80">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2"></span> Nonaktif
                                </span>
                            @endif

                            @if(str_starts_with($row->status_verifikasi, 'pending'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200 uppercase">
                                    Menunggu Verifikasi
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex-1 flex flex-col justify-end">
                        <h3 class="text-[17px] font-black text-slate-800 leading-snug uppercase mb-1.5">{{ $row->nama }}</h3>
                        <p class="text-[14px] text-slate-600">{{ $row->jabatan }}</p>
                        <p class="text-[14px] text-slate-600">{{ $row->desa->nama_desa ?? auth()->user()->desa->nama_desa ?? 'Desa' }}, {{ $row->desa->kecamatan->nama_kecamatan ?? auth()->user()->desa->kecamatan->nama_kecamatan ?? 'Kecamatan' }}</p>
                    </div>

                    <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-end gap-4">
                        <a href="{{ route('desa.bpd.edit', $row) }}" class="text-[13px] font-bold text-blue-600 hover:text-blue-700">Edit</a>
                        @if($row->status_aktif)
                            <form action="{{ route('desa.bpd.destroy', $row) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan BPD ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[13px] font-bold text-red-600 hover:text-red-700">Nonaktifkan</button>
                            </form>
                        @else
                            <form action="{{ route('desa.bpd.activate', $row) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengaktifkan kembali BPD ini?');">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-[13px] font-bold text-green-600 hover:text-green-700">Aktifkan</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-card shadow-sm border border-border p-8">
                        <x-empty-state
                            icon="<path stroke-linecap='round' stroke-linejoin='round' d='M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z' />"
                            title="Data BPD Kosong"
                            message="Belum ada anggota BPD yang terdaftar." />
                    </div>
                </div>
            @endforelse
        </div>
        @if($bpd->hasPages())
            <div class="mt-6">{{ $bpd->links() }}</div>
        @endif
    </div>
</x-app-layout>