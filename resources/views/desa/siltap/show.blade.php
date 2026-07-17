<x-app-layout>
    @section('title', 'Detail Usulan Siltap')

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('desa.siltap.index') }}"
                    class="text-sm font-medium text-primary hover:underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    Kembali ke Daftar Siltap
                </a>
                <h2 class="text-xl font-display font-bold text-ink mt-2">Detail Usulan Siltap:
                    {{ date('F', mktime(0, 0, 0, $siltap->bulan, 10)) }} / {{ $siltap->tahun }}</h2>
                <span class="text-xs text-muted block mt-1">Diajukan pada:
                    {{ $siltap->created_at->format('d M Y H:i') }}</span>
            </div>
            <div>
                @if($siltap->sp2d_path)
                    <a href="{{ asset('storage/' . $siltap->sp2d_path) }}" target="_blank"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-btn hover:bg-green-700 transition-colors text-sm shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Unduh Dokumen SP2D.pdf
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-card shadow-sm border border-border p-6 mb-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Berkas
                        Kelengkapan Pencairan</h3>

                    <div class="space-y-4 text-xs font-semibold text-ink">
                        <div class="p-3 bg-gray-50/50 rounded border border-border flex items-center justify-between">
                            <span class="font-bold">1. Rekomendasi Camat</span>
                            <a href="{{ asset('storage/' . $siltap->rekomendasi_camat_path) }}" target="_blank"
                                class="text-primary hover:underline flex items-center gap-1 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                Lihat Berkas
                            </a>
                        </div>
                        <div class="p-3 bg-gray-50/50 rounded border border-border flex items-center justify-between">
                            <span class="font-bold">2. Bebas Pinjaman & BPJS</span>
                            <a href="{{ asset('storage/' . $siltap->bukti_bpjs_path) }}" target="_blank"
                                class="text-primary hover:underline flex items-center gap-1 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                Lihat Berkas
                            </a>
                        </div>
                        <div class="p-3 bg-gray-50/50 rounded border border-border flex items-center justify-between">
                            <span class="font-bold">3. Laporan Pertanggungjawaban (SPJ) Bulan Sblmnya</span>
                            <a href="{{ asset('storage/' . $siltap->spj_path) }}" target="_blank"
                                class="text-primary hover:underline flex items-center gap-1 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                Lihat Berkas
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-card shadow-sm border border-border p-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Status Evaluasi
                    </h3>

                    @if($siltap->status === 'approved')
                        <div class="p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm">
                            <strong class="font-bold block">Status: Disetujui (SP2D Terbit)</strong>
                            <p class="text-xs mt-1">Pencairan Siltap disetujui. Berkas SP2D sudah diterbitkan dan dikirim ke
                                bank penyalur.</p>
                            @if($siltap->notes)
                                <div class="mt-3 p-3 bg-white/70 rounded border border-green-200 text-xs">
                                    <strong>Catatan Dinas:</strong>
                                    <p class="mt-0.5">{{ $siltap->notes }}</p>
                                </div>
                            @endif
                        </div>
                    @elseif($siltap->status === 'rejected')
                        <div class="p-4 bg-red-50 text-red-800 rounded border border-red-200 text-sm">
                            <strong class="font-bold block">Status: Ditolak / Draf Dikembalikan</strong>
                            <p class="text-xs mt-1">Berkas usulan ditolak atau dikembalikan karena data tidak cocok / tidak
                                lengkap.</p>
                            @if($siltap->notes)
                                <div class="mt-3 p-3 bg-white/70 rounded border border-red-200 text-xs">
                                    <strong>Alasan Penolakan:</strong>
                                    <p class="mt-0.5">{{ $siltap->notes }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="p-4 bg-blue-50 text-blue-800 rounded border border-blue-200 text-sm">
                            <strong class="font-bold block">Status: Tahap Evaluasi Dinas</strong>
                            <p class="text-xs mt-1">Usulan Anda sedang diperiksa kelengkapannya oleh dinas teknis terkait.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>