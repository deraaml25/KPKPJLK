<x-app-layout>
    @section('title', 'Tinjau Regulasi')

    <div class="mb-4 flex items-center justify-between">
        <a href="{{ route('admin.regulasi.index') }}"
            class="text-sm font-medium text-slate-500 hover:text-slate-800 flex items-center gap-1 transition-colors">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali ke Daftar Regulasi
        </a>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-140px)] min-h-[600px]">
        
        <!-- KIRI: Layar Tinjauan Dokumen -->
        <div class="w-full flex flex-col bg-slate-100/50 rounded-2xl border border-slate-200 overflow-hidden shadow-inner relative" style="width: 70%;">
            <div class="bg-white border-b border-slate-200 px-4 py-3 flex items-center justify-between z-10 shadow-sm">
                <div class="flex items-center gap-2 text-slate-700 font-semibold text-sm">
                    <span class="material-symbols-outlined text-primary text-[20px]">visibility</span>
                    Layar Tinjauan Dokumen
                </div>
                
                @if($regulasi->file_path)
                    <a href="{{ asset('storage/' . $regulasi->file_path) }}" target="_blank"
                        class="text-xs text-primary hover:underline flex items-center gap-1 bg-primary/10 px-2.5 py-1 rounded-md transition-colors">
                        <span class="material-symbols-outlined text-[16px]">download</span>
                        Unduh Asli
                    </a>
                @endif
            </div>

            <!-- Tombol Komentar Pintar -->
            <button id="add-comment-btn" class="hidden absolute z-50 bg-primary text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg hover:bg-primary-dark transition-all transform scale-95 items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">format_quote</span>
                Kutip ke Catatan
            </button>

            <div class="flex-1 overflow-y-auto p-4 md:p-8 bg-slate-100/50 relative" id="viewer-wrapper" style="display: flex; flex-direction: column;">
                @if($regulasi->file_path)
                    @php
                        $ext = pathinfo($regulasi->file_path, PATHINFO_EXTENSION);
                    @endphp
                    @if(in_array(strtolower($ext), ['doc', 'docx']))
                        <div id="document-container" class="bg-white p-8 md:p-12 shadow-sm border border-slate-200 mx-auto rounded-md prose prose-slate max-w-none text-sm relative selection:bg-yellow-200 selection:text-slate-900" style="flex-grow: 1; min-height: 100%; width: 100%;">
                            <div class="flex flex-col items-center justify-center h-64 text-slate-400">
                                <svg class="animate-spin h-8 w-8 text-primary mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memuat dokumen...
                            </div>
                        </div>
                    @elseif(strtolower($ext) == 'pdf')
                        <iframe src="{{ asset('storage/' . $regulasi->file_path) }}" class="w-full h-full rounded-md shadow-sm border border-slate-200"></iframe>
                    @else
                        <div class="flex flex-col items-center justify-center h-full text-slate-400">
                            <span class="material-symbols-outlined text-6xl mb-2">description</span>
                            <p>Format file tidak dapat ditinjau langsung.</p>
                            <a href="{{ asset('storage/' . $regulasi->file_path) }}" class="text-primary hover:underline mt-2">Unduh file</a>
                        </div>
                    @endif
                @else
                    <div class="flex flex-col items-center justify-center h-full text-slate-400">
                        <span class="material-symbols-outlined text-6xl mb-2">description</span>
                        <p>Draf belum diunggah.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- KANAN: Panel Informasi & Form -->
        <div class="w-full flex flex-col h-full overflow-y-auto pr-2 custom-scrollbar" style="width: 30%;">
            
            <!-- Info Card -->
            <!-- Info Card -->
            <div class="rounded-xl p-5 shadow-sm mb-5 relative overflow-hidden" style="background-color: #e0f2fe; color: #0c4a6e;">
                <div class="absolute top-0 right-0 p-4 opacity-20">
                    <span class="material-symbols-outlined text-8xl" style="color: #0284c7;">account_balance</span>
                </div>
                <div class="relative z-10">
                    <p class="text-xs mb-1 font-mono uppercase tracking-wider" style="color: #0369a1;">{{ $regulasi->no_regulasi }}</p>
                    <h2 class="text-xl font-bold mb-3" style="color: #082f49;">{{ strtoupper($regulasi->desa->nama_desa) }}</h2>
                    
                    <div class="text-sm" style="color: #0f172a;">
                        <p class="mb-1"><span class="opacity-70">Layanan:</span> Evaluasi Hukum ({{ ucfirst($regulasi->tipe) }})</p>
                        <p class="mb-1"><span class="opacity-70">Tanggal:</span> {{ $regulasi->tgl_diajukan ? $regulasi->tgl_diajukan->format('d M Y') : '-' }}</p>
                    </div>

                    <div class="mt-4 pt-4 border-t border-blue-200">
                        <p class="text-sm font-semibold mb-1 leading-snug">{{ $regulasi->judul }}</p>
                    </div>
                </div>
            </div>

            <!-- Panel Aksi -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6">
                <h3 class="text-md font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Catatan Perbaikan Desa</h3>

                @if($regulasi->status === 'disahkan')
                    <div class="p-4 bg-green-50 text-green-800 rounded-lg text-sm border border-green-100">
                        <div class="flex items-center gap-2 mb-2 font-bold">
                            <span class="material-symbols-outlined">check_circle</span>
                            Status: Disahkan
                        </div>
                        <p class="text-xs">Regulasi ini telah terbit di Lembaran Desa.</p>
                        @if($regulasi->catatan_revisi)
                            <div class="mt-3 p-3 bg-white rounded border border-green-200">
                                <strong class="text-xs block mb-1">Catatan Akhir Sanksi/Legal Note:</strong>
                                <p class="text-xs">{{ $regulasi->catatan_revisi }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    
                    <!-- Form Revisi -->
                    <form action="{{ route('admin.regulasi.kembalikan', $regulasi) }}" method="POST" enctype="multipart/form-data" class="mb-6 pb-6 border-b border-slate-100">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="catatan" class="block text-xs font-bold text-slate-700 mb-1.5">Catatan Kelengkapan dari Admin untuk Desa</label>
                            <textarea name="catatan" id="catatan" rows="5"
                                class="w-full text-sm rounded-lg border-slate-300 text-slate-800 bg-white focus:border-slate-500 focus:ring-slate-500 shadow-sm"
                                placeholder="Tuliskan catatan perbaikan jika ada dokumen yang kurang lengkap..." required></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="file_catatan_dinas" class="block text-xs font-bold text-slate-700 mb-1.5">Unggah Draf Coretan (Opsional)</label>
                            <input type="file" name="file_catatan_dinas" id="file_catatan_dinas"
                                class="w-full text-xs box-border rounded-lg border-slate-300 p-1.5 bg-slate-50" accept=".doc,.docx,.pdf">
                            <p class="text-[10px] text-slate-500 mt-1">Lampirkan file bila ada coretan khusus.</p>
                        </div>

                        <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2 font-bold rounded-lg transition-colors text-sm shadow-sm"
                            style="background-color: #0A1A3A; color: white;">
                            Kembalikan untuk Revisi
                        </button>
                    </form>



                @endif
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.6.0/mammoth.browser.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if($regulasi->file_path && in_array(strtolower(pathinfo($regulasi->file_path, PATHINFO_EXTENSION)), ['doc', 'docx']))
                const docUrl = "{{ asset('storage/' . $regulasi->file_path) }}";
                
                fetch(docUrl)
                    .then(response => {
                        if(!response.ok) throw new Error('Network response was not ok');
                        return response.arrayBuffer();
                    })
                    .then(arrayBuffer => mammoth.convertToHtml({arrayBuffer: arrayBuffer}))
                    .then(result => {
                        document.getElementById('document-container').innerHTML = result.value;
                        initSmartComment();
                    })
                    .catch(err => {
                        console.error('Error rendering DOCX:', err);
                        document.getElementById('document-container').innerHTML = `
                            <div class="flex flex-col items-center justify-center h-64 text-red-400">
                                <span class="material-symbols-outlined text-4xl mb-2">error</span>
                                <p>Gagal memuat dokumen. Harap unduh secara manual.</p>
                                <a href="${docUrl}" class="text-primary hover:underline mt-2">Unduh file</a>
                            </div>
                        `;
                    });
            @endif

            function initSmartComment() {
                const wrapper = document.getElementById('viewer-wrapper');
                const btn = document.getElementById('add-comment-btn');
                const catatanInput = document.getElementById('catatan');
                let selectedText = '';

                wrapper.addEventListener('mouseup', function(e) {
                    const selection = window.getSelection();
                    selectedText = selection.toString().trim();
                    
                    if (selectedText.length > 0 && selection.anchorNode && wrapper.contains(selection.anchorNode)) {
                        // Position button near cursor
                        const rect = selection.getRangeAt(0).getBoundingClientRect();
                        const wrapperRect = wrapper.getBoundingClientRect();
                        
                        btn.style.top = (rect.top - wrapperRect.top + wrapper.scrollTop - 40) + 'px';
                        btn.style.left = (rect.left - wrapperRect.left + (rect.width / 2) - (btn.offsetWidth / 2)) + 'px';
                        
                        btn.classList.remove('hidden');
                        btn.classList.add('flex');
                    } else {
                        setTimeout(() => {
                            btn.classList.add('hidden');
                            btn.classList.remove('flex');
                        }, 100);
                    }
                });

                btn.addEventListener('mousedown', function(e) {
                    e.preventDefault(); // Prevent text un-selection
                });

                btn.addEventListener('click', function(e) {
                    if (selectedText) {
                        const quote = `> "${selectedText}"\n\n`;
                        if (catatanInput.value) {
                            catatanInput.value += `\n\n${quote}`;
                        } else {
                            catatanInput.value = quote;
                        }
                        catatanInput.focus();
                        
                        // Hide button
                        btn.classList.add('hidden');
                        btn.classList.remove('flex');
                        window.getSelection().removeAllRanges();
                    }
                });
            }
        });
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
        /* Style untuk hasil render mammoth agar mirip dokumen */
        #document-container {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.5;
        }
        #document-container p { margin-bottom: 1em; }
        #document-container h1, #document-container h2, #document-container h3 { font-weight: bold; margin-top: 1.5em; margin-bottom: 0.5em; }
        #document-container table { width: 100%; border-collapse: collapse; margin-bottom: 1em; }
        #document-container th, #document-container td { border: 1px solid #cbd5e1; padding: 0.5em; }
    </style>
    @endpush
</x-app-layout>