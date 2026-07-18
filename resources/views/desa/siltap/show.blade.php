<x-app-layout>
    @section('title', 'Detail Siltap')

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
            <a href="{{ route('desa.siltap.index') }}"
                class="text-sm font-medium text-primary hover:underline flex items-center gap-1 mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
            <h2 class="text-xl font-display font-bold text-ink">Pencairan Siltap: {{ $siltap->nama_bulan }}
                {{ $siltap->tahun }}</h2>
            <p class="text-muted text-sm mt-1">Jumlah Perangkat Aktif Saat Diajukan:
                <strong>{{ $siltap->jumlah_perangkat_aktif }} orang</strong></p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-card shadow-sm border border-border p-6">
                <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Dokumen Diajukan
                </h3>
                <div class="space-y-3">
                    <div><span class="text-xs text-muted block">Surat Rekomendasi Camat</span><a
                            href="{{ asset('storage/' . $siltap->rekomendasi_camat_path) }}" target="_blank"
                            class="text-primary text-sm hover:underline">📄 Unduh</a></div>
                    <div><span class="text-xs text-muted block">Bukti Setor BPJS</span><a
                            href="{{ asset('storage/' . $siltap->bukti_bpjs_path) }}" target="_blank"
                            class="text-primary text-sm hover:underline">📄 Unduh</a></div>
                    <div><span class="text-xs text-muted block">SPJ Bulan Lalu</span><a
                            href="{{ asset('storage/' . $siltap->spj_path) }}" target="_blank"
                            class="text-primary text-sm hover:underline">📄 Unduh</a></div>
                </div>
            </div>

            <div class="bg-white rounded-card shadow-sm border border-border p-6">
                <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Status Verifikasi
                </h3>
                @if($siltap->status === 'disetujui')
                    <div class="p-4 bg-green-50 text-green-800 rounded-md text-sm"><strong>✅ Disetujui</strong>
                        <p class="text-xs mt-1">Pencairan telah disetujui oleh Dinpermasdes.</p>
                    </div>
                @elseif($siltap->status === 'ditolak')
                    <div class="p-4 bg-red-50 text-red-800 rounded-md text-sm"><strong>❌ Ditolak</strong>
                        @if($siltap->catatan_verifikator)
                        <p class="text-xs mt-1">Catatan: {{ $siltap->catatan_verifikator }}</p>@endif
                    </div>
                @elseif($siltap->status === 'dikirim_bkad')
                    <div class="p-4 bg-gray-100 text-gray-800 rounded-md text-sm"><strong>⚫ Telah Dikirim ke
                            BKAD/Bank</strong>
                        <p class="text-xs mt-1">Dana akan segera dicairkan.</p>
                    </div>
                @else
                    <div class="p-4 bg-blue-50 text-blue-800 rounded-md text-sm"><strong>🔵 Menunggu Verifikasi</strong>
                        <p class="text-xs mt-1">Dokumen sedang diperiksa oleh Dinpermasdes.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>