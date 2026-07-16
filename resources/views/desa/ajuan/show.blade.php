<x-app-layout>
    @section('title', 'Detail Ajuan: ' . $ajuan->no_registrasi)

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('desa.ajuan.index') }}" class="inline-flex items-center text-sm font-medium text-muted hover:text-ink">
            <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Ajuan
        </a>
        @if($ajuan->status === 'selesai' && $ajuan->arsipRekom)
            <a href="{{ Storage::disk('public')->url($ajuan->arsipRekom->file_path) }}" target="_blank"
               class="inline-flex items-center px-4 py-2 bg-success text-white text-sm font-medium rounded-btn hover:bg-green-700 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Unduh Surat Rekomendasi
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-5 p-4 rounded-card bg-green-50 border border-green-200 text-green-800 flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="mb-5 p-4 rounded-card bg-red-50 border border-red-200 text-red-800 flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <ul class="text-sm list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- ===== KIRI: Info + Checklist Upload ===== --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Header Card --}}
            @php
                $statusBadge = match($ajuan->status) {
                    'submitted' => ['label' => 'Menunggu Verifikasi', 'css' => 'bg-blue-500 text-white'],
                    'direvisi'  => ['label' => 'Perlu Perbaikan Dokumen', 'css' => 'bg-red-500 text-white'],
                    'diproses'  => ['label' => 'Sedang Diproses', 'css' => 'bg-yellow-400 text-yellow-900'],
                    'selesai'   => ['label' => 'Selesai - Rekomendasi Terbit', 'css' => 'bg-green-500 text-white'],
                    'draft'     => ['label' => 'Draft', 'css' => 'bg-gray-400 text-white'],
                    default     => ['label' => $ajuan->status, 'css' => 'bg-gray-400 text-white'],
                };
            @endphp

            <div class="bg-primary text-white rounded-card shadow-md p-6 relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white opacity-5 rounded-full blur-xl"></div>
                <div class="flex flex-wrap justify-between items-start gap-3 mb-5">
                    <div>
                        <p class="text-xs font-mono text-primary-soft tracking-widest mb-1">{{ $ajuan->no_registrasi }}</p>
                        <h2 class="text-xl font-display font-bold">Ajuan {{ $ajuan->jenisLayanan->nama }}</h2>
                        <p class="text-sm text-primary-soft mt-1">{{ $ajuan->perangkatDesa->nama }} — {{ $ajuan->perangkatDesa->jabatan }}</p>
                    </div>
                    <span class="px-3 py-1.5 rounded-full text-xs font-bold shadow-sm {{ $statusBadge['css'] }}">
                        {{ $statusBadge['label'] }}
                    </span>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 bg-black/10 rounded-xl p-4 border border-white/10">
                    <div>
                        <p class="text-xs text-primary-soft mb-0.5">Tgl Diajukan</p>
                        <p class="font-semibold text-sm">{{ $ajuan->tgl_diajukan ? $ajuan->tgl_diajukan->format('d M Y') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-primary-soft mb-0.5">Target SLA (20 HK)</p>
                        <p class="font-semibold text-sm">{{ $ajuan->tgl_sla_batas ? $ajuan->tgl_sla_batas->format('d M Y') : '-' }}</p>
                    </div>
                    @if($ajuan->alasanPemberhentian)
                    <div>
                        <p class="text-xs text-primary-soft mb-0.5">Alasan</p>
                        <p class="font-semibold text-sm">{{ $ajuan->alasanPemberhentian->nama }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Dokumen Persyaratan --}}
            <div class="bg-surface rounded-card border border-border shadow-sm overflow-hidden">
                <form method="POST" action="{{ route('desa.ajuan.bulk-upload', $ajuan) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="px-6 py-4 border-b border-border bg-gray-50 flex flex-wrap justify-between items-center gap-3">
                        <div>
                            <h3 class="text-base font-display font-semibold text-ink">Checklist Berkas Persyaratan ({{ $ajuan->checklistAjuans->count() }} item)</h3>
                            <p class="text-xs text-muted mt-0.5">Unggah dokumen (PDF, maks 10MB) untuk setiap persyaratan.</p>
                        </div>
                    </div>

                    <div class="divide-y divide-border">
                        @forelse($ajuan->checklistAjuans->sortBy('templateChecklist.urutan') as $item)
                            @php
                                $bolehUpload = !in_array($ajuan->status, ['selesai', 'submitted', 'diproses']);
                            @endphp
                            <div class="px-6 py-4 flex flex-col lg:flex-row lg:items-center justify-between hover:bg-gray-50 transition-colors gap-4">
                                <div class="flex items-start lg:items-center gap-3 flex-1 pr-4">
                                    <span class="font-medium text-ink flex-shrink-0">{{ $item->templateChecklist->urutan }}.</span>
                                    <span class="text-sm text-ink">{{ $item->templateChecklist->nama_dokumen }}</span>
                                    @if($item->templateChecklist->wajib)
                                        <span class="text-danger text-xs font-bold flex-shrink-0">*</span>
                                    @endif
                                </div>
                                
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 lg:gap-4 flex-shrink-0">
                                    @if($item->file_path)
                                        <div class="flex items-center text-success text-sm font-medium whitespace-nowrap">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Terunggah
                                        </div>
                                    @endif
                                    
                                    @if($bolehUpload)
                                        <input type="file" name="dokumen[{{ $item->id }}]" accept=".pdf" 
                                               class="block w-full sm:w-auto text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-light file:cursor-pointer cursor-pointer focus:outline-none">
                                    @endif

                                    @if($item->file_path)
                                        <a href="{{ Storage::disk('public')->url($item->file_path) }}" target="_blank" 
                                           class="text-xs font-medium text-primary hover:underline truncate max-w-[150px] whitespace-nowrap" title="{{ basename($item->file_path) }}">
                                            Lihat File
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @if($item->catatan && in_array($item->status, ['kurang', 'tidak_sesuai']))
                                <div class="px-6 py-2 bg-red-50 text-xs text-red-800 border-b border-border">
                                    <strong class="font-semibold">Catatan Perbaikan:</strong> {{ $item->catatan }}
                                </div>
                            @endif
                        @empty
                            <div class="py-12 text-center text-muted text-sm">Tidak ada checklist dokumen untuk ajuan ini.</div>
                        @endforelse
                    </div>

                    @if(!in_array($ajuan->status, ['selesai', 'submitted', 'diproses']))
                    <div class="px-6 py-5 bg-gray-50 border-t border-border flex flex-wrap items-center justify-end gap-3">
                        <button type="submit" name="simpan_draft" value="1" class="px-5 py-2.5 bg-white border border-border rounded-btn text-ink text-sm font-medium hover:bg-gray-50 transition-colors shadow-sm">
                            Simpan Draft
                        </button>
                        <button type="submit" name="submit_ajuan" value="1" class="px-5 py-2.5 bg-success rounded-btn text-white text-sm font-medium hover:bg-green-700 transition-colors shadow-sm">
                            Kirim Pengajuan (Submit)
                        </button>
                    </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- ===== KANAN: Milestone Tracker ===== --}}
        <div>
            <div class="bg-surface rounded-card border border-border shadow-sm p-5 sticky top-6">
                <h3 class="text-base font-display font-semibold text-ink mb-1">Status Proses</h3>
                <p class="text-xs text-muted mb-4 pb-4 border-b border-border">Pantau tahapan perjalanan ajuan Anda secara real-time.</p>
                <x-milestone-tracker :tahapAktif="$tahapAktif" :milestones="$ajuan->milestoneTrackings" />
            </div>
        </div>
    </div>
</x-app-layout>
