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
                            {{ $regulasi->deskripsi ?? 'Tidak ada deskripsi tambahan.' }}
                        </p>
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
                            <p class="text-xs mt-1">Regulasi ini terbit di Lembaran Desa.</p>
                            @if($regulasi->catatan_revisi)
                                <div class="mt-3 p-3 bg-white/70 rounded border border-green-200">
                                    <strong class="text-xs block mb-1">Catatan Akhir Sanksi/Legal Note:</strong>
                                    <p class="text-xs">{{ $regulasi->catatan_revisi }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Form Kembalikan ke Desa (Revisi) -->
                        <form action="{{ route('admin.regulasi.kembalikan', $regulasi) }}" method="POST"
                            enctype="multipart/form-data" class="mb-6 pb-6 border-b border-gray-200">
                            @csrf
                            <h4 class="text-sm font-semibold text-red-600 mb-3 block">🔴 Opsi 1: Kembalikan untuk Revisi
                            </h4>
                            <div class="mb-3">
                                <label for="file_catatan_dinas" class="block text-xs font-semibold text-ink mb-1">Unggah
                                    Draf Coretan (.doc/.docx)</label>
                                <input type="file" name="file_catatan_dinas" id="file_catatan_dinas"
                                    class="w-full text-xs box-border rounded-md border-border" accept=".doc,.docx" required>
                            </div>
                            <div class="mb-3">
                                <label for="catatan" class="block text-xs font-semibold text-ink mb-1">Legal Drafting Note
                                    Singkat</label>
                                <textarea name="catatan" id="catatan" rows="3"
                                    class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                                    placeholder="Tulis instruksi revisi..." required></textarea>
                            </div>

                            <button type="submit"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-50 text-red-700 hover:bg-red-100 font-medium rounded-btn transition-colors text-sm shadow-sm border border-red-200">
                                Kirim Revisi ke Desa
                            </button>
                        </form>

                        <!-- Form Sahkan & Terbitkan Nomor -->
                        <form action="{{ route('admin.regulasi.sahkan', $regulasi) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <h4 class="text-sm font-semibold text-green-600 mb-3 block">🟢 Opsi 2: Sahkan Menjadi Aturan
                            </h4>

                            <div class="mb-3">
                                <label for="no_regulasi" class="block text-xs font-semibold text-ink mb-1">Nomor Registrasi
                                    (Lembaran Desa)</label>
                                <input type="text" name="no_regulasi" id="no_regulasi" required
                                    class="w-full text-xs font-mono box-border rounded-md border-border shadow-sm placeholder:text-gray-300"
                                    placeholder="Contoh: PRD/2026/08/001">
                            </div>

                            <div class="mb-4">
                                <label for="file_final" class="block text-xs font-semibold text-ink mb-1">Unggah PDF Final
                                    Paripurna (Opsional)</label>
                                <input type="file" name="file_final" id="file_final"
                                    class="w-full text-xs box-border rounded-md border-border" accept=".pdf">
                            </div>

                            <button type="submit"
                                class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-green-600 text-white font-medium rounded-btn hover:bg-green-700 transition-colors text-sm shadow-sm">
                                Approve & Sahkan Aturan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>