<x-app-layout>
    @section('title', 'Tinjau Usulan Pj Kades')

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('admin.pjkades.index') }}"
                    class="text-sm font-medium text-primary hover:underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    Kembali ke Daftar Usulan
                </a>
                <h2 class="text-xl font-display font-bold text-ink mt-2">Verifikasi Usulan Pj Kades -
                    {{ $pjkades->desa->nama_desa }}</h2>
                <span class="text-xs text-muted block mt-1">Status Berkas: <strong
                        class="text-ink font-semibold capitalize">{{ $pjkades->status }}</strong></span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-card shadow-sm border border-border p-6 mb-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Profile PNS
                        Calon Pj Kades</h3>

                    <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                        <div>
                            <span class="text-muted block text-xs">Nama Lengkap PNS</span>
                            <span class="text-ink font-bold font-display">{{ $pjkades->nama_pns }}</span>
                        </div>
                        <div>
                            <span class="text-muted block text-xs">NIP</span>
                            <span class="text-ink font-mono font-medium">{{ $pjkades->nip }}</span>
                        </div>
                        <div>
                            <span class="text-muted block text-xs">Pangkat / Golongan</span>
                            <span class="text-ink font-medium">{{ $pjkades->pangkat }}</span>
                        </div>
                    </div>

                    <h4 class="text-xs font-semibold text-ink uppercase tracking-wider mb-3">Dokumen Usulan</h4>
                    <div class="space-y-3 text-xs font-semibold text-ink">
                        <div class="p-3 bg-gray-50/50 rounded border border-border flex items-center justify-between">
                            <span>1. Riwayat Hidup / CV Calon</span>
                            <a href="{{ asset('storage/' . $pjkades->riwayat_hidup_path) }}" target="_blank"
                                class="text-primary hover:underline font-medium">Lihat Dokumen</a>
                        </div>
                        <div class="p-3 bg-gray-50/50 rounded border border-border flex items-center justify-between">
                            <span>2. SK Pangkat Terakhir</span>
                            <a href="{{ asset('storage/' . $pjkades->sk_pangkat_path) }}" target="_blank"
                                class="text-primary hover:underline font-medium">Lihat Dokumen</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-card shadow-sm border border-border p-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Verifikasi
                        Disiplin & Penerbitan SK</h3>

                    @if($pjkades->status === 'approved')
                        <div class="p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm">
                            <strong class="font-bold block">Status: Disetujui (SK Terbit)</strong>
                            <p class="text-xs mt-1">PNS bersangkutan bersih dari sanksi hukuman disiplin. SK Bupati Pj
                                Kepala Desa telah ditandatangani.</p>
                            <a href="{{ asset('storage/' . $pjkades->sk_bupati_path) }}" target="_blank"
                                class="mt-3 inline-flex items-center text-xs font-semibold hover:underline">Unduh SK Bupati
                                Pj Kades →</a>
                        </div>
                    @elseif($pjkades->status === 'rejected')
                        <div class="p-4 bg-red-50 text-red-800 rounded border border-red-200 text-sm">
                            <strong class="font-bold block">Status: Ditolak</strong>
                            <p class="text-xs mt-1">Usulan penunjukan ditolak karena terdeteksi PNS sedang menjalani sanksi
                                hukuman disiplin sedang/berat.</p>
                        </div>
                    @else
                        <form action="{{ route('admin.pjkades.generate-sk', $pjkades) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="status_bebas_hukdis"
                                    class="block text-xs font-semibold text-ink uppercase tracking-wider mb-1">Status
                                    Disiplin PNS (Clearance)</label>
                                <select name="status_bebas_hukdis" id="status_bebas_hukdis" required
                                    class="w-full text-xs rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm">
                                    <option value="clean">Bebas Hukuman Disiplin (Clean)</option>
                                    <option value="has_issues">Sedang Terjerat Kasus/Sanksi (Locked)</option>
                                </select>
                            </div>

                            <button type="submit"
                                class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors text-sm shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Proses & Terbitkan SK Bupati
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>