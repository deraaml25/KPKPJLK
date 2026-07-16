<x-app-layout>
    @section('title', 'Detail Ajuan ' . $ajuan->no_registrasi)

    <div class="mb-5 flex items-center justify-between flex-wrap gap-3">
        <a href="{{ route('admin.ajuan.index') }}" class="inline-flex items-center text-sm font-medium text-muted hover:text-ink">
            <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar
        </a>
        @if(in_array($ajuan->status, ['diproses', 'selesai']) && !$ajuan->arsipRekom)
            <a href="{{ route('admin.arsip.create', $ajuan) }}" class="inline-flex items-center px-4 py-2 bg-success text-white text-sm font-medium rounded-btn hover:bg-green-700 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Unggah Surat Rekomendasi
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-5 p-4 rounded-card bg-green-50 border border-green-200 text-green-800 flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 p-4 rounded-card bg-red-50 border border-red-200 text-red-800 flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- =========== KIRI: Info + Checklist =========== --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Info Card --}}
            <div class="bg-surface rounded-card border border-border shadow-sm p-6">
                <div class="flex flex-wrap justify-between items-start gap-3 mb-5">
                    <div>
                        <h2 class="text-lg font-display font-bold text-ink">{{ $ajuan->perangkatDesa->nama }}</h2>
                        <p class="text-sm text-muted">{{ $ajuan->perangkatDesa->jabatan }} — Desa {{ $ajuan->desa->nama_desa }}, Kec. {{ $ajuan->desa->kecamatan->nama_kecamatan }}</p>
                    </div>
                    @php
                        $jenisBadge = match($ajuan->jenisLayanan->nama) {
                            'Pengangkatan'  => 'bg-blue-100 text-blue-700',
                            'Pemberhentian' => 'bg-red-100 text-red-700',
                            'Rotasi'        => 'bg-indigo-100 text-indigo-700',
                            default         => 'bg-gray-100 text-gray-700',
                        };
                        $statusBadge = match($ajuan->status) {
                            'submitted' => ['label' => 'Diajukan', 'css' => 'bg-blue-100 text-blue-700'],
                            'direvisi'  => ['label' => 'Perlu Revisi', 'css' => 'bg-red-100 text-red-700'],
                            'diproses'  => ['label' => 'Diproses', 'css' => 'bg-yellow-100 text-yellow-800'],
                            'selesai'   => ['label' => 'Selesai', 'css' => 'bg-green-100 text-green-700'],
                            'draft'     => ['label' => 'Draft', 'css' => 'bg-gray-100 text-gray-600'],
                            default     => ['label' => $ajuan->status, 'css' => 'bg-gray-100 text-gray-600'],
                        };
                    @endphp
                    <div class="flex gap-2 flex-wrap">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $jenisBadge }}">{{ $ajuan->jenisLayanan->nama }}</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusBadge['css'] }}">{{ $statusBadge['label'] }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-muted mb-1">No. Registrasi</p>
                        <p class="font-mono font-semibold text-ink text-xs">{{ $ajuan->no_registrasi }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-muted mb-1">Tgl Diajukan</p>
                        <p class="font-medium text-ink">{{ $ajuan->tgl_diajukan ? $ajuan->tgl_diajukan->format('d M Y') : '-' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-muted mb-1">Batas SLA</p>
                        <p class="font-medium {{ now()->gt($ajuan->tgl_sla_batas) ? 'text-danger' : 'text-ink' }}">
                            {{ $ajuan->tgl_sla_batas ? $ajuan->tgl_sla_batas->format('d M Y') : '-' }}
                        </p>
                    </div>
                    @if($ajuan->alasanPemberhentian)
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-muted mb-1">Alasan</p>
                        <p class="font-medium text-ink">{{ $ajuan->alasanPemberhentian->nama }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Checklist Dokumen --}}
            <div class="bg-surface rounded-card border border-border shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-border bg-gray-50 flex flex-wrap justify-between items-center gap-2">
                    <div>
                        <h3 class="text-base font-display font-semibold text-ink">Verifikasi Checklist Dokumen</h3>
                        <p class="text-xs text-muted mt-0.5">Ubah status setiap dokumen. Jika ada yang kurang, tulis catatan agar desa tahu.</p>
                    </div>
                    @php
                        $totalLengkap = $ajuan->checklistAjuans->where('status', 'lengkap')->count();
                        $total        = $ajuan->checklistAjuans->count();
                        $pct          = $total > 0 ? round(($totalLengkap / $total) * 100) : 0;
                    @endphp
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-success rounded-full transition-all" style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="font-medium text-ink">{{ $totalLengkap }}/{{ $total }} Lengkap</span>
                    </div>
                </div>

                @forelse($ajuan->checklistAjuans->sortBy('templateChecklist.urutan') as $item)
                    @php
                        $rowBg = match($item->status) {
                            'lengkap'      => 'bg-green-50',
                            'kurang','tidak_sesuai' => 'bg-red-50',
                            'pending'      => 'bg-yellow-50',
                            default        => '',
                        };
                        $dotColor = match($item->status) {
                            'lengkap'      => 'bg-success',
                            'kurang','tidak_sesuai' => 'bg-danger',
                            'pending'      => 'bg-yellow-500',
                            default        => 'bg-gray-300',
                        };
                    @endphp
                    <div class="px-6 py-5 border-b border-border last:border-0 transition-colors {{ $rowBg }}" x-data="{ open: false }">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                            {{-- Kiri: Info Dokumen --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="w-6 h-6 rounded-full bg-white border-2 border-border flex items-center justify-center text-xs font-bold text-muted flex-shrink-0">{{ $item->templateChecklist->urutan }}</span>
                                    <h4 class="font-medium text-ink text-sm">{{ $item->templateChecklist->nama_dokumen }}</h4>
                                    @if($item->templateChecklist->wajib)
                                        <span class="text-danger text-xs font-bold" title="Wajib">★</span>
                                    @endif
                                    <span class="inline-flex items-center gap-1 ml-1">
                                        <span class="w-2 h-2 rounded-full {{ $dotColor }}"></span>
                                        <span class="text-xs text-muted capitalize">{{ str_replace('_', ' ', $item->status) }}</span>
                                    </span>
                                </div>

                                @if($item->file_path)
                                    <a href="{{ Storage::disk('public')->url($item->file_path) }}" target="_blank"
                                       class="inline-flex items-center text-xs font-medium text-primary hover:underline ml-8">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Lihat File Dokumen yang Diunggah
                                    </a>
                                @else
                                    <span class="inline-flex items-center ml-8 text-xs text-muted italic">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                        Desa belum mengunggah dokumen ini
                                    </span>
                                @endif

                                @if($item->catatan)
                                    <div class="ml-8 mt-2 p-2 bg-red-100 border-l-4 border-red-400 rounded-r text-xs text-red-800">
                                        <strong>Catatan:</strong> {{ $item->catatan }}
                                    </div>
                                @endif
                            </div>

                            {{-- Kanan: Form Verifikasi --}}
                            <div class="md:w-56 flex-shrink-0">
                                <form method="POST" action="{{ route('admin.ajuan.verifikasi-checklist', [$ajuan, $item]) }}">
                                    @csrf @method('PATCH')

                                    <select name="status" class="w-full text-xs rounded-lg border-border focus:ring-primary focus:border-primary mb-2 py-2"
                                            onchange="this.form.querySelector('.catatan-box').style.display = ['kurang','tidak_sesuai'].includes(this.value) ? 'block' : 'none'">
                                        <option value="pending"       @selected($item->status === 'pending')>⏳ Menunggu Verifikasi</option>
                                        <option value="lengkap"       @selected($item->status === 'lengkap')>✅ Lengkap / Sesuai</option>
                                        <option value="kurang"        @selected($item->status === 'kurang')>⚠️ Kurang</option>
                                        <option value="tidak_sesuai"  @selected($item->status === 'tidak_sesuai')>❌ Tidak Sesuai</option>
                                    </select>

                                    <div class="catatan-box" style="display: {{ in_array($item->status, ['kurang', 'tidak_sesuai']) ? 'block' : 'none' }}">
                                        <textarea name="catatan" rows="2" placeholder="Tulis catatan untuk desa..."
                                                  class="w-full text-xs rounded-lg border-border focus:ring-primary focus:border-primary mb-2 placeholder-gray-400">{{ $item->catatan }}</textarea>
                                    </div>

                                    <button type="submit" class="w-full text-xs bg-primary text-white font-medium py-2 rounded-lg hover:bg-primary-light transition-colors">
                                        Simpan Status
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-muted text-sm">
                        <p>Belum ada checklist dokumen untuk ajuan ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- =========== KANAN: Milestone Tracker =========== --}}
        <div class="space-y-5">
            <div class="bg-surface rounded-card border border-border shadow-sm p-5 sticky top-6">
                <h3 class="text-base font-display font-semibold text-ink mb-1">Milestone Progress</h3>
                <p class="text-xs text-muted mb-4 pb-4 border-b border-border">Klik "Selesaikan Tahap" untuk melanjutkan proses.</p>

                <x-milestone-tracker :tahapAktif="$tahapAktif" :milestones="$ajuan->milestoneTrackings" />

                @if($tahapAktif <= 9 && $ajuan->status !== 'selesai')
                    <div class="mt-5 pt-5 border-t border-border">
                        <form method="POST" action="{{ route('admin.ajuan.update-milestone', $ajuan) }}">
                            @csrf
                            <input type="hidden" name="tahap" value="{{ $tahapAktif }}">

                            <p class="text-xs font-semibold text-ink mb-3">
                                Selesaikan Tahap {{ $tahapAktif }}:
                            </p>

                            <div class="space-y-2 mb-3">
                                <div>
                                    <label class="text-xs text-muted block mb-1">Tanggal Selesai</label>
                                    <input type="date" name="tgl_selesai" value="{{ date('Y-m-d') }}"
                                           class="w-full text-xs rounded-lg border-border focus:ring-primary focus:border-primary py-2">
                                </div>
                                <div>
                                    <label class="text-xs text-muted block mb-1">Catatan (Opsional)</label>
                                    <textarea name="catatan" rows="2" placeholder="Tambahkan keterangan..."
                                              class="w-full text-xs rounded-lg border-border focus:ring-primary focus:border-primary placeholder-gray-400"></textarea>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-primary hover:bg-primary-light text-white text-sm font-semibold py-2.5 rounded-lg transition-colors">
                                ✓ Selesaikan Tahap {{ $tahapAktif }}
                            </button>
                        </form>
                    </div>
                @elseif($ajuan->status === 'selesai')
                    <div class="mt-5 pt-5 border-t border-border text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-sm font-semibold text-success">Proses Selesai!</p>
                        @if($ajuan->arsipRekom)
                            <a href="{{ route('admin.arsip.download', $ajuan->arsipRekom) }}" class="mt-2 inline-flex items-center text-xs font-medium text-primary hover:underline">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Unduh Surat Rekomendasi
                            </a>
                        @else
                            <a href="{{ route('admin.arsip.create', $ajuan) }}" class="mt-2 inline-flex items-center text-xs font-medium text-primary hover:underline">
                                Unggah Surat Rekomendasi →
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
