<x-app-layout>
    @section('title', 'Detail & Revisi Regulasi')

    <div class="w-full">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex-1 w-full">
                <a href="{{ route('desa.regulasi.index') }}"
                    class="text-sm font-medium text-primary hover:text-primary-light transition-colors inline-flex items-center gap-1.5 mb-3 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg w-fit">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    Kembali ke Daftar Regulasi
                </a>
                <div class="flex items-center gap-3 mb-2 flex-wrap">
                    <h2 class="text-2xl font-display font-bold text-slate-800">{{ $regulasi->judul }}</h2>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-sm uppercase tracking-wider">
                        {{ str_replace('_', ' ', $regulasi->tipe) }}
                    </span>
                </div>
                
                @if($regulasi->deskripsi)
                    <div class="mt-3 p-4 bg-slate-50 border border-slate-200 rounded-xl max-w-3xl flex items-start gap-3">
                        <span class="material-symbols-outlined text-slate-400 mt-0.5">sticky_note_2</span>
                        <div class="flex-1">
                            <p class="text-sm text-slate-700 leading-relaxed">{{ $regulasi->deskripsi }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-slate-400 mt-2 italic flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">info</span>
                        Tidak ada keterangan lampiran
                    </p>
                @endif
            </div>
            
            <div class="flex-shrink-0 bg-slate-50 p-4 rounded-xl border border-slate-100 min-w-[200px] flex flex-col items-center justify-center h-full">
                <p class="text-[10px] text-slate-500 font-bold mb-2 uppercase tracking-widest">Status Evaluasi</p>
                @if($regulasi->status === 'disahkan')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-800 shadow-sm border border-green-200">
                        ✅ Telah Disahkan ({{ $regulasi->no_regulasi }})
                    </span>
                @elseif($regulasi->status === 'perlu_revisi')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-red-100 text-red-800 shadow-sm border border-red-200">
                        ⚠️ Butuh Revisi
                    </span>
                @elseif($regulasi->status === 'evaluasi_lanjutan')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800 shadow-sm border border-yellow-200">
                        🟡 Evaluasi Lanjutan
                    </span>
                @elseif($regulasi->status === 'disetujui')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-emerald-100 text-emerald-800 shadow-sm border border-emerald-200">
                        ✅ Draft Disetujui
                    </span>
                @else
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-blue-100 text-blue-800 shadow-sm border border-blue-200">
                        🔵 Menunggu Evaluasi
                    </span>
                @endif
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-140px)] min-h-[600px]">
            <!-- KIRI: Layar Tinjauan Dokumen -->
            <div class="w-full flex flex-col gap-0" style="width: 70%;">
                @php 
                    $fileToPreview = $regulasi->status === 'disahkan' && $regulasi->file_pdf ? $regulasi->file_pdf : $regulasi->file_path;
                    $ext = $fileToPreview ? strtolower(pathinfo($fileToPreview, PATHINFO_EXTENSION)) : null; 
                @endphp

                <div class="rounded-2xl border border-slate-200 overflow-hidden shadow-sm bg-white flex flex-col" style="height: 100%;">
                    {{-- Header kartu --}}
                    <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-slate-200 flex-shrink-0">
                        <div>
                            <p class="text-sm font-medium text-ink">Draf Peraturan Desa</p>
                            <p class="text-xs text-muted mt-0.5">
                                {{ $fileToPreview ? $regulasi->judul : 'Belum ada dokumen diunggah' }}
                            </p>
                        </div>
                        @if($fileToPreview)
                            <a href="{{ asset('storage/' . $fileToPreview) }}" target="_blank"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary text-white text-xs font-medium rounded-btn hover:bg-primary-light transition-colors flex-shrink-0">
                                📥 Unduh
                            </a>
                        @endif
                    </div>

                    {{-- Area preview dokumen --}}
                    <div class="flex-1 overflow-y-auto bg-slate-50" style="min-height: 0;">
                        @if($fileToPreview)
                            @if($ext === 'pdf')
                                <iframe src="{{ asset('storage/' . $fileToPreview) }}"
                                    class="w-full h-full border-0" style="min-height: 600px;"></iframe>
                            @elseif(in_array($ext, ['doc', 'docx']))
                                <div class="p-6 md:p-10">
                                    <div id="docx-loading" class="flex flex-col items-center justify-center py-16 text-slate-400 gap-3">
                                        <svg class="animate-spin w-8 h-8 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                        </svg>
                                        <p class="text-sm">Memuat dokumen...</p>
                                    </div>
                                    <div id="docx-error" class="hidden flex-col items-center justify-center py-16 text-slate-400 gap-3">
                                        <span class="material-symbols-outlined text-5xl">description</span>
                                        <p class="text-sm">Gagal memuat pratinjau. Gunakan tombol Unduh.</p>
                                    </div>
                                    <div id="docx-content" class="hidden prose prose-sm max-w-none text-slate-800 leading-relaxed bg-white p-8 rounded-lg shadow-sm border border-slate-200 min-h-[800px]"></div>
                                </div>
                                <script>
                                (function () {
                                    var fileUrl = '{{ asset('storage/' . $fileToPreview) }}';
                                    var loading = document.getElementById('docx-loading');
                                    var errorEl = document.getElementById('docx-error');
                                    var contentEl = document.getElementById('docx-content');
                                    function showError() {
                                        loading.classList.add('hidden');
                                        errorEl.classList.remove('hidden');
                                        errorEl.style.display = 'flex';
                                    }
                                    function loadScript(src, cb) {
                                        var s = document.createElement('script');
                                        s.src = src; s.onload = cb;
                                        s.onerror = function() { showError(); };
                                        document.head.appendChild(s);
                                    }
                                    loadScript('https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.8.0/mammoth.browser.min.js', function () {
                                        fetch(fileUrl).then(function(r) { return r.arrayBuffer(); })
                                            .then(function(buf) { return mammoth.convertToHtml({ arrayBuffer: buf }); })
                                            .then(function(result) {
                                                loading.classList.add('hidden');
                                                contentEl.innerHTML = result.value;
                                                contentEl.classList.remove('hidden');
                                            }).catch(function() { showError(); });
                                    });
                                })();
                                </script>
                            @else
                                <div class="flex flex-col items-center justify-center py-20 text-slate-400 gap-3">
                                    <span class="material-symbols-outlined text-6xl">description</span>
                                    <p class="text-sm">Format file tidak dapat ditampilkan langsung.</p>
                                    <a href="{{ asset('storage/' . $fileToPreview) }}" class="text-primary hover:underline text-sm">Unduh file</a>
                                </div>
                            @endif
                        @else
                            <div class="flex flex-col items-center justify-center py-20 text-slate-400 gap-3">
                                <span class="material-symbols-outlined text-6xl">upload_file</span>
                                <p class="text-sm">Draf belum diunggah oleh desa.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- KANAN: Panel Aksi & Status -->
            <div class="w-full lg:w-3/12 xl:w-1/4 flex-shrink-0 flex flex-col min-h-0" style="width: 30%;">
                
                @if($regulasi->status === 'disahkan')
                    <!-- Bukti Sah -->
                    <div class="bg-green-50 rounded-card shadow-sm border border-green-200 p-6 mb-4">
                        <h3 class="text-md font-display font-bold text-green-900 mb-2">🎉 Selamat, Regulasi Sah!</h3>
                        <p class="text-sm text-green-800 mb-4">Produk hukum ini telah diregistrasi resmi oleh Dinpermasdes dan tercatat di Lembaran Desa.</p>
                        <div class="bg-white p-3 rounded text-sm text-ink mb-3 font-mono font-bold text-center border border-green-200">
                            {{ $regulasi->no_regulasi }}
                        </div>
                    </div>
                @endif

                @if($regulasi->status === 'perlu_revisi')
                    <div class="bg-red-50 rounded-card shadow-sm border border-red-200 p-6 mb-4">
                        <h3 class="text-md font-display font-bold text-red-900 mb-4 pb-2 border-b border-red-200">Evaluasi Dinas (Penting)</h3>

                        <div class="mb-4">
                            <span class="text-xs text-red-700 block mb-1 font-semibold">Catatan Hukum:</span>
                            <div class="bg-white p-3 rounded text-sm text-ink border border-red-100">
                                {{ $regulasi->catatan_revisi }}
                            </div>
                        </div>

                        @if($regulasi->file_catatan_dinas)
                            <div class="mb-4">
                                <span class="text-xs text-red-700 block mb-1 font-semibold">Coretan Dinas (Opsional):</span>
                                <a href="{{ asset('storage/' . $regulasi->file_catatan_dinas) }}" target="_blank"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-red-200 text-red-700 hover:bg-red-100 font-medium rounded-btn transition-colors text-sm">
                                    📝 Unduh Dokumen
                                </a>
                            </div>
                        @endif

                        <hr class="border-red-200 my-5">

                        @if($errors->any())
                            <div class="mb-4 p-3 rounded-card bg-red-50 border border-red-200 text-red-800 text-xs">
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $e)
                                        <li>{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('desa.regulasi.kirim-revisi', $regulasi) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h4 class="text-sm font-semibold text-ink mb-3">Upload Perbaikan Final</h4>
                            <div class="mb-3">
                                <label for="file_revisi" class="block text-xs font-medium text-ink mb-1">Upload Draf Hasil Perbaikan (Wajib .doc/.docx)</label>
                                <input type="file" name="file_revisi" id="file_revisi" class="w-full text-xs box-border rounded-md border-border bg-white" accept=".doc,.docx" required>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors text-sm shadow-sm">
                                Laporkan Selesai
                            </button>
                        </form>
                    </div>
                @elseif($regulasi->status === 'evaluasi_lanjutan')
                    <div class="bg-yellow-50 rounded-card shadow-sm border border-yellow-200 p-6 mb-4">
                        <h3 class="text-md font-display font-bold text-yellow-900 mb-2">Evaluasi Lanjutan Aktif</h3>
                        <p class="text-sm text-yellow-800">Anda telah mengirimkan dokumen perbaikan ke Dinpermasdes. Silakan tunggu hingga status berubah menjadi <b>Disetujui</b> oleh Admin.</p>
                    </div>
                @elseif($regulasi->status === 'disetujui')
                    <div class="bg-green-50 rounded-card shadow-sm border border-green-200 p-6 mb-4">
                        <h3 class="text-md font-display font-bold text-green-900 mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined">verified</span>
                            Draft Disetujui
                        </h3>
                        <p class="text-sm text-green-800 mb-4">Draft regulasi Anda telah dikoreksi dan disetujui. Silakan unggah salinan final (PDF yang sudah ditandatangani dan dicap) untuk mengesahkan regulasi ini.</p>
                        
                        <form action="{{ route('desa.regulasi.sahkan', $regulasi) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="file_final" class="block text-xs font-bold text-green-900 mb-1">Unggah Salinan Final (Wajib .pdf)</label>
                                <input type="file" name="file_final" id="file_final" class="w-full text-sm box-border rounded-lg border-green-300 p-2 bg-white" accept=".pdf" required>
                            </div>
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengesahkan dan menerbitkan regulasi ini secara definitif?')" class="w-full inline-flex justify-center items-center px-4 py-2 font-bold rounded-lg transition-colors text-sm shadow-sm bg-green-700 hover:bg-green-800 text-white">
                                Sahkan Regulasi
                            </button>
                        </form>
                    </div>
                @elseif($regulasi->status === 'menunggu_evaluasi')
                    <!-- Jika sedang menunggu review atau diajukan -->
                    <div class="bg-gray-50 rounded-card shadow-sm border border-gray-200 p-6 text-center text-muted mb-4">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-md font-semibold text-ink line-clamp-2">Sedang dievaluasi oleh Dinpermasdes</h3>
                        <p class="text-sm mt-1">Belum ada catatan resmi yang dilaporkan.</p>
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</x-app-layout>