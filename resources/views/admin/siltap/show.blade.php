<x-app-layout>
    @section('title', 'Tinjau Siltap')

    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
            <a href="{{ route('admin.siltap.index') }}"
                class="text-sm font-medium text-primary hover:underline flex items-center gap-1 mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Daftar
            </a>
            <h2 class="text-xl font-display font-bold text-ink">Siltap: {{ $siltap->desa->nama_desa ?? '-' }} —
                {{ $siltap->nama_bulan }} {{ $siltap->tahun }}</h2>
            <div class="flex items-center gap-4 mt-2 text-sm text-muted">
                <span>📊 Perangkat saat ajuan: <strong
                        class="text-ink">{{ $siltap->jumlah_perangkat_aktif }}</strong></span>
                <span>📊 Perangkat aktif sekarang: <strong
                        class="text-ink">{{ $perangkatAktifSekarang }}</strong></span>
                @if($siltap->jumlah_perangkat_aktif !== $perangkatAktifSekarang)
                    <span class="text-red-600 font-semibold">⚠️ Selisih terdeteksi!</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Dokumen Upload -->
            <div class="lg:col-span-2 bg-white rounded-card shadow-sm border border-border p-6">
                <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Berkas Pengajuan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <span class="text-xs text-muted block mb-2">Rekomendasi Camat</span>
                        @if($siltap->rekomendasi_camat_path)
                            <a href="{{ asset('storage/' . $siltap->rekomendasi_camat_path) }}" target="_blank"
                                class="inline-flex items-center px-3 py-1.5 bg-white border border-border text-primary hover:bg-gray-50 font-medium rounded-btn text-xs">📄
                                Buka PDF</a>
                        @else
                            <span class="text-xs text-red-500">❌ Tidak ada</span>
                        @endif
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <span class="text-xs text-muted block mb-2">Bukti BPJS</span>
                        @if($siltap->bukti_bpjs_path)
                            <a href="{{ asset('storage/' . $siltap->bukti_bpjs_path) }}" target="_blank"
                                class="inline-flex items-center px-3 py-1.5 bg-white border border-border text-primary hover:bg-gray-50 font-medium rounded-btn text-xs">📄
                                Buka PDF</a>
                        @else
                            <span class="text-xs text-red-500">❌ Tidak ada</span>
                        @endif
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <span class="text-xs text-muted block mb-2">SPJ Bulan Lalu</span>
                        @if($siltap->spj_path)
                            <a href="{{ asset('storage/' . $siltap->spj_path) }}" target="_blank"
                                class="inline-flex items-center px-3 py-1.5 bg-white border border-border text-primary hover:bg-gray-50 font-medium rounded-btn text-xs">📄
                                Buka PDF</a>
                        @else
                            <span class="text-xs text-red-500">❌ Tidak ada</span>
                        @endif
                    </div>
                </div>

                <!-- Audit Trail -->
                @if($siltap->verified_at)
                    <div class="mt-6 p-4 bg-gray-50 rounded-md text-xs text-muted border border-border">
                        <strong class="text-ink block mb-1">📝 Audit Trail</strong>
                        Diverifikasi oleh: <strong>{{ $siltap->verifikator->name ?? 'N/A' }}</strong> pada
                        <strong>{{ $siltap->verified_at->format('d M Y H:i') }}</strong>
                        @if($siltap->catatan_verifikator)
                            <p class="mt-1">Catatan: {{ $siltap->catatan_verifikator }}</p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Panel Aksi -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-card shadow-sm border border-border p-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Keputusan</h3>

                    @if($siltap->status === 'menunggu_verifikasi')
                        <!-- Form Setujui -->
                        <form action="{{ route('admin.siltap.verifikasi', $siltap) }}" method="POST"
                            class="mb-4 pb-4 border-b border-gray-200">
                            @csrf
                            <input type="hidden" name="keputusan" value="disetujui">
                            <div class="mb-3">
                                <label class="block text-xs font-semibold text-ink mb-1">Catatan (Opsional)</label>
                                <textarea name="catatan_verifikator" rows="2"
                                    class="w-full text-sm rounded-md border-border shadow-sm"
                                    placeholder="Catatan verifikasi..."></textarea>
                            </div>
                            <button type="submit"
                                class="w-full px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-btn hover:bg-green-700 transition-colors">✅
                                Validasi & Setujui</button>
                        </form>

                        <!-- Form Tolak -->
                        <form action="{{ route('admin.siltap.verifikasi', $siltap) }}" method="POST">
                            @csrf
                            <input type="hidden" name="keputusan" value="ditolak">
                            <div class="mb-3">
                                <label class="block text-xs font-semibold text-ink mb-1">Alasan Penolakan</label>
                                <textarea name="catatan_verifikator" rows="2"
                                    class="w-full text-sm rounded-md border-border shadow-sm"
                                    placeholder="Alasan penolakan..." required></textarea>
                            </div>
                            <button type="submit"
                                class="w-full px-4 py-2 bg-red-50 text-red-700 text-sm font-medium rounded-btn hover:bg-red-100 transition-colors border border-red-200">❌
                                Tolak Pencairan</button>
                        </form>

                    @elseif($siltap->status === 'disetujui')
                        <div class="p-4 bg-green-50 text-green-800 rounded-md text-sm mb-4">
                            <strong>✅ Telah Disetujui</strong>
                        </div>
                        <!-- Tombol Kirim ke BKAD -->
                        <form action="{{ route('admin.siltap.kirim-bkad', $siltap) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full px-4 py-2.5 bg-gray-800 text-white text-sm font-medium rounded-btn hover:bg-gray-900 transition-colors">⚫
                                Kirim ke BKAD/Bank</button>
                        </form>

                    @elseif($siltap->status === 'dikirim_bkad')
                        <div class="p-4 bg-gray-100 text-gray-800 rounded-md text-sm">
                            <strong>⚫ Telah Dikirim ke BKAD</strong>
                            <p class="text-xs mt-1">Data ini sudah diteruskan untuk proses pencairan oleh Bank Daerah.</p>
                        </div>

                    @elseif($siltap->status === 'ditolak')
                        <div class="p-4 bg-red-50 text-red-800 rounded-md text-sm">
                            <strong>❌ Ditolak</strong>
                            @if($siltap->catatan_verifikator)
                            <p class="text-xs mt-1">{{ $siltap->catatan_verifikator }}</p>@endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>