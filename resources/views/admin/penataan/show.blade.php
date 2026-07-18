<x-app-layout>
    @section('title', 'Evaluasi Teknis Penataan Desa')

    <div class="max-w-6xl mx-auto mb-6">
        <a href="{{ route('admin.penataan.index') }}"
            class="text-sm font-medium text-primary hover:underline flex items-center gap-1 mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Daftar Penataan Wilayah
        </a>
        <div class="bg-white rounded-card p-6 shadow-sm border border-border flex justify-between items-center">
            <div>
                <h2 class="text-xl font-display font-bold text-ink flex items-center gap-2">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                        </path>
                    </svg>
                    Dossier Evaluasi Pembentukan Desa — {{ $penataan->desa->nama_desa }}
                </h2>
                <span class="text-xs text-muted block mt-1">Sistem Terhubung dengan Parameter UU Desa No. 6 Tahun
                    2014</span>
            </div>
            @if($penataan->status === 'persiapan')
                @if($penataan->isHampirBatasPersiapan())
                    <span
                        class="text-sm font-bold px-3 py-1.5 bg-red-100 text-red-800 rounded animate-pulse border border-red-200 shadow-sm">⚠️
                        MASA PERSIAPAN KRITIS: Sisa {{ $penataan->sisaHariPersiapan() }} Hari</span>
                @else
                    <span
                        class="text-sm font-bold px-3 py-1.5 bg-yellow-100 text-yellow-800 rounded border border-yellow-200">Masa
                        Evaluasi: Sisa {{ $penataan->sisaHariPersiapan() }} Hari</span>
                @endif
            @endif
        </div>
    </div>

    @if(session('success'))
        <div
            class="max-w-6xl mx-auto p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm mb-6 font-medium shadow-sm">
            {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div
            class="max-w-6xl mx-auto p-4 bg-red-50 text-red-800 rounded border border-red-200 text-sm mb-6 font-medium shadow-sm">
            {{ session('error') }}</div>
    @endif

    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Data Empiris & GIS --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-card shadow-sm border border-border p-6">
                <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Data Analisis
                    Lapangan</h3>

                <div class="grid grid-cols-3 gap-4 mb-6 text-sm">
                    <div class="p-4 bg-gray-50 border border-gray-100 rounded text-center">
                        <span class="block text-muted text-xs mb-1">Total Populasi</span>
                        <span
                            class="text-xl font-bold font-display text-ink">{{ number_format($penataan->jumlah_penduduk) }}
                            <small class="text-muted text-xs">Jiwa</small></span>
                    </div>
                    <div class="p-4 bg-gray-50 border border-gray-100 rounded text-center">
                        <span class="block text-muted text-xs mb-1">Total Kepala Keluarga</span>
                        <span class="text-xl font-bold font-display text-ink">{{ number_format($penataan->jumlah_kk) }}
                            <small class="text-muted text-xs">KK</small></span>
                    </div>
                    <div class="p-4 bg-gray-50 border border-gray-100 rounded text-center">
                        <span class="block text-muted text-xs mb-1">Luas Delineasi</span>
                        <span class="text-xl font-bold font-display text-primary">{{ $penataan->luas_wilayah_km2 }}
                            <small class="text-muted text-xs">Km²</small></span>
                    </div>
                </div>

                <div
                    class="p-4 border border-dashed border-gray-300 bg-gray-50 rounded flex items-center justify-between">
                    <div>
                        <strong class="block text-sm font-bold text-ink">Arsip Pemetaan Geospasial (GIS)</strong>
                        <p class="text-xs text-muted">Berkas batas delineasi / tata ruang administrasi baru.</p>
                    </div>
                    <a href="{{ asset('storage/' . $penataan->peta_geospasial_path) }}" target="_blank"
                        class="px-4 py-2 bg-white border border-border rounded shadow-sm text-sm font-bold text-primary hover:bg-gray-50 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        Inspeksi Dokumen GIS
                    </a>
                </div>
            </div>

            {{-- Audit Laporan --}}
            @if($penataan->diproses_oleh)
                <div
                    class="bg-gray-50 rounded border border-border p-4 text-xs flex justify-between items-center text-muted">
                    <span><strong>Prosesor Terakhir:</strong> {{ $penataan->prosesor->name ?? 'Admin' }}
                        (Dinpermasdes)</span>
                    <span><strong>Waktu Audit Data:</strong> {{ $penataan->diproses_at->format('d/m/Y H:i A') }}</span>
                </div>
            @endif
        </div>

        {{-- Right: Gatekeeper UI --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- Kalkulator UU Box --}}
            <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
                <div
                    class="p-4 border-b border-border {{ $kalkulator['is_valid'] ? 'bg-green-600 text-white' : 'bg-red-600 text-white' }}">
                    <h3 class="text-sm font-bold flex items-center gap-2">
                        @if($kalkulator['is_valid'])
                            ✅ Lolos Uji Algoritma UU Desa
                        @else
                            ❌ Ditolak Sistem Kalkulator UU
                        @endif
                    </h3>
                </div>
                <div class="p-4 text-xs text-ink bg-gray-50">
                    <p class="mb-3 font-medium">Berdasarkan komparasi data lapangan terhadap threshold regulasi PP
                        No.43/2014 Jo PP No.47/2015 mengenai Syarat Pemekaran Desa (Regional Jawa):</p>
                    @if($kalkulator['is_valid'])
                        <div class="text-green-700 font-bold bg-green-50 p-2 border border-green-200 rounded">
                            Data demografi memenuhi kualifikasi batas minimal ambang wilayah baru. Administrasi legal dapat
                            diteruskan ke Bupati / DPRD.
                        </div>
                    @else
                        <ul class="space-y-1 text-red-700 font-bold list-disc pl-4">
                            @foreach($kalkulator['messages'] as $msg)
                                <li>{{ $msg }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            {{-- Decision Panels --}}
            @if($penataan->status === 'ditolak')
                <div
                    class="bg-white rounded-card shadow-sm border border-border p-5 text-center bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-red-50 to-white">
                    <div
                        class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </div>
                    <strong class="block text-red-800 text-sm mb-1">Arsip Ditolak & Ditutup</strong>
                    <p class="text-xs text-muted">{{ $penataan->alasan_penolakan }}</p>
                </div>
            @elseif($penataan->status === 'diajukan')
                <div class="bg-white rounded-card shadow-sm border border-border p-5">
                    <h4 class="text-sm font-bold text-ink mb-3 pb-2 border-b border-border">Penetapan Masa Desa Persiapan
                    </h4>

                    @if(!$kalkulator['is_valid'])
                        <p class="text-xs text-muted mb-4">Sistem menolak mengaktifkan form penetapan hukum karena usulan desa
                            <strong>gagal secara matematis</strong>. Klik tombol di bawah untuk mengeksekusi penolakan permanen.
                        </p>
                        <form action="{{ route('admin.penataan.set_persiapan', $penataan->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded font-bold text-sm shadow-sm transition-colors">
                                Eksekusi Penolakan Otomatis
                            </button>
                        </form>
                    @else
                        <p class="text-xs text-muted mb-4">Secara komputasi data lolos. Masukkan SK Bupati untuk meresmikan
                            tahap uji coba tata kelola pemekaran.</p>
                        <form action="{{ route('admin.penataan.set_persiapan', $penataan->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="block text-xs font-bold text-ink mb-1">Durasi Mandat Uji Coba</label>
                                <select name="lama_uji_coba_tahun" class="w-full text-xs rounded border-border" required>
                                    <option value="1">1 Tahun (Standard Minimal)</option>
                                    <option value="2">2 Tahun (Evaluasi Khusus)</option>
                                    <option value="3">3 Tahun (Batas Maksimal UU)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="block text-xs font-bold text-ink mb-1">Tanggal Berlaku Mandat</label>
                                <input type="date" name="tgl_mulai_persiapan" required
                                    class="w-full text-xs rounded border-border">
                            </div>
                            <div class="mb-4 p-3 bg-gray-50 border border-border rounded">
                                <label class="block text-xs font-bold text-ink mb-1">Salinan Perbup Penetapan Desa Persiapan
                                    (.pdf) <span class="text-red-500">*</span></label>
                                <input type="file" name="perbup_persiapan" accept=".pdf" required
                                    class="w-full text-xs bg-white rounded border border-border">
                            </div>

                            <button type="submit"
                                onclick="return confirm('Tetapkan usulan pemekaran ini menjadi Desa Persiapan yang diakui hukum formil?')"
                                class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-primary text-white font-bold rounded hover:bg-primary-light transition-colors text-sm shadow-sm">
                                Resmikan Status Desa Persiapan
                            </button>
                        </form>
                    @endif
                </div>
            @elseif($penataan->status === 'persiapan')
                <div class="bg-white rounded-card shadow-sm border border-border p-5">
                    <h4 class="text-sm font-bold text-ink mb-3 pb-2 border-b border-border">Penetapan Kode Definitif Akhir
                    </h4>
                    <p class="text-xs text-muted mb-4">Jika masa evaluasi sukses dan telah diterbitkan rekomendasi
                        Kemendagri RI. Masukkan <strong>Kode Kemendagri Registrasi Nasional</strong> untuk meresmikan
                        otonomi penuh desa baru.</p>

                    <form action="{{ route('admin.penataan.set_definitif', $penataan->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-ink mb-1">Kode Desa Resmi Induk (KEMENDAGRI)</label>
                            <input type="text" name="kode_desa_kemendagri" required pattern="[0-9.]+"
                                placeholder="Cth: 33.02.14.2001"
                                class="w-full text-sm font-mono tracking-widest text-center rounded border-border font-bold">
                        </div>
                        <button type="submit"
                            onclick="return confirm('WARNING!\nProses ini tidak dapat dibatalkan. Menetapkan kode desa akan mengubah status Desa Persiapan ini secara permanen menjadi Entitas Mandiri Desa Definitif. Lanjutkan?')"
                            class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-green-600 text-white font-bold rounded hover:bg-green-700 transition-colors text-sm shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Sahkan Menjadi Desa Definitif
                        </button>
                    </form>
                </div>
            @elseif($penataan->status === 'definitif')
                <div
                    class="bg-white rounded-card shadow-sm border border-border p-6 text-center shadow-floating border-t-4 border-t-green-500">
                    <div
                        class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <strong class="block text-green-800 text-lg mb-1 font-display">DESA DEFINITIF (KLIR)</strong>
                    <span
                        class="block text-3xl font-display font-black tracking-widest text-ink mt-2 border-b-2 border-border pb-2 mb-2">{{ $penataan->kode_desa_kemendagri }}</span>
                    <p class="text-xs text-muted">Proses integrasi kewilayahan telah selesai sepenuhnya atas mandat UU.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>