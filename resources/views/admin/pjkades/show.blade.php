<x-app-layout>
    @section('title', 'Tinjau Usulan Pj Kades')

    <div class="max-w-5xl mx-auto">
        {{-- Header --}}
        <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
            <a href="{{ route('admin.pjkades.index') }}" class="text-sm font-medium text-primary hover:underline flex items-center gap-1 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke Daftar Usulan
            </a>
            <h2 class="text-xl font-display font-bold text-ink">Verifikasi Usulan Pj Kades — {{ $pjkades->desa->nama_desa }}</h2>
            <span class="text-xs text-muted block mt-1">Status:
                <strong class="text-ink font-semibold capitalize">{{ $pjkades->status }}</strong>
            </span>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm mb-6 font-medium">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-50 text-red-800 rounded border border-red-200 text-sm mb-6 font-medium">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Kolom Kiri: Profil & Dokumen --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Profil PNS --}}
                <div class="bg-white rounded-card shadow-sm border border-border p-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Profil PNS Calon Pj Kades</h3>
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
                        <div>
                            <span class="text-muted block text-xs">Asal Desa Pengusul</span>
                            <span class="text-ink font-medium">{{ $pjkades->desa->nama_desa }}</span>
                        </div>
                    </div>

                    <h4 class="text-xs font-semibold text-ink uppercase tracking-wider mb-3">Dokumen Usulan</h4>
                    <div class="space-y-3 text-xs font-semibold text-ink">
                        <div class="p-3 bg-gray-50/50 rounded border border-border flex items-center justify-between">
                            <span>1. Surat Usulan dari Camat</span>
                            @if($pjkades->surat_camat_path)
                                <a href="{{ asset('storage/' . $pjkades->surat_camat_path) }}" target="_blank" class="text-primary hover:underline font-medium">Lihat Dokumen</a>
                            @else
                                <span class="text-muted">Belum diunggah</span>
                            @endif
                        </div>
                        <div class="p-3 bg-gray-50/50 rounded border border-border flex items-center justify-between">
                            <span>2. SK Pangkat Terakhir</span>
                            <a href="{{ asset('storage/' . $pjkades->sk_pangkat_path) }}" target="_blank" class="text-primary hover:underline font-medium">Lihat Dokumen</a>
                        </div>
                        <div class="p-3 bg-gray-50/50 rounded border border-border flex items-center justify-between">
                            <span>3. Daftar Riwayat Hidup PNS</span>
                            <a href="{{ asset('storage/' . $pjkades->riwayat_hidup_path) }}" target="_blank" class="text-primary hover:underline font-medium">Lihat Dokumen</a>
                        </div>
                    </div>
                </div>

                {{-- Masa Jabatan Aktif --}}
                @if($pjkades->status === 'approved' && $pjkades->tgl_mulai && $pjkades->tgl_selesai)
                    <div class="bg-white rounded-card shadow-sm border border-border p-6">
                        <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Argo Masa Jabatan Pj Kades</h3>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div class="p-4 bg-primary-soft rounded-lg">
                                <div class="text-xs text-muted font-medium">Mulai Berlaku</div>
                                <div class="text-lg font-bold text-ink mt-1">{{ $pjkades->tgl_mulai->format('d M Y') }}</div>
                            </div>
                            <div class="p-4 bg-primary-soft rounded-lg">
                                <div class="text-xs text-muted font-medium">Berakhir</div>
                                <div class="text-lg font-bold text-ink mt-1">{{ $pjkades->tgl_selesai->format('d M Y') }}</div>
                            </div>
                            <div class="p-4 rounded-lg {{ $pjkades->sudah_berakhir ? 'bg-red-100' : ($pjkades->hampir_berakhir ? 'bg-yellow-100' : 'bg-green-100') }}">
                                <div class="text-xs text-muted font-medium">Sisa Hari</div>
                                <div class="text-3xl font-bold mt-1 {{ $pjkades->sudah_berakhir ? 'text-red-600' : ($pjkades->hampir_berakhir ? 'text-yellow-600' : 'text-green-600') }}">
                                    {{ $pjkades->sudah_berakhir ? 'HABIS' : $pjkades->sisa_hari }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Kolom Kanan: Panel Verifikasi --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-card shadow-sm border border-border p-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Verifikasi Disiplin & Penerbitan SK</h3>

                    @if($pjkades->status === 'approved')
                        <div class="p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm">
                            <strong class="font-bold block">✅ SK Bupati Terbit</strong>
                            <p class="text-xs mt-1">PNS bersangkutan bebas dari sanksi hukuman disiplin. SK Bupati Pj Kades telah diterbitkan dan berlaku hingga {{ $pjkades->tgl_selesai->format('d F Y') }}.</p>
                            <a href="{{ asset('storage/' . $pjkades->sk_bupati_path) }}" target="_blank" class="mt-3 inline-flex items-center text-xs font-semibold hover:underline">Unduh SK Bupati Pj Kades →</a>
                        </div>
                    @elseif($pjkades->status === 'rejected')
                        <div class="p-4 bg-red-50 text-red-800 rounded border border-red-200 text-sm">
                            <strong class="font-bold block">❌ Usulan Ditolak</strong>
                            <p class="text-xs mt-1">PNS terdeteksi sedang menjalani hukuman disiplin sedang/berat dari BKPSDM. Dikembalikan ke Camat untuk pengusulan nama lain.</p>
                        </div>
                    @else
                        <form action="{{ route('admin.pjkades.generate-sk', $pjkades) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Clearance --}}
                            <div class="mb-4">
                                <label for="status_bebas_hukdis" class="block text-xs font-semibold text-ink uppercase tracking-wider mb-1">Status Disiplin PNS (BKPSDM)</label>
                                <select name="status_bebas_hukdis" id="status_bebas_hukdis" required
                                    class="w-full text-xs rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                                    onchange="toggleSkFields(this.value)">
                                    <option value="">— Pilih Status —</option>
                                    <option value="clean">✅ Bebas Hukuman Disiplin (Clean)</option>
                                    <option value="has_issues">❌ Sedang Terjerat Kasus/Sanksi</option>
                                </select>
                            </div>

                            {{-- SK Fields (hanya muncul jika clean) --}}
                            <div id="sk-fields" class="hidden space-y-4 mb-4 p-4 bg-green-50 rounded border border-green-200">
                                <p class="text-xs text-green-800 font-bold mb-2">Penerbitan SK Bupati — Masukkan Masa Berlaku</p>

                                <div>
                                    <label for="tgl_mulai" class="block text-xs font-medium text-ink mb-1">Tanggal Mulai Berlaku SK</label>
                                    <input type="date" name="tgl_mulai" id="tgl_mulai"
                                        class="w-full text-xs rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm">
                                </div>
                                <div>
                                    <label for="tgl_selesai" class="block text-xs font-medium text-ink mb-1">Tanggal Berakhir SK <span class="text-red-500">(max 1 tahun)</span></label>
                                    <input type="date" name="tgl_selesai" id="tgl_selesai"
                                        class="w-full text-xs rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm">
                                </div>
                                <div>
                                    <label for="sk_bupati" class="block text-xs font-medium text-ink mb-1">Upload SK Bupati (.pdf)</label>
                                    <input type="file" name="sk_bupati" id="sk_bupati" accept=".pdf"
                                        class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1">
                                </div>
                            </div>

                            @if ($errors->any())
                                <div class="p-3 bg-red-50 text-red-800 rounded border border-red-200 text-xs mb-4">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif

                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors text-sm shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Proses Keputusan
                            </button>
                        </form>

                        <script>
                            function toggleSkFields(val) {
                                const fields = document.getElementById('sk-fields');
                                if (val === 'clean') {
                                    fields.classList.remove('hidden');
                                    document.getElementById('tgl_mulai').required = true;
                                    document.getElementById('tgl_selesai').required = true;
                                    document.getElementById('sk_bupati').required = true;
                                } else {
                                    fields.classList.add('hidden');
                                    document.getElementById('tgl_mulai').required = false;
                                    document.getElementById('tgl_selesai').required = false;
                                    document.getElementById('sk_bupati').required = false;
                                }
                            }
                        </script>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>