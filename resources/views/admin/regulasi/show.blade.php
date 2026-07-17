<x-app-layout>
    @section('title', 'Tinjau Regulasi')

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('admin.regulasi.index') }}"
                    class="text-sm font-medium text-primary hover:underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    Kembali ke Daftar Regulasi
                </a>
                <h2 class="text-xl font-display font-bold text-ink mt-2">Evaluasi Hukum: {{ $regulasi->judul }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs text-muted">Diajukan oleh: <strong
                            class="text-ink font-medium">{{ $regulasi->desa->nama_desa }}</strong></span>
                    <span class="text-gray-300">•</span>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-primary-soft text-primary capitalize">{{ $regulasi->tipe }}</span>
                </div>
            </div>
            <div>
                @if($regulasi->file_path)
                    <a href="{{ asset('storage/' . $regulasi->file_path) }}" target="_blank"
                        class="inline-flex items-center px-4 py-2 border border-border text-ink hover:bg-gray-50 font-medium rounded-btn transition-colors text-sm shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Unduh Draf Dokumen
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-card shadow-sm border border-border p-6 mb-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Detail
                        Rancangan Regulasi</h3>

                    <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                        <div>
                            <span class="text-muted block text-xs">No. Registrasi Usulan</span>
                            <span class="text-ink font-mono font-bold">{{ $regulasi->no_regulasi }}</span>
                        </div>
                        <div>
                            <span class="text-muted block text-xs">Tanggal Pengajuan</span>
                            <span
                                class="text-ink font-medium">{{ $regulasi->tgl_diajukan ? $regulasi->tgl_diajukan->format('d M Y') : '-' }}</span>
                        </div>
                    </div>

                    <div class="mb-4 text-sm">
                        <span class="text-muted block text-xs">Uraian / Deskripsi Usulan</span>
                        <p class="text-ink mt-1 font-body leading-relaxed">
                            {{ $regulasi->deskripsi ?? 'Tidak ada deskripsi tambahan.' }}</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-card shadow-sm border border-border p-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Fasilitasi &
                        Catatan Hukum</h3>

                    @if($regulasi->status === 'disahkan')
                        <div class="p-4 bg-green-50 text-green-800 rounded-md text-sm mb-4">
                            <strong>Status: Disahkan</strong>
                            <p class="text-xs mt-1">Regulasi ini telah disahkan dan terbit di Lembaran Desa.</p>
                            @if($regulasi->catatan_revisi)
                                <div class="mt-3 p-3 bg-white/70 rounded border border-green-200">
                                    <strong class="text-xs block mb-1">Catatan Akhir Sanksi/Legal Note:</strong>
                                    <p class="text-xs">{{ $regulasi->catatan_revisi }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <form action="{{ route('admin.regulasi.approve', $regulasi) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="catatan_revisi"
                                    class="block text-xs font-semibold text-ink uppercase tracking-wider mb-1">Legal
                                    Drafting Note (Per Pasal)</label>
                                <textarea name="catatan_revisi" id="catatan_revisi" rows="6"
                                    class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                                    placeholder="Tuliskan pasal-pasal revisi jika ada, atau rekomendasi penyelarasan..."></textarea>
                            </div>

                            <button type="submit"
                                class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors text-sm shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Sahkan & Approve Produk Hukum
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>