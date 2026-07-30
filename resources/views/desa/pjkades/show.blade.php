<x-app-layout>
    @section('title', 'Detail Usulan SK Kades')

    <div class="max-w-5xl mx-auto mb-8">
        {{-- Header Card --}}
        <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        @if($pjkades->kategori === 'plt_kades')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                Plt Kades (Pelaksana Tugas)
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                Pj Kades (Penjabat)
                            </span>
                        @endif
                        <span class="text-xs font-mono text-muted">#{{ $pjkades->no_registrasi ?? ('SKK-' . $pjkades->id) }}</span>
                    </div>
                    <h2 class="text-xl font-display font-bold text-ink">
                        Usulan {{ $pjkades->kategori === 'plt_kades' ? 'Plt Kepala Desa' : 'Pj Kepala Desa' }} — Desa {{ $pjkades->desa->nama_desa }}
                    </h2>
                    <p class="text-muted text-sm mt-1">
                        Alasan Pemberhentian/Cuti: <strong class="text-ink">{{ $pjkades->alasan_nama ?? ($pjkades->alasanPemberhentian->nama ?? '-') }}</strong>
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('desa.pjkades.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-ink font-medium rounded-btn transition-colors text-sm">
                        Kembali ke Daftar
                    </a>

                    @if($pjkades->status === 'draft' || $pjkades->status === 'rejected')
                        <form action="{{ route('desa.pjkades.submit', $pjkades->id) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengirim berkas usulan ini ke Dinpermasdes?')"
                                class="px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Kirim Usulan ke Dinpermasdes
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 text-green-800 rounded-lg border border-green-200 text-sm mb-6 font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-red-50 text-red-800 rounded-lg border border-red-200 text-sm mb-6 font-medium">
                {{ session('error') }}
            </div>
        @endif

        {{-- Info Card --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white p-5 rounded-card border border-border shadow-sm">
                <div class="text-xs text-muted font-medium uppercase tracking-wider mb-1">Status Usulan</div>
                @if($pjkades->status === 'approved')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                        Disetujui / SK Bupati Terbit
                    </span>
                @elseif($pjkades->status === 'submitted')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                        Dalam Proses Verifikasi
                    </span>
                @elseif($pjkades->status === 'rejected')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                        Dikembalikan / Minta Revisi
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800">
                        Draft (Lengkapi Berkas)
                    </span>
                @endif
            </div>

            <div class="bg-white p-5 rounded-card border border-border shadow-sm">
                <div class="text-xs text-muted font-medium uppercase tracking-wider mb-1">Calon {{ $pjkades->kategori === 'plt_kades' ? 'Plt' : 'Pj' }} Kades</div>
                @if($pjkades->kategori === 'plt_kades')
                    <div class="text-sm font-bold text-ink">{{ $pjkades->nama_plt ?? '-' }}</div>
                    <div class="text-xs text-muted">Sekretaris Desa / Plt</div>
                @else
                    <div class="text-sm font-bold text-ink">{{ $pjkades->nama_pns ?? '-' }}</div>
                    <div class="text-xs text-muted font-mono">NIP. {{ $pjkades->nip ?? '-' }} ({{ $pjkades->pangkat ?? '-' }})</div>
                @endif
            </div>

            <div class="bg-white p-5 rounded-card border border-border shadow-sm">
                <div class="text-xs text-muted font-medium uppercase tracking-wider mb-1">Progress Berkas</div>
                @php
                    $total = $pjkades->checklists->count();
                    $uploaded = $pjkades->checklists->whereNotNull('file_path')->count();
                    $approved = $pjkades->checklists->where('status_verifikasi', 'disetujui')->count();
                @endphp
                <div class="text-sm font-bold text-ink mb-1">{{ $uploaded }} dari {{ $total }} Berkas Diunggah</div>
                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                    <div class="bg-primary h-2 rounded-full" style="width: {{ $total > 0 ? round(($uploaded/$total)*100) : 0 }}%"></div>
                </div>
            </div>
        </div>        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Kiri: Checklist --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Checklist Table & Upload Form --}}
                <div class="bg-surface rounded-card shadow-sm border border-border overflow-hidden" x-data="{ isSubmitting: false }">
                    <form method="POST" action="{{ route('desa.pjkades.bulkUpload', $pjkades->id) }}" enctype="multipart/form-data" @submit="isSubmitting = true">
                        @csrf
                        <div class="p-6 border-b border-border bg-gray-50 flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-display font-semibold text-ink">Daftar Dokumen Persyaratan & Checklist</h3>
                                <p class="text-xs text-muted mt-0.5">Unggah dokumen sesuai persyaratan.</p>
                            </div>
                        </div>

                        <div class="divide-y divide-border">
                            @forelse ($pjkades->checklists as $index => $item)
                                <div class="px-6 py-4 flex flex-col lg:flex-row lg:items-center justify-between hover:bg-gray-50 transition-colors gap-4">
                                    <div class="flex items-start lg:items-center gap-3 flex-1 pr-4">
                                        <span class="font-medium text-ink flex-shrink-0">{{ $index + 1 }}.</span>
                                        <span class="text-sm text-ink">{{ $item->nama_dokumen }}</span>
                                    </div>

                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 lg:gap-4 flex-shrink-0">
                                        @if($item->status_verifikasi === 'valid')
                                            <div class="flex items-center text-success text-sm font-medium whitespace-nowrap">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Memenuhi
                                            </div>
                                        @elseif($item->status_verifikasi === 'tidak_sesuai')
                                            <div class="flex items-center text-danger text-sm font-medium whitespace-nowrap">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                Tidak Memenuhi
                                            </div>
                                        @else
                                            <span class="text-xs text-muted italic">Belum Diperiksa</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="py-12 text-center text-muted text-sm">Tidak ada checklist dokumen.</div>
                            @endforelse
                        </div>

                        @if($pjkades->metode === 'online' && in_array($pjkades->status, ['draft', 'rejected']))
                        <div class="px-6 py-6 bg-white border-t border-border">
                            <label class="block text-sm font-medium text-ink mb-2">Unggah Keseluruhan Persyaratan (.ZIP / .RAR / .PDF)</label>
                            <input type="file" name="berkas_zip" accept=".zip,.rar,.pdf" 
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-light file:cursor-pointer cursor-pointer focus:outline-none border border-border rounded-md p-2">
                            @if($pjkades->berkas_zip)
                                <div class="mt-3 text-sm text-success flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Berkas telah diunggah. <a href="{{ Storage::disk('public')->url($pjkades->berkas_zip) }}" target="_blank" class="ml-2 underline text-primary">Unduh / Lihat</a>
                                </div>
                            @endif
                        </div>
                        @endif

                        @if($pjkades->catatan_admin)
                        <div class="px-6 py-5 bg-yellow-50 border-t border-yellow-200 text-yellow-900 text-sm">
                            <strong class="block mb-1 font-bold">Catatan dari Admin:</strong>
                            <p class="whitespace-pre-line">{{ $pjkades->catatan_admin }}</p>
                        </div>
                        @endif

                        @if(in_array($pjkades->status, ['draft', 'rejected']))
                        <div class="px-6 py-5 bg-gray-50 border-t border-border flex flex-wrap items-center justify-end gap-3">
                            <button type="submit" name="simpan_draft" value="1" 
                                    :disabled="isSubmitting"
                                    :class="{'opacity-50 cursor-not-allowed': isSubmitting}"
                                    class="px-5 py-2.5 bg-white border border-border rounded-btn text-ink text-sm font-medium hover:bg-gray-50 transition-colors shadow-sm">
                                <span x-show="!isSubmitting">Simpan Draft</span>
                                <span x-show="isSubmitting">Menyimpan...</span>
                            </button>
                            <button type="submit" name="submit_ajuan" value="1" 
                                    :disabled="isSubmitting"
                                    :class="{'opacity-50 cursor-not-allowed': isSubmitting}"
                                    class="px-5 py-2.5 bg-success rounded-btn text-white text-sm font-medium hover:bg-green-700 transition-colors shadow-sm flex items-center">
                                <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-show="!isSubmitting">Kirim Pengajuan (Submit)</span>
                                <span x-show="isSubmitting">Mengunggah...</span>
                            </button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
            
            {{-- Kanan: Tracker --}}
            <div class="lg:col-span-1">
                <div class="bg-surface rounded-card shadow-sm border border-border p-6 sticky top-6">
                    <h3 class="text-base font-display font-semibold text-ink mb-1">Status Proses</h3>
                    <p class="text-xs text-muted mb-4 pb-4 border-b border-border">Pantau tahapan perjalanan usulan SK Kades Anda secara real-time.</p>
                    
                    <x-pjkades-tracker :posisiAktif="$pjkades->posisi_surat ?? 'Berkas Diterima'" :status="$pjkades->status" :pjkades="$pjkades" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
