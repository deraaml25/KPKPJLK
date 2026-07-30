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



            <div class="flex-1 overflow-y-auto p-4 md:p-8 bg-slate-100/50 relative" id="viewer-wrapper" style="display: flex; flex-direction: column;">
                @if($regulasi->file_path)
                    @php
                        $ext = pathinfo($regulasi->file_path, PATHINFO_EXTENSION);
                    @endphp
                    @if(in_array(strtolower($ext), ['doc', 'docx']))
                        {{-- DOCX: tampilkan download card, tidak bisa di-preview langsung di browser --}}
                        <div class="flex flex-col items-center justify-center h-full text-slate-500 gap-6 py-12">
                            <div class="w-24 h-24 rounded-2xl bg-blue-50 border-2 border-blue-200 flex items-center justify-center">
                                <svg viewBox="0 0 48 48" class="w-14 h-14" fill="none">
                                    <rect width="48" height="48" rx="8" fill="#2B579A"/>
                                    <text x="50%" y="62%" dominant-baseline="middle" text-anchor="middle" fill="white" font-size="16" font-weight="bold" font-family="Arial">W</text>
                                </svg>
                            </div>
                            <div class="text-center">
                                <p class="font-semibold text-slate-700 text-base">Dokumen Word (.docx)</p>
                                <p class="text-sm text-slate-400 mt-1">File Word tidak bisa ditampilkan langsung di browser.<br>Unduh untuk membuka dengan Microsoft Word.</p>
                            </div>
                            <a href="{{ asset('storage/' . $regulasi->file_path) }}" download
                                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-sm text-white transition-all shadow-md hover:shadow-lg"
                                style="background-color: #2B579A;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Unduh Dokumen Word
                            </a>
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
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    </style>
    @endpush
</x-app-layout>