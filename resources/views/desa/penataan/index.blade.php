<x-app-layout>
    @section('title', 'e-Penataan & Pemekaran Desa')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <h2 class="text-xl font-display font-bold text-ink">Dashboard Penataan Kewilayahan</h2>
        <p class="text-muted text-sm mt-1">Sistem informasi pendataan spasial dan integrasi status hukum Desa Persiapan
            hingga menjadi Desa Definitif.</p>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm mb-6 font-bold">
            {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50 text-red-800 rounded border border-red-200 text-sm mb-6 font-bold">{{ session('error') }}
        </div>
    @endif

    {{-- Kiri: Form Pengajuan (Hanya tampil jika belum ada yg diproses) --}}
    @if(!$penataan || $penataan->status === 'ditolak')
        <div class="bg-white rounded-card shadow-sm border border-border p-6 max-w-3xl">
            <h3 class="text-md font-display font-bold text-ink mb-2">Ajukan Pemekaran / Perubahan Status</h3>
            <p class="text-xs text-muted mb-6">Sistem akan melakukan kalkulasi otomatis berdasarkan syarat UU Desa sebelum
                permohonan diteruskan ke Provinsi.</p>

            <form action="{{ route('desa.penataan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-ink mb-1">Total Penduduk (Jiwa) <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_penduduk" required min="1"
                            class="w-full text-sm rounded bg-white shadow-sm border-border" placeholder="Contoh: 6500">
                        <span class="text-[10px] text-muted mt-1 inline-block">Min. Jawa: 6000 jiwa</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink mb-1">Total Kepala Keluarga <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_kk" required min="1"
                            class="w-full text-sm rounded bg-white shadow-sm border-border" placeholder="Contoh: 1250">
                        <span class="text-[10px] text-muted mt-1 inline-block">Min. Jawa: 1200 KK</span>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-ink mb-1">Luas Wilayah Total (Km²) <span
                                class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="luas_wilayah_km2" required
                            class="w-full text-sm rounded bg-white shadow-sm border-border" placeholder="Contoh: 4.5">
                    </div>
                </div>

                <div class="p-4 bg-amber-50 border border-amber-200 rounded mb-6">
                    <label class="block text-sm font-bold text-amber-800 mb-1">Upload Peta Geospasial / SHP (.zip / .pdf)
                        <span class="text-red-500">*</span></label>
                    <p class="text-xs text-amber-700 mb-2">Peta kontur, tata ruang, batas delineasi wilayah dari BIG atau
                        BPN rujukan.</p>
                    <input type="file" name="peta_geospasial" accept=".zip,.pdf" required
                        class="w-full text-sm rounded border border-amber-300 bg-white p-1">
                </div>

                <button type="submit"
                    class="px-6 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors text-sm shadow-sm inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Ajukan ke Kalkulator Uji UU Desa
                </button>
            </form>
        </div>

    @else
        {{-- Kanan: Detail Tracking jika sudah mengajukan --}}
        <div class="max-w-4xl bg-white rounded-card shadow-sm border border-border p-8">
            <h3 class="text-lg font-display font-bold text-ink mb-4 pb-2 border-b border-border flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                    </path>
                </svg>
                Status Integrasi Wilayah
            </h3>

            {{-- Progress Status --}}
            <div class="mb-8 p-4 rounded-lg flex items-center gap-4 bg-gray-50 border border-border">
                @if($penataan->status === 'diajukan')
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-full animate-pulse"><svg class="w-6 h-6" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg></div>
                    <div>
                        <strong class="text-blue-800 text-lg">Dalam Kajian UU / Teknis</strong>
                        <p class="text-xs text-muted">Proposal Anda sedang diverifikasi secara otomatis dari sistem dan tim
                            hukum Dinpermasdes.</p>
                    </div>
                @elseif($penataan->status === 'persiapan')
                    <div class="p-3 bg-yellow-100 text-yellow-700 rounded-full"><svg class="w-6 h-6" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg></div>
                    <div>
                        <strong class="text-yellow-800 text-lg">Masa Otonomi Desa Persiapan</strong>
                        <p class="text-xs text-muted">Wilayah Anda sedang dalam masa uji coba sesuai mandat Bupati.</p>
                    </div>
                @elseif($penataan->status === 'definitif')
                    <div class="p-3 bg-green-100 text-green-700 rounded-full"><svg class="w-6 h-6" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg></div>
                    <div>
                        <strong class="text-green-800 text-lg">SAH! Desa Definitif</strong>
                        <p class="text-xs text-green-700">Kode register Kemendagri telah diterbitkan. Desa resmi berdiri mandiri
                            dengan legal standing.</p>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h4 class="text-xs font-bold uppercase text-muted tracking-wider mb-3">Data Empiris Diajukan</h4>
                    <ul class="space-y-2 text-sm">
                        <li class="flex justify-between border-b border-gray-100 pb-1"><span>Total Penduduk</span><strong
                                class="text-ink">{{ number_format($penataan->jumlah_penduduk) }} Jiwa</strong></li>
                        <li class="flex justify-between border-b border-gray-100 pb-1"><span>Total KK</span><strong
                                class="text-ink">{{ number_format($penataan->jumlah_kk) }} KK</strong></li>
                        <li class="flex justify-between border-b border-gray-100 pb-1"><span>Luas Wilayah</span><strong
                                class="text-ink">{{ $penataan->luas_wilayah_km2 }} Km²</strong></li>
                        <li class="flex items-center gap-2 pt-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <a href="{{ asset('storage/' . $penataan->peta_geospasial_path) }}" target="_blank"
                                class="text-primary hover:underline text-xs font-semibold">Tinjau Arsip Spasial</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase text-muted tracking-wider mb-3">Produk Hukum</h4>
                    @if($penataan->perbup_persiapan_path)
                        <div class="bg-gray-50 border border-border p-3 rounded mb-3">
                            <span class="block text-xs font-medium text-ink mb-1">Perbup Desa Persiapan</span>
                            <a href="{{ asset('storage/' . $penataan->perbup_persiapan_path) }}" target="_blank"
                                class="inline-flex items-center text-xs text-primary font-bold hover:underline">Unduh Salinan SK
                                Bupati</a>
                        </div>
                        <ul class="text-xs space-y-1">
                            <li class="text-muted">Mulai Uji Coba: <span
                                    class="text-ink font-medium">{{ $penataan->tgl_mulai_persiapan?->format('d M Y') }}</span>
                            </li>
                            <li class="text-muted">Batas Maksimal Uji Coba: <span
                                    class="text-ink font-medium">{{ $penataan->tgl_batas_persiapan?->format('d M Y') }}</span>
                            </li>
                        </ul>
                    @else
                        <span class="text-xs text-muted block bg-gray-50 p-2 rounded italic">Belum ada instrumen hukum yang
                            diterbitkan.</span>
                    @endif
                </div>
            </div>

            @if($penataan->status === 'definitif')
                <div class="mt-6 p-4 bg-primary text-white rounded-lg shadow-floating text-center">
                    <span class="block text-sm opacity-90 mb-1">KODE REGISTER KEMENDAGRI (SAH)</span>
                    <span class="text-4xl font-display font-black tracking-widest">{{ $penataan->kode_desa_kemendagri }}</span>
                </div>
            @endif
        </div>
    @endif

</x-app-layout>