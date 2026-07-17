<x-app-layout>
    @section('title', 'Kajian Kelayakan Penataan Wilayah')

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('admin.penataan.index') }}"
                    class="text-sm font-medium text-primary hover:underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    Kembali ke Daftar Penataan
                </a>
                <h2 class="text-xl font-display font-bold text-ink mt-2">Kajian Kelayakan Penataan Wilayah:
                    {{ $penataan->nama_wilayah_baru }}
                </h2>
                <span class="text-xs text-muted block mt-1">Diajukan oleh: <strong
                        class="text-ink font-semibold">{{ $penataan->desa->nama_desa }}</strong></span>
            </div>
            <div>
                @if($penataan->proposal_path)
                    <a href="{{ asset('storage/' . $penataan->proposal_path) }}" target="_blank"
                        class="inline-flex items-center px-4 py-2 border border-border text-ink hover:bg-gray-50 font-medium rounded-btn transition-colors text-sm shadow-sm">
                        Unduh Proposal Kajian
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-card shadow-sm border border-border p-6 mb-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Syarat
                        Kelayakan Demografis (Undang-Undang Desa)</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="p-4 bg-gray-50 rounded border border-border">
                            <span class="text-muted block text-xs font-semibold mb-1 uppercase">Jumlah KK
                                Terdaftar</span>
                            <span class="text-ink text-2xl font-bold font-mono">{{ $penataan->jumlah_kk }} <span
                                    class="text-sm font-normal text-muted">KK</span></span>
                            <div class="text-[10px] text-muted mt-2">Batas minimum UU Desa Jawa: 1.200 KK</div>
                        </div>
                        <div class="p-4 bg-gray-50 rounded border border-border">
                            <span class="text-muted block text-xs font-semibold mb-1 uppercase">Jumlah Penduduk
                                (Jiwa)</span>
                            <span class="text-ink text-2xl font-bold font-mono">{{ $penataan->jumlah_penduduk }} <span
                                    class="text-sm font-normal text-muted">Jiwa</span></span>
                            <div class="text-[10px] text-muted mt-2">Batas minimum UU Desa Jawa: 6.000 Jiwa</div>
                        </div>
                    </div>

                    <div
                        class="p-4 rounded-md text-xs font-semibold {{ $penataan->jumlah_penduduk >= 6000 ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200' }}">
                        @if($penataan->jumlah_penduduk >= 6000)
                            <strong>STATUS KELAYAKAN: LOLOS SYARAT</strong>
                            <p class="font-normal mt-0.5">Usulan memenuhi prasyarat demografis batas kuota jumlah penduduk
                                daerah Jawa menurut UU Desa.</p>
                        @else
                            <strong>STATUS KELAYAKAN: DI BAWAH BATAS MINIMUM</strong>
                            <p class="font-normal mt-0.5">Mohon maaf, data jumlah penduduk pendukung di daerah target
                                penataan wilayah ini berada di bawah batas legal minimal 6.000 jiwa.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-card shadow-sm border border-border p-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Tinjauan &
                        Legalitas</h3>

                    @if($penataan->status === 'approved')
                        <div class="p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm">
                            <strong class="font-bold block">Status: Disetujui (Rekomendasi Terbit)</strong>
                            <p class="text-xs mt-1">Usulan penataan wilayah disetujui, dan surat rekomendasi dinas kabupaten
                                telah disahkan.</p>
                            @if($penataan->rekomendasi_dinas_path)
                                <a href="{{ asset('storage/' . $penataan->rekomendasi_dinas_path) }}" target="_blank"
                                    class="mt-3 inline-flex items-center text-xs font-bold text-primary hover:underline">Unduh
                                    Rekomendasi Dinas →</a>
                            @endif
                        </div>
                    @elseif($penataan->status === 'rejected')
                        <div class="p-4 bg-red-50 text-red-800 rounded border border-red-200 text-sm">
                            <strong class="font-bold block">Status: Ditolak (Tidak Layak)</strong>
                            <p class="text-xs mt-1">Usulan tidak memenuhi prasyarat hukum atau kelayakan demografis.</p>
                        </div>
                    @else
                        <form action="{{ route('admin.penataan.verifikasi', $penataan) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="rekomendasi"
                                    class="block text-xs font-semibold text-ink uppercase tracking-wider mb-1">Unggah Surat
                                    Rekomendasi Dinas (.pdf)</label>
                                <input type="file" name="rekomendasi" id="rekomendasi" required
                                    class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1">
                            </div>

                            <button type="submit"
                                class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors text-sm shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Approve & Terbitkan Rekomendasi
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>