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
        </div>

        {{-- Checklist Table --}}
        <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
            <div class="p-6 border-b border-border flex items-center justify-between">
                <h3 class="text-base font-bold text-ink">Daftar Dokumen Persyaratan & Checklist</h3>
                <span class="text-xs text-muted font-medium">Unggah file berformat PDF atau Gambar (maks 10MB)</span>
            </div>

            <div class="divide-y divide-border">
                @foreach ($pjkades->checklists as $index => $item)
                    <div class="p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4 hover:bg-gray-50/50 transition-colors">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="w-6 h-6 rounded-full bg-gray-100 text-ink text-xs font-bold flex items-center justify-center font-mono">
                                    {{ $index + 1 }}
                                </span>
                                <h4 class="text-sm font-semibold text-ink">{{ $item->nama_dokumen }}</h4>
                                @if($item->wajib)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">Wajib</span>
                                @endif
                            </div>

                            <div class="ml-8 flex items-center gap-4 text-xs mt-1">
                                @if($item->file_path)
                                    <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="text-primary hover:underline font-medium flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Lihat File Dokumen
                                    </a>
                                    <span class="text-muted">Diunggah: {{ $item->tgl_diunggah ? $item->tgl_diunggah->format('d/m/Y H:i') : '-' }}</span>
                                @else
                                    <span class="text-amber-600 italic">Belum ada file diunggah</span>
                                @endif
                            </div>

                            @if($item->status_verifikasi === 'ditolak' && $item->catatan_revisi)
                                <div class="ml-8 mt-2 p-2.5 bg-red-50 rounded border border-red-200 text-xs text-red-800">
                                    <strong>Catatan Revisi Admin:</strong> {{ $item->catatan_revisi }}
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-3 ml-8 md:ml-0">
                            {{-- Status Badge --}}
                            @if($item->status_verifikasi === 'disetujui')
                                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                    ✓ Disetujui
                                </span>
                            @elseif($item->status_verifikasi === 'ditolak')
                                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                    ✕ Minta Revisi
                                </span>
                            @elseif($item->file_path)
                                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                    Perlu Verifikasi
                                </span>
                            @endif

                            {{-- Upload Form --}}
                            @if($pjkades->status === 'draft' || $pjkades->status === 'rejected' || $item->status_verifikasi === 'ditolak')
                                <form action="{{ route('desa.pjkades.upload', [$pjkades->id, $item->id]) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                                    @csrf
                                    <input type="file" name="file_dokumen" accept=".pdf,.jpg,.jpeg,.png" required
                                        class="text-xs text-ink file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-ink hover:file:bg-gray-200 w-44">
                                    <button type="submit" class="px-3 py-1 bg-primary text-white text-xs font-medium rounded hover:bg-primary-light transition-colors">
                                        {{ $item->file_path ? 'Ganti' : 'Unggah' }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
