<x-app-layout>
    @section('title', 'Evaluasi Usulan Siltap')

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('admin.siltap.index') }}"
                    class="text-sm font-medium text-primary hover:underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    Kembali ke Daftar Siltap
                </a>
                <h2 class="text-xl font-display font-bold text-ink mt-2">Evaluasi Usulan Siltap -
                    {{ $siltap->desa->nama_desa }}</h2>
                <span class="text-xs text-muted block mt-1">Periode:
                    {{ date('F', mktime(0, 0, 0, $siltap->bulan, 10)) }} / {{ $siltap->tahun }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-card shadow-sm border border-border p-6 mb-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Berkas
                        Kelengkapan Usulan</h3>

                    <div class="space-y-4 text-xs font-semibold text-ink">
                        <div class="p-3 bg-gray-50/50 rounded border border-border flex items-center justify-between">
                            <span>1. Rekomendasi Camat</span>
                            <a href="{{ asset('storage/' . $siltap->rekomendasi_camat_path) }}" target="_blank"
                                class="text-primary hover:underline font-medium">Lihat Berkas</a>
                        </div>
                        <div class="p-3 bg-gray-50/50 rounded border border-border flex items-center justify-between">
                            <span>2. Bebas Pinjaman & BPJS</span>
                            <a href="{{ asset('storage/' . $siltap->bukti_bpjs_path) }}" target="_blank"
                                class="text-primary hover:underline font-medium">Lihat Berkas</a>
                        </div>
                        <div class="p-3 bg-gray-50/50 rounded border border-border flex items-center justify-between">
                            <span>3. Laporan Pertanggungjawaban (SPJ) Bulan Sblmnya</span>
                            <a href="{{ asset('storage/' . $siltap->spj_path) }}" target="_blank"
                                class="text-primary hover:underline font-medium">Lihat Berkas</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-card shadow-sm border border-border p-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Persetujuan &
                        Terbit SP2D</h3>

                    @if($siltap->status === 'approved')
                        <div class="p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm">
                            <strong class="font-bold block">Status: Disetujui</strong>
                            <p class="text-xs mt-1">Berkas SP2D telah diunggah dan dikirim ke bank.</p>
                            <a href="{{ asset('storage/' . $siltap->sp2d_path) }}" target="_blank"
                                class="mt-3 inline-flex items-center text-xs font-semibold hover:underline">Unduh PDF SP2D
                                →</a>
                        </div>
                    @else
                        <form action="{{ route('admin.siltap.approve', $siltap) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="sp2d"
                                    class="block text-xs font-semibold text-ink uppercase tracking-wider mb-1">Unggah PDF
                                    SP2D Resmi (.pdf)</label>
                                <input type="file" name="sp2d" id="sp2d" required
                                    class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1">
                            </div>
                            <div class="mb-4">
                                <label for="notes"
                                    class="block text-xs font-semibold text-ink uppercase tracking-wider mb-1">Catatan
                                    Evaluasi / Keterangan</label>
                                <textarea name="notes" id="notes" rows="4"
                                    class="w-full text-xs rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                                    placeholder="Berikan catatan persetujuan..."></textarea>
                            </div>

                            <button type="submit"
                                class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors text-sm shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Approve & Terbitkan SP2D
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>