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

    <div class="{{ $ajuan->metode !== 'offline' ? 'grid grid-cols-1 lg:grid-cols-12 gap-6' : 'max-w-4xl mx-auto' }} h-[80vh]">

        @if($ajuan->metode !== 'offline')
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
        @endif

        {{-- PANEL KANAN: VERIFIKASI GRANULAR & DISPOSISI --}}
        <div class="{{ $ajuan->metode !== 'offline' ? 'lg:col-span-5 flex flex-col' : 'w-full flex flex-col lg:flex-row' }} gap-6 h-full overflow-y-auto pr-2 custom-scrollbar">

            {{-- Kolom Kiri (Atas untuk online, Kiri 70% untuk offline) --}}
            <div class="w-full flex flex-col gap-6" @if($ajuan->metode === 'offline') style="flex: 0 0 calc(70% - 12px); max-width: calc(70% - 12px);" @endif>
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

                    <p class="text-primary-soft font-medium mt-2">Daftar Peserta ({{ $ajuan->pesertas->count() }}
                        Orang):</p>
                    <div class="max-h-24 overflow-y-auto custom-scrollbar space-y-2 pr-1">
                        @foreach($ajuan->pesertas as $index => $peserta)
                            <div class="bg-black/10 rounded p-2 border border-white/5 text-xs">
                                <span class="font-bold block">{{ $index + 1 }}. {{ $peserta->perangkatDesa->nama }}</span>
                                <span class="opacity-80 block">{{ $peserta->perangkatDesa->jabatan }}</span>
                                @if($peserta->jabatan_baru)
                                    <span class="bg-white/20 px-1.5 py-0.5 rounded text-[10px] mt-1 inline-block">M =>
                                        {{ $peserta->jabatan_baru }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- LIST DOKUMEN CHECKLIST --}}
            <div class="bg-surface rounded-card border border-border shadow-sm flex flex-col">
                <div class="px-5 py-4 border-b border-border bg-gray-50">
                    <h3 class="font-display font-semibold text-ink">Verifikasi Syarat Formil</h3>
                </div>

                <div class="divide-y divide-border">
                    @foreach($dokumenList as $item)
                        <div class="p-4 border-l-4 transition-colors {{ $item->status == 'valid' || $item->status == 'lengkap' ? 'border-green-500 bg-green-50/40' : ($item->status == 'kurang' || $item->status == 'tidak_sesuai' ? 'border-red-500 bg-red-50/40' : 'border-amber-400 bg-amber-50/30') }}">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-white text-xs font-bold text-ink border border-border shadow-sm flex-shrink-0">{{ $item->templateChecklist->urutan }}</span>
                                <div class="flex-1 flex flex-col sm:flex-row sm:items-center gap-2 justify-between">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <p class="text-sm font-semibold text-ink leading-tight">
                                            {{ $item->templateChecklist->nama_dokumen }}
                                        </p>
                                        @if($item->templateChecklist->wajib && !in_array(strtolower($ajuan->jenisLayanan->nama), ['rotasi', 'pengangkatan']))
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-red-700">Wajib</span>
                                        @endif

                                        @if($item->file_path)
                                            <button
                                                onclick="previewPdf('{{ Storage::disk('public')->url($item->file_path) }}', '{{ addslashes($item->templateChecklist->nama_dokumen) }}')"
                                                class="ml-2 inline-flex items-center text-xs px-2 py-1 bg-white hover:bg-gray-50 border border-gray-300 rounded font-medium text-ink transition-colors shadow-sm">
                                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Lihat PDF
                                            </button>
                                        @elseif($ajuan->metode === 'online' && !$ajuan->berkas_zip)
                                            <span class="ml-2 inline-block px-2 py-1 bg-gray-100 text-gray-500 text-xs font-medium rounded border border-gray-200">Belum Terunggah</span>
                                        @endif
                                    </div>

                                    <form action="{{ route('admin.ajuan.verify', [$ajuan, $item]) }}" method="POST" class="verify-form flex-shrink-0 ml-auto sm:ml-4" data-url="{{ route('admin.ajuan.verify', [$ajuan, $item]) }}">
                                        @csrf
                                        <input type="checkbox" name="status" value="valid" 
                                               class="w-7 h-7 text-blue-400 focus:ring-blue-400 border-gray-300 rounded shadow-sm cursor-pointer transition-colors verify-checkbox" 
                                               {{ $item->status == 'valid' || $item->status == 'lengkap' ? 'checked' : '' }}
                                               title="Tandai Sesuai">
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- PANEL BERKAS ZIP & CATATAN ADMIN --}}
            <div class="bg-surface rounded-card border border-border shadow-sm flex flex-col mb-2">
                <div class="px-5 py-4 border-b border-border bg-gray-50 flex justify-between items-center">
                    <h3 class="font-display font-semibold text-ink">Keseluruhan Persyaratan & Catatan</h3>
                    @if($ajuan->metode === 'online' && $ajuan->berkas_zip)
                        <a href="{{ Storage::disk('public')->url($ajuan->berkas_zip) }}" target="_blank" class="inline-flex items-center text-xs px-3 py-1 bg-primary text-white hover:bg-primary-light rounded font-medium transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Unduh Berkas ZIP
                        </a>
                    @elseif($ajuan->metode === 'offline')
                        <span class="text-xs font-semibold px-2 py-1 bg-gray-200 text-gray-700 rounded">Metode: Offline</span>
                    @else
                        <span class="text-xs font-semibold px-2 py-1 bg-red-100 text-red-700 rounded">Berkas ZIP belum diunggah</span>
                    @endif
                </div>
                <form action="{{ route('admin.ajuan.update-catatan', $ajuan) }}" method="POST" class="p-5">
                    @csrf
                    <label class="block text-sm font-medium text-ink mb-2">Catatan Kelengkapan dari Admin untuk Desa</label>
                    <textarea name="catatan_admin" rows="3" class="w-full text-sm border-gray-300 rounded-md focus:border-primary focus:ring focus:ring-primary/20" placeholder="Tuliskan catatan lengkap atau tidaknya berkas keseluruhan di sini...">{{ $ajuan->catatan_admin }}</textarea>
                    <div class="mt-3 text-right">
                        <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-light text-white text-xs font-medium rounded shadow-sm transition-colors">Simpan Catatan Admin</button>
                    </div>
                </form>
            </div>

            </div>

            {{-- Kolom Kanan (Bawah untuk online, Kanan 30% untuk offline) --}}
            <div class="w-full flex flex-col gap-6" @if($ajuan->metode === 'offline') style="flex: 0 0 calc(30% - 12px); max-width: calc(30% - 12px);" @endif>
            {{-- PANEL MILESTONE TRACKER & ACTION BUTTONS --}}
            <div class="bg-surface rounded-card border border-border shadow-sm mb-10 flex flex-col">
                <div class="p-5">
                    <h3 class="text-base font-display font-semibold text-ink mb-1">Status Proses</h3>
                    <p class="text-xs text-muted mb-4 pb-4 border-b border-border">Pantau tahapan perjalanan ajuan ini secara real-time.</p>
                    <x-milestone-tracker :tahapAktif="$tahapAktif" :milestones="$ajuan->milestoneTrackings" :ajuan="$ajuan" />
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="px-5 py-4 bg-gray-50 border-t border-border mt-auto rounded-b-card">
                    <h3 class="text-xs font-semibold text-muted mb-2">Tindak Lanjut Cepat</h3>
                    <div class="flex flex-col gap-2">
                        @if($ajuan->posisi_surat !== 'Selesai (Surat Terbit)')
                        <form action="{{ route('admin.ajuan.disposisi', $ajuan) }}" method="POST">
                            @csrf
                            <input type="hidden" name="posisi_baru" value="{{ $nextPosisi }}">
                            <button type="submit"
                                class="w-full py-2 px-3 bg-primary rounded text-white text-xs font-medium hover:bg-primary-light transition-colors flex items-center justify-center shadow-sm">
                                Lanjutkan ke : {{ $nextPosisi }}
                            </button>
                        </form>
                        @endif

                        <form action="{{ route('admin.ajuan.disposisi', $ajuan) }}" method="POST">
                            @csrf
                            <input type="hidden" name="posisi_baru" value="Front Office (FO)">
                            <input type="hidden" name="status_ajuan_baru" value="direvisi">
                            <button type="submit"
                                class="w-full py-2 px-3 bg-white border border-red-300 text-red-600 rounded text-xs font-medium hover:bg-red-50 transition-colors flex items-center justify-center shadow-sm" title="Kembalikan ke Front Office (Butuh Revisi)">
                                Revisi
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
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

            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.verify-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const form = this.closest('form');
                        const url = form.getAttribute('data-url');
                        const token = form.querySelector('input[name="_token"]').value;
                        const status = this.checked ? 'valid' : 'menunggu';
                        
                        // Optimistic UI update
                        const row = form.closest('.p-4');
                        if (this.checked) {
                            row.classList.remove('border-red-500', 'bg-red-50/40', 'border-amber-400', 'bg-amber-50/30');
                            row.classList.add('border-green-500', 'bg-green-50/40');
                        } else {
                            row.classList.remove('border-green-500', 'bg-green-50/40', 'border-red-500', 'bg-red-50/40');
                            row.classList.add('border-amber-400', 'bg-amber-50/30');
                        }

                        // Send request without requiring response handling
                        fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ status: status })
                        }).catch(err => {
                            console.error('Network error during verification update', err);
                            // Revert UI on network error
                            this.checked = !this.checked;
                            if (this.checked) {
                                row.classList.remove('border-amber-400', 'bg-amber-50/30');
                                row.classList.add('border-green-500', 'bg-green-50/40');
                            } else {
                                row.classList.remove('border-green-500', 'bg-green-50/40');
                                row.classList.add('border-amber-400', 'bg-amber-50/30');
                            }
                        });
                    });
                });
            });
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