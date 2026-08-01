<x-app-layout>
    @section('title', 'Verifikasi Ajuan BPD: ' . $ajuanBpd->no_registrasi)

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('admin.ajuan-bpd.index') }}"
            class="inline-flex items-center text-sm font-medium text-muted hover:text-ink">
            <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar Antrean
        </a>
        <div class="flex items-center gap-3">
            <span class="text-sm font-medium text-muted">Status Saat Ini:</span>
            <span
                class="px-3 py-1 rounded bg-surface border border-border font-bold text-ink shadow-sm">{{ strtoupper($ajuanBpd->status) }}</span>
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

    <div class="{{ $ajuanBpd->metode !== 'offline' ? 'grid grid-cols-1 lg:grid-cols-12 gap-6' : 'max-w-4xl mx-auto' }} h-[80vh]">

        @if($ajuanBpd->metode !== 'offline')
        {{-- PANEL KIRI: PREVIEW PDF --}}
        <div
            class="lg:col-span-7 bg-surface rounded-card border border-border shadow-sm flex flex-col overflow-hidden h-full">
            <div class="px-4 py-3 border-b border-border bg-gray-50 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-ink">Berkas Keseluruhan Persyaratan</p>
                    <p class="text-xs text-muted">Ajuan BPD ({{ ucfirst($ajuanBpd->jenis_ajuan) }}) — {{ $ajuanBpd->desa->nama_desa }}</p>
                </div>
                <span id="preview-title" class="text-xs text-muted hidden"></span>
            </div>
            <div class="flex-1 bg-gray-200 relative p-2" id="pdf-container">
                <!-- PDF Viewer / Empty State -->
                <div id="pdf-empty-state" class="absolute inset-0 flex flex-col items-center justify-center text-muted">
                    <svg class="w-16 h-16 mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="font-medium">Klik tombol "Lihat PDF" pada tabel di kanan</p>
                </div>
                <iframe id="pdf-iframe" src="" class="w-full h-full rounded shadow-sm border border-gray-300 hidden" frameborder="0"></iframe>
                <img id="img-preview" src="" class="w-full h-full object-contain rounded shadow-sm border border-gray-300 hidden">
            </div>
        </div>
        @endif

        {{-- PANEL KANAN: VERIFIKASI GRANULAR & DISPOSISI --}}
        <div class="{{ $ajuanBpd->metode !== 'offline' ? 'lg:col-span-5' : 'w-full' }} flex flex-col gap-6 h-full overflow-y-auto pr-2 custom-scrollbar">

            {{-- IDENTITAS DESA --}}
            <div class="bg-primary text-white rounded-card shadow-sm p-4">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-[10px] font-mono text-primary-soft">{{ $ajuanBpd->no_registrasi }}</p>
                        <h2 class="text-lg font-display font-bold leading-tight">{{ $ajuanBpd->desa->nama_desa }}</h2>
                    </div>
                </div>
                <div class="text-xs border-t border-white/20 pt-2 flex flex-col gap-1">
                    <p><span class="text-primary-soft inline-block w-16">Layanan:</span>
                        Ajuan BPD ({{ ucfirst($ajuanBpd->jenis_ajuan) }})</p>

                    <p class="text-primary-soft font-medium mt-1">Daftar BPD ({{ $ajuanBpd->pesertas->count() }} Orang):</p>
                    <div class="max-h-20 overflow-y-auto custom-scrollbar space-y-1.5 pr-1">
                        @foreach($ajuanBpd->pesertas as $index => $peserta)
                            <div class="bg-black/10 rounded p-1.5 border border-white/5 text-[10px]">
                                <span class="font-bold block">{{ $index + 1 }}. {{ $peserta->bpd->nama }}</span>
                                <span class="opacity-80 block">{{ $peserta->bpd->jabatan }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- LIST DOKUMEN CHECKLIST --}}
            <div class="bg-surface rounded-card border border-border shadow-sm flex flex-col">
                <div class="px-5 py-4 border-b border-border bg-gray-50 flex justify-between items-center">
                    <h3 class="font-display font-semibold text-ink">Verifikasi Syarat</h3>
                    <a href="{{ route('admin.ajuan-bpd.print-syarat', $ajuanBpd->id) }}" target="_blank" class="inline-flex items-center text-xs px-2 py-1 bg-white border border-gray-300 rounded font-medium text-ink hover:bg-gray-50 transition-colors shadow-sm">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Print Checklist
                    </a>
                </div>

                <div class="divide-y divide-border">
                    @foreach($ajuanBpd->checklists->sortBy('templateChecklist.urutan') as $item)
                        <div class="p-4 border-l-4 transition-colors {{ $item->status == 'terverifikasi' ? 'border-green-500 bg-green-50/40' : ($item->status == 'ditolak' ? 'border-red-500 bg-red-50/40' : 'border-amber-400 bg-amber-50/30') }}">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-white text-xs font-bold text-ink border border-border shadow-sm flex-shrink-0">{{ $item->templateChecklist->urutan }}</span>
                                <div class="flex-1 flex flex-col sm:flex-row sm:items-center gap-2 justify-between">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <p class="text-sm font-semibold text-ink leading-tight">
                                            {{ $item->templateChecklist->nama_dokumen }}
                                        </p>
                                        @if($item->templateChecklist->wajib)
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-red-700">Wajib</span>
                                        @endif

                                        @if($item->file_path)
                                            <button
                                                onclick="previewFile('{{ Storage::disk('public')->url($item->file_path) }}', '{{ addslashes($item->templateChecklist->nama_dokumen) }}')"
                                                class="ml-2 inline-flex items-center text-xs px-2 py-1 bg-white hover:bg-gray-50 border border-gray-300 rounded font-medium text-ink transition-colors shadow-sm">
                                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Lihat Berkas
                                            </button>
                                        @else
                                            <span class="ml-2 inline-block px-2 py-1 bg-gray-100 text-gray-500 text-xs font-medium rounded border border-gray-200">Belum Terunggah</span>
                                        @endif
                                    </div>

                                    @if($item->file_path)
                                    <form class="bpd-verify-form flex flex-col gap-1 mt-2 sm:mt-0 w-full sm:w-auto" data-url="{{ route('admin.ajuan-bpd.verify-checklist', [$ajuanBpd->id, $item->id]) }}">
                                        @csrf
                                        <div class="flex items-center gap-2 mb-1">
                                            <label class="flex items-center gap-1 text-[10px] cursor-pointer">
                                                <input type="radio" name="status" value="terverifikasi" class="bpd-verify-input text-green-600 focus:ring-green-600 w-3 h-3" {{ $item->status === 'terverifikasi' ? 'checked' : '' }} data-last-status="{{ $item->status }}"> Valid
                                            </label>
                                            <label class="flex items-center gap-1 text-[10px] cursor-pointer">
                                                <input type="radio" name="status" value="ditolak" class="bpd-verify-input text-red-600 focus:ring-red-600 w-3 h-3" {{ $item->status === 'ditolak' ? 'checked' : '' }} data-last-status="{{ $item->status }}"> Tolak
                                            </label>
                                        </div>
                                        <input type="text" name="catatan" value="{{ $item->catatan }}" placeholder="Catatan opsional..." class="bpd-verify-input text-[10px] rounded border-gray-300 w-full">
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- PANEL CATATAN ADMIN --}}
            <div class="bg-surface rounded-card border border-border shadow-sm flex flex-col mb-2">
                <div class="px-5 py-4 border-b border-border bg-gray-50 flex justify-between items-center">
                    <h3 class="font-display font-semibold text-ink">Catatan Perbaikan Desa</h3>
                </div>
                <form action="{{ route('admin.ajuan-bpd.catatan', $ajuanBpd->id) }}" method="POST" class="p-5">
                    @csrf
                    <label class="block text-sm font-medium text-ink mb-2">Catatan Kelengkapan dari Admin untuk Desa</label>
                    <textarea name="catatan_admin" rows="3" class="w-full text-sm border-gray-300 rounded-md focus:border-primary focus:ring focus:ring-primary/20" placeholder="Tuliskan catatan perbaikan jika ada dokumen yang kurang lengkap...">{{ $ajuanBpd->catatan_admin }}</textarea>
                    <div class="mt-3 text-right">
                        <button type="submit" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white text-xs font-medium rounded shadow-sm transition-colors">Kirim Catatan & Minta Revisi</button>
                    </div>
                </form>
            </div>

            {{-- PANEL UPDATE DISPOSISI SURAT --}}
            <div class="bg-surface rounded-card border border-border shadow-sm mb-10">
                <div class="px-5 py-4 border-b border-border bg-primary-soft/10">
                    <h3 class="font-display font-semibold text-primary">Update Disposisi & Status Proses</h3>
                </div>
                <form action="{{ route('admin.ajuan-bpd.disposisi', $ajuanBpd->id) }}" method="POST"
                    class="p-5 flex flex-col gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-ink mb-1">Pilih Status / Disposisi Terbaru</label>
                        <select name="tahapan"
                            class="w-full text-sm border-gray-300 rounded-md focus:border-primary focus:ring focus:ring-primary/20" required>
                            <option value="">-- Pilih --</option>
                            <option value="Diterima oleh Front Office">Diterima oleh Front Office</option>
                            <option value="Diteruskan ke Kepala Bidang">Diteruskan ke Kepala Bidang</option>
                            <option value="Diteruskan ke Kepala Dinas">Diteruskan ke Kepala Dinas</option>
                            <option value="SK Terbit">SK Terbit</option>
                            <option value="Selesai (Ditutup)">Selesai (Ditutup)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ink mb-1">Catatan Disposisi (Opsional)</label>
                        <textarea name="catatan" rows="2" class="w-full text-sm border-gray-300 rounded-md"
                            placeholder="Contoh: Berkas sudah lengkap, mohon arahan Bapak Kadis."></textarea>
                    </div>

                    <button type="submit"
                        class="w-full py-2.5 bg-primary rounded-btn text-white text-sm font-medium hover:bg-primary-light transition-colors flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                        Simpan Disposisi
                    </button>
                </form>
            </div>

            {{-- MILESTONE TRACKER --}}
            <div class="bg-surface rounded-card border border-border shadow-sm p-5 mb-10">
                <h3 class="text-base font-display font-semibold text-ink mb-1">History Tracking Status</h3>
                <div class="relative border-l-2 border-slate-200 ml-3 mt-4 space-y-6">
                    @foreach($ajuanBpd->milestones as $ms)
                        <div class="relative pl-6">
                            <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full {{ $loop->last ? 'bg-primary ring-4 ring-white' : 'bg-gray-400 ring-4 ring-white' }}"></span>
                            <p class="font-bold text-sm {{ $loop->last ? 'text-primary' : 'text-ink' }}">{{ $ms->tahapan }}</p>
                            <p class="text-xs text-muted">{{ $ms->tgl_selesai ? \Carbon\Carbon::parse($ms->tgl_selesai)->translatedFormat('d M Y H:i') : '-' }}</p>

                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function previewFile(url, title) {
                document.getElementById('pdf-empty-state').classList.add('hidden');
                const iframe = document.getElementById('pdf-iframe');
                const img = document.getElementById('img-preview');
                
                iframe.classList.add('hidden');
                img.classList.add('hidden');

                if (url.toLowerCase().endsWith('.pdf')) {
                    iframe.src = url;
                    iframe.classList.remove('hidden');
                } else {
                    img.src = url;
                    img.classList.remove('hidden');
                }
                
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
