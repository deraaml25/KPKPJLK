<x-app-layout>
    @section('title', 'e-Bimtek')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <div>
            <h2 class="text-xl font-display font-bold text-ink">e-Bimtek Pembinaan</h2>
            <p class="text-muted text-sm mt-1">Peningkatan kapasitas aparatur desa, pendaftaran bimtek daring, serta
                kepatuhan pelaporan Rencana Tindak Lanjut (RTL).</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Upcoming Classes -->
        <div class="lg:col-span-2 space-y-6">
            <h3 class="text-lg font-display font-bold text-ink">Jadwal Kelas Pembinaan Tersedia</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse ($bimteks as $bim)
                    <div class="bg-white rounded-card p-6 border border-border shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary-soft text-primary">Siskeudes/Sipades</span>
                                <span class="text-xs text-muted font-bold font-mono">Sisa Kuota:
                                    {{ $bim->sisa_kuota }}/{{ $bim->kuota }}</span>
                            </div>
                            <h4 class="text-md font-display font-bold text-ink mb-1">{{ $bim->judul }}</h4>
                            <p class="text-xs text-muted line-clamp-3 mb-4">{{ $bim->deskripsi }}</p>

                            <div class="space-y-1 mb-4 text-xs text-ink font-medium">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    {{ $bim->tanggal_pelaksanaan ? $bim->tanggal_pelaksanaan->format('d M Y') : '-' }}
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $bim->tempat ?? 'Daring' }}
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('desa.bimtek.daftar', $bim) }}" method="POST">
                            @csrf
                            <button type="submit" {{ $bim->sisa_kuota <= 0 ? 'disabled' : '' }}
                                class="w-full text-center px-4 py-2 bg-primary text-white font-medium text-xs rounded-btn hover:bg-primary-light transition-colors disabled:bg-gray-100 disabled:text-gray-400">
                                {{ $bim->sisa_kuota <= 0 ? 'Kuota Penuh' : 'Daftar Kelas' }}
                            </button>
                        </form>
                    </div>
                @empty
                    <div
                        class="col-span-2 p-6 bg-white border border border-border text-center rounded-card text-muted text-sm">
                        Tidak ada kelas pembinaan terdekat yang tersedia.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- My Classes / RTL Tracker -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-card border border-border p-6 shadow-sm">
                <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Kelas Diikuti & RTL
                </h3>

                <div class="space-y-4">
                    @forelse ($myPendaftarans as $pendaftaran)
                        <div class="p-4 bg-gray-50/50 rounded-md border border-border text-xs">
                            <h4 class="font-bold text-ink mb-1 font-display">{{ $pendaftaran->bimtek->judul }}</h4>
                            <div class="flex items-center gap-1.5 text-muted mb-3">
                                <span>Presensi:</span>
                                @if($pendaftaran->status_presensi === 'hadir')
                                    <span class="text-green-800 font-bold">Hadir</span>
                                @else
                                    <span class="text-red-800 font-bold">Belum Presensi / Absen</span>
                                @endif
                            </div>

                            @if($pendaftaran->file_rtl)
                                <div
                                    class="bg-green-50 p-2.5 rounded border border-green-200 text-green-800 flex items-center justify-between">
                                    <span>RTL Terunggah</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                        </path>
                                    </svg>
                                </div>
                            @else
                                <form action="{{ route('desa.bimtek.upload-rtl', $pendaftaran) }}" method="POST"
                                    enctype="multipart/form-data" class="mt-2 space-y-2">
                                    @csrf
                                    <label class="block font-semibold text-gray-700">Unggah Bukti Dokumen RTL
                                        (PDF/DOCX):</label>
                                    <input type="file" name="file_rtl" required
                                        class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1">
                                    <button type="submit"
                                        class="w-full text-center px-3 py-1.5 bg-primary text-white font-medium rounded hover:bg-primary-light transition-colors">
                                        Upload RTL
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-sm text-muted py-6">Anda belum mendaftar di kelas pembinaan manapun.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>