<x-app-layout>
    @section('title', 'Verifikasi Granular: ' . $ajuan->no_registrasi)

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('admin.ajuan.index') }}"
            class="inline-flex items-center text-sm font-medium text-muted hover:text-ink">
            <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar Antrean
        </a>
        <div class="flex items-center gap-3">
            <span class="text-sm font-medium text-muted">Posisi Surat Saat Ini:</span>
            <span
                class="px-3 py-1 rounded bg-surface border border-border font-bold text-ink shadow-sm">{{ $ajuan->posisi_surat ?? 'Front Office (FO)' }}</span>
        </div>
    </div>

    @if(session('success'))
        <div
            class="mb-5 p-4 rounded-card bg-green-50 border border-green-200 text-green-800 flex items-start gap-3 shadow-sm">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-[80vh]">

        {{-- PANEL KIRI: PREVIEW PDF --}}
        <div
            class="lg:col-span-7 bg-surface rounded-card border border-border shadow-sm flex flex-col overflow-hidden h-full">
            <div class="p-4 border-b border-border bg-gray-50 flex items-center justify-between">
                <h3 class="font-display font-semibold text-ink flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Layar Tinjauan Dokumen
                </h3>
                <span id="preview-title" class="text-sm font-medium text-muted truncate max-w-xs">Pilih dokumen di
                    sebelah kanan</span>
            </div>
            <div class="flex-1 bg-gray-200 relative p-2" id="pdf-container">
                <!-- PDF Viewer / Empty State -->
                <div id="pdf-empty-state" class="absolute inset-0 flex flex-col items-center justify-center text-muted">
                    <svg class="w-16 h-16 mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="font-medium">Klik tombol "Lihat" pada tabel di kanan</p>
                </div>
                <iframe id="pdf-iframe" src="" class="w-full h-full rounded shadow-sm border border-gray-300 hidden"
                    frameborder="0"></iframe>
            </div>
        </div>

        {{-- PANEL KANAN: VERIFIKASI GRANULAR & DISPOSISI --}}
        <div class="lg:col-span-5 flex flex-col gap-6 h-full overflow-y-auto pr-2 custom-scrollbar">

            {{-- IDENTITAS DESA --}}
            <div class="bg-primary text-white rounded-card shadow-sm p-5">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <p class="text-xs font-mono text-primary-soft">{{ $ajuan->no_registrasi }}</p>
                        <h2 class="text-xl font-display font-bold">{{ $ajuan->desa->nama_desa }}</h2>
                    </div>
                </div>
                <div class="text-sm border-t border-white/20 pt-3 flex flex-col gap-1">
                    <p><span class="text-primary-soft inline-block w-20">Layanan:</span>
                        {{ $ajuan->jenisLayanan->nama }}</p>
                    <p><span class="text-primary-soft inline-block w-20">Perangkat:</span>
                        {{ $ajuan->perangkatDesa->nama }} ({{ $ajuan->perangkatDesa->jabatan }})</p>
                </div>
            </div>

            {{-- LIST DOKUMEN CHECKLIST --}}
            <div class="bg-surface rounded-card border border-border shadow-sm flex flex-col">
                <div class="px-5 py-4 border-b border-border bg-gray-50">
                    <h3 class="font-display font-semibold text-ink">Verifikasi Syarat Formil</h3>
                </div>

                <div class="divide-y divide-border">
                    @foreach($dokumenList as $item)
                        <div
                            class="p-4 {{ $item->status == 'valid' ? 'bg-green-50/30' : ($item->status == 'kurang' ? 'bg-red-50/30' : '') }}">
                            <div class="flex items-start gap-3">
                                <span class="font-bold text-ink mt-0.5">{{ $item->templateChecklist->urutan }}.</span>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-ink leading-tight mb-2">
                                        {{ $item->templateChecklist->nama_dokumen }}
                                        @if($item->templateChecklist->wajib) <span class="text-danger">*</span> @endif
                                    </p>

                                    @if($item->file_path)
                                        <div class="flex items-center gap-2 mb-3">
                                            <button
                                                onclick="previewPdf('{{ Storage::disk('public')->url($item->file_path) }}', '{{ addslashes($item->templateChecklist->nama_dokumen) }}')"
                                                class="inline-flex items-center text-xs px-3 py-1 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded font-medium text-ink transition-colors">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Lihat PDF
                                            </button>

                                            <span
                                                class="text-xs font-semibold px-2 py-0.5 rounded {{ $item->status == 'valid' ? 'bg-green-100 text-green-700' : ($item->status == 'menunggu' ? 'bg-gray-100 text-gray-600' : 'bg-red-100 text-red-700') }}">
                                                Status: {{ strtoupper($item->status) }}
                                            </span>
                                        </div>

                                        <form action="{{ route('admin.ajuan.verify', [$ajuan, $item]) }}" method="POST"
                                            class="bg-white p-3 border border-border rounded shadow-sm text-sm">
                                            @csrf
                                            <div class="flex items-center gap-4 mb-2">
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="status" value="valid"
                                                        class="text-success focus:ring-success" {{ $item->status == 'valid' ? 'checked' : '' }} onclick="toggleCatatan(this, {{ $item->id }})">
                                                    <span class="ml-2 font-medium">Valid (Sesuai)</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="status" value="kurang"
                                                        class="text-danger focus:ring-danger" {{ in_array($item->status, ['kurang', 'tidak_sesuai']) ? 'checked' : '' }}
                                                        onclick="toggleCatatan(this, {{ $item->id }})">
                                                    <span class="ml-2 font-medium">Tolak / Revisi</span>
                                                </label>
                                            </div>
                                            <div id="catatan-box-{{ $item->id }}"
                                                class="{{ in_array($item->status, ['kurang', 'tidak_sesuai']) ? 'block' : 'hidden' }}">
                                                <textarea name="catatan"
                                                    class="w-full text-xs rounded border-gray-300 focus:border-red-500 focus:ring-red-500 mb-2"
                                                    rows="2"
                                                    placeholder="Tulis catatan perbaikan untuk desa...">{{ $item->catatan }}</textarea>
                                            </div>
                                            <button type="submit"
                                                class="w-full py-1.5 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded text-xs font-semibold transition-colors">
                                                Simpan Keputusan Ceklis
                                            </button>
                                        </form>
                                    @else
                                        <span
                                            class="inline-block px-2 py-1 bg-gray-100 text-gray-500 text-xs font-medium rounded border border-gray-200 mt-1">Belum
                                            Terunggah</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- PANEL UPDATE DISPOSISI SURAT --}}
            <div class="bg-surface rounded-card border border-border shadow-sm mb-10">
                <div class="px-5 py-4 border-b border-border bg-primary-soft/10">
                    <h3 class="font-display font-semibold text-primary">Tindak Lanjut & Disposisi</h3>
                </div>
                <form action="{{ route('admin.ajuan.disposisi', $ajuan) }}" method="POST"
                    class="p-5 flex flex-col gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-ink mb-1">Maju ke Meja Birokrasi / Status</label>
                        <select name="posisi_baru"
                            class="w-full text-sm border-gray-300 rounded-md focus:border-primary focus:ring focus:ring-primary/20">
                            <option value="Front Office (FO)" {{ $ajuan->posisi_surat == 'Front Office (FO)' ? 'selected' : '' }}>Kembalikan ke Front Office</option>
                            <option value="Kabid PDPD" {{ $ajuan->posisi_surat == 'Kabid PDPD' ? 'selected' : '' }}>Maju
                                ke Kabid PDPD</option>
                            <option value="Sekretaris Dinas" {{ $ajuan->posisi_surat == 'Sekretaris Dinas' ? 'selected' : '' }}>Maju ke Sekretaris Dinas</option>
                            <option value="Kepala Dinas" {{ $ajuan->posisi_surat == 'Kepala Dinas' ? 'selected' : '' }}>
                                Maju ke Kepala Dinas (Rekomendasi Dinas Terbit)</option>
                            <option value="Asisten Setda / Sekda" {{ $ajuan->posisi_surat == 'Asisten Setda / Sekda' ? 'selected' : '' }}>Maju ke Binawil / Setda</option>
                            <option value="Bupati" {{ $ajuan->posisi_surat == 'Bupati' ? 'selected' : '' }}>Maju ke meja
                                Bupati</option>
                            <option value="Selesai (Surat Terbit)" {{ $ajuan->posisi_surat == 'Selesai (Surat Terbit)' ? 'selected' : '' }}>Selesai (Surat SK/Rekomendasi Terbit)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ink mb-1">Ubah Status Keseluruhan Ajuan
                            (Opsional)</label>
                        <select name="status_ajuan_baru" class="w-full text-sm border-gray-300 rounded-md">
                            <option value="">-- Tetap ({{ $ajuan->status }}) --</option>
                            <option value="diproses">Dalam Proses (Validasi Berjalan)</option>
                            <option value="direvisi">Kembalikan ke Desa (Butuh Revisi)</option>
                            <option value="selesai">Selesai Berhasil</option>
                            <option value="ditolak">Ditolak Permanen</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ink mb-1">Catatan Disposisi (Opsional)</label>
                        <textarea name="catatan_milestone" rows="2" class="w-full text-sm border-gray-300 rounded-md"
                            placeholder="Contoh: Berkas sudah lengkap, mohon arahan Bapak Kadis."></textarea>
                    </div>

                    <button type="submit"
                        class="w-full py-2.5 bg-primary rounded-btn text-white text-sm font-medium hover:bg-primary-light transition-colors flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                        Eksekusi Disposisi
                    </button>
                </form>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function toggleCatatan(radio, id) {
                const box = document.getElementById('catatan-box-' + id);
                if (radio.value === 'kurang') {
                    box.classList.remove('hidden');
                    box.classList.add('block');
                } else {
                    box.classList.remove('block');
                    box.classList.add('hidden');
                }
            }

            function previewPdf(url, title) {
                document.getElementById('pdf-empty-state').classList.add('hidden');
                const iframe = document.getElementById('pdf-iframe');
                iframe.src = url;
                iframe.classList.remove('hidden');
                document.getElementById('preview-title').innerText = title;
            }
        </script>
        <style>
            .custom-scrollbar::-webkit-scrollbar {
                width: 6px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background-color: #cbd5e1;
                border-radius: 20px;
            }
        </style>
    @endpush
</x-app-layout>