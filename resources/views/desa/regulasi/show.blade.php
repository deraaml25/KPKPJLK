<x-app-layout>
    @section('title', 'Detail & Revisi Regulasi')

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('desa.regulasi.index') }}"
                    class="text-sm font-medium text-primary hover:underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    Kembali ke Daftar Regulasi
                </a>
                <h2 class="text-xl font-display font-bold text-ink mt-2">Usulan: {{ $regulasi->judul }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-primary-soft text-primary capitalize">{{ $regulasi->tipe }}</span>
                </div>
            </div>
            <div>
                @if($regulasi->status === 'disahkan')
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">✅
                        Telah Disahkan ({{ $regulasi->no_regulasi }})</span>
                @elseif($regulasi->status === 'perlu_revisi')
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">⚠️
                        Butuh Revisi</span>
                @elseif($regulasi->status === 'evaluasi_lanjutan')
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">🟡
                        Evaluasi Lanjutan</span>
                @else
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">🔵
                        Menunggu Evaluasi</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Sisi Informasi & Draf Awal -->
            <div class="space-y-6">
                <!-- Info Status -->
                <div class="bg-white rounded-card shadow-sm border border-border p-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Riwayat Draf
                        Anda</h3>
                    <div class="mb-4">
                        <span class="text-xs text-muted block mb-1">Draf Usulan Awal (.doc/.docx)</span>
                        <a href="{{ asset('storage/' . $regulasi->file_path) }}" target="_blank"
                            class="inline-flex items-center text-primary text-sm hover:underline font-medium">📄 Unduh
                            Draf Terkirim</a>
                    </div>
                </div>

                @if($regulasi->status === 'disahkan')
                    <!-- Bukti Sah -->
                    <div class="bg-green-50 rounded-card shadow-sm border border-green-200 p-6">
                        <h3 class="text-md font-display font-bold text-green-900 mb-2">🎉 Selamat, Regulasi Sah!</h3>
                        <p class="text-sm text-green-800 mb-4">Produk hukum ini telah diregistrasi resmi oleh Dinpermasdes
                            dan tercatat di Lembaran Desa.</p>
                        <div
                            class="bg-white p-3 rounded text-sm text-ink mb-3 font-mono font-bold text-center border border-green-200">
                            {{ $regulasi->no_regulasi }}
                        </div>
                        @if($regulasi->file_pdf)
                            <a href="{{ asset('storage/' . $regulasi->file_pdf) }}" target="_blank"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 text-white font-medium rounded-btn hover:bg-green-700 transition-colors text-sm shadow-sm">📥
                                Unduh PDF Final Paripurna</a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sisi Evaluasi & Aksi Revisi -->
            <div class="">
                @if($regulasi->status === 'perlu_revisi')
                    <div class="bg-red-50 rounded-card shadow-sm border border-red-200 p-6">
                        <h3 class="text-md font-display font-bold text-red-900 mb-4 pb-2 border-b border-red-200">Evaluasi
                            Dinas (Penting)</h3>

                        <div class="mb-4">
                            <span class="text-xs text-red-700 block mb-1 font-semibold">Tindak Lanjut / Catatan
                                Hukum:</span>
                            <div class="bg-white p-3 rounded text-sm text-ink border border-red-100">
                                {{ $regulasi->catatan_revisi }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <span class="text-xs text-red-700 block mb-1 font-semibold">Dokumentasi Coretan Dinas (Legal
                                Drafting Note):</span>
                            <a href="{{ asset('storage/' . $regulasi->file_catatan_dinas) }}" target="_blank"
                                class="inline-flex items-center px-4 py-2 bg-white border border-red-200 text-red-700 hover:bg-red-100 font-medium rounded-btn transition-colors text-sm">
                                📝 Unduh Dokumen Catatan (.docx)
                            </a>
                        </div>

                        <hr class="border-red-200 my-5">

                        <form action="{{ route('desa.regulasi.kirim-revisi', $regulasi) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <h4 class="text-sm font-semibold text-ink mb-3">Upload Perbaikan Final</h4>
                            <div class="mb-3">
                                <label for="file_revisi" class="block text-xs font-medium text-ink mb-1">Upload Draf Hasil
                                    Perbaikan (Wajib .doc/.docx)</label>
                                <input type="file" name="file_revisi" id="file_revisi"
                                    class="w-full text-xs box-border rounded-md border-border bg-white" accept=".doc,.docx"
                                    required>
                            </div>
                            <div class="mb-5">
                                <label for="file_pdf_sah" class="block text-xs font-medium text-ink mb-1">Upload Bukti TTD
                                    Fisik (Opsional .pdf)</label>
                                <input type="file" name="file_pdf_sah" id="file_pdf_sah"
                                    class="w-full text-xs box-border rounded-md border-border bg-white" accept=".pdf">
                            </div>
                            <button type="submit"
                                class="w-full px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors text-sm shadow-sm">
                                Laporkan Selesai & Kirim Ulang
                            </button>
                        </form>
                    </div>
                @elseif($regulasi->status === 'evaluasi_lanjutan')
                    <div class="bg-yellow-50 rounded-card shadow-sm border border-yellow-200 p-6">
                        <h3 class="text-md font-display font-bold text-yellow-900 mb-2">Evaluasi Lanjutan Aktif</h3>
                        <p class="text-sm text-yellow-800">Anda telah mengirimkan dokumen perbaikan ke Dinpermasdes. Silakan
                            tunggu hingga status berubah menjadi <b>Disahkan</b> atau jika ada umpan balik lebih lanjut.</p>
                    </div>
                @else
                    <!-- Jika sedang menunggu review atau diajukan -->
                    <div class="bg-gray-50 rounded-card shadow-sm border border-gray-200 p-6 text-center text-muted">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-md font-semibold text-ink line-clamp-2">Sedang dievaluasi oleh Dinpermasdes</h3>
                        <p class="text-sm mt-1">Belum ada catatan resmi yang dilaporkan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>