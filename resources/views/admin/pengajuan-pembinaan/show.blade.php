<x-app-layout>
    @section('title', 'Detail Pengajuan — ' . $pengajuanPembinaan->judul_kegiatan)

    <div class="max-w-4xl mx-auto">
        <a href="{{ route('admin.pengajuan-pembinaan.index') }}"
            class="text-sm text-primary hover:underline flex items-center gap-1 mb-5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Daftar Pengajuan
        </a>

        @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-card text-sm">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Detail Pengajuan -->
            <div class="lg:col-span-2 space-y-5">
                <div class="bg-white rounded-card shadow-sm border border-border p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-xl font-display font-bold text-ink">{{ $pengajuanPembinaan->judul_kegiatan }}</h2>
                            <p class="text-sm text-muted mt-1">
                                Diajukan oleh: <span class="font-medium text-ink">{{ $pengajuanPembinaan->desa->nama_desa ?? '-' }}</span>
                                pada {{ $pengajuanPembinaan->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $pengajuanPembinaan->status_color }} ml-4 flex-shrink-0">
                            {{ $pengajuanPembinaan->status_label }}
                        </span>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-medium text-muted uppercase tracking-wide mb-1">Tanggal Kegiatan yang Diusulkan</p>
                            <p class="text-sm text-ink">{{ $pengajuanPembinaan->tanggal_diajukan->format('d M Y') }}</p>
                        </div>

                        @if($pengajuanPembinaan->deskripsi)
                            <div>
                                <p class="text-xs font-medium text-muted uppercase tracking-wide mb-1">Deskripsi Kegiatan</p>
                                <p class="text-sm text-ink leading-relaxed">{{ $pengajuanPembinaan->deskripsi }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Dokumen Persyaratan -->
                <div class="bg-white rounded-card shadow-sm border border-border p-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">📎 Dokumen Persyaratan</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-border">
                            <div>
                                <p class="text-sm font-medium text-ink">Surat Permohonan Narasumber</p>
                                <p class="text-xs text-muted">Dokumen resmi permohonan narasumber ke Dinpermasdes</p>
                            </div>
                            @if($pengajuanPembinaan->file_surat_permohonan)
                                <a href="{{ asset('storage/' . $pengajuanPembinaan->file_surat_permohonan) }}" target="_blank"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary text-white text-xs font-medium rounded-btn hover:bg-primary-light transition-colors flex-shrink-0">
                                    📥 Unduh
                                </a>
                            @else
                                <span class="text-xs text-muted italic">Tidak dilampirkan</span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-border">
                            <div>
                                <p class="text-sm font-medium text-ink">Surat Undangan</p>
                                <p class="text-xs text-muted">Surat undangan resmi untuk Dinpermasdes</p>
                            </div>
                            @if($pengajuanPembinaan->file_undangan)
                                <a href="{{ asset('storage/' . $pengajuanPembinaan->file_undangan) }}" target="_blank"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary text-white text-xs font-medium rounded-btn hover:bg-primary-light transition-colors flex-shrink-0">
                                    📥 Unduh
                                </a>
                            @else
                                <span class="text-xs text-muted italic">Tidak dilampirkan</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Balasan Admin yang sudah ada -->
                @if($pengajuanPembinaan->catatan_admin)
                    <div class="bg-blue-50 rounded-card border border-blue-200 p-5">
                        <h3 class="text-sm font-display font-bold text-blue-800 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                </path>
                            </svg>
                            Balasan Dinpermasdes
                            <span class="text-xs font-normal text-blue-600">({{ $pengajuanPembinaan->dibalas_at?->format('d M Y, H:i') }})</span>
                        </h3>
                        <p class="text-sm text-blue-900 leading-relaxed">{{ $pengajuanPembinaan->catatan_admin }}</p>
                    </div>
                @endif
            </div>

            <!-- Form Balas -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-card shadow-sm border border-border p-6 sticky top-4">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">
                        💬 Berikan Balasan
                    </h3>

                    <form action="{{ route('admin.pengajuan-pembinaan.balas', $pengajuanPembinaan) }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label for="status" class="block text-sm font-medium text-ink mb-1">Status Keputusan <span class="text-red-500">*</span></label>
                            <select name="status" id="status" required
                                class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm text-sm">
                                <option value="disetujui" {{ $pengajuanPembinaan->status === 'disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                                <option value="ditolak" {{ $pengajuanPembinaan->status === 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                                <option value="selesai" {{ $pengajuanPembinaan->status === 'selesai' ? 'selected' : '' }}>🎉 Selesai</option>
                            </select>
                        </div>

                        <div>
                            <label for="catatan_admin" class="block text-sm font-medium text-ink mb-1">Catatan / Balasan <span class="text-red-500">*</span></label>
                            <textarea name="catatan_admin" id="catatan_admin" rows="6" required
                                class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm text-sm"
                                placeholder="Tulis balasan resmi, informasi jadwal, atau alasan penolakan...">{{ $pengajuanPembinaan->catatan_admin }}</textarea>
                        </div>

                        <button type="submit"
                            class="w-full px-4 py-2.5 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">
                            Kirim Balasan
                        </button>
                    </form>

                    @if($errors->any())
                        <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded text-xs text-red-700">
                            @foreach($errors->all() as $err)
                                <p>{{ $err }}</p>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
