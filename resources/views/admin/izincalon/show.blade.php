<x-app-layout>
    @section('title', 'Tinjau Izin Calon Kades')

    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
            <a href="{{ route('admin.izincalon.index') }}"
                class="text-sm font-medium text-primary hover:underline flex items-center gap-1 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Daftar Permohonan
            </a>
            <h2 class="text-xl font-display font-bold text-ink">Tinjau Permohonan Izin Calon Kades —
                {{ $izincalon->desa->nama_desa }}</h2>
            <span class="text-xs text-muted">Status: <strong
                    class="text-ink capitalize">{{ $izincalon->status }}</strong></span>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm mb-6 font-medium">
                {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-50 text-red-800 rounded border border-red-200 text-sm mb-6 font-medium">
                {{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Kiri: Profil & Dokumen --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Profil Calon --}}
                <div class="bg-white rounded-card shadow-sm border border-border p-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Profil Calon
                        Kepala Desa</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm mb-6">
                        <div>
                            <span class="text-muted block text-xs">Nama Lengkap</span>
                            <span class="text-ink font-bold font-display">{{ $izincalon->nama_calon }}</span>
                        </div>
                        <div>
                            <span class="text-muted block text-xs">Jenis Calon</span>
                            <span class="text-ink font-medium">{{ $izincalon->label_jenis_calon }}</span>
                        </div>
                        <div>
                            <span class="text-muted block text-xs">Jabatan / Instansi Saat Ini</span>
                            <span class="text-ink font-medium">{{ $izincalon->jabatan_sekarang }}</span>
                        </div>
                        @if($izincalon->jenis_calon === 'kades' && $izincalon->tgl_cuti_mulai)
                            <div>
                                <span class="text-muted block text-xs">Periode Cuti Kampanye</span>
                                <span class="text-ink font-medium font-display">
                                    {{ $izincalon->tgl_cuti_mulai->format('d M Y') }} —
                                    {{ $izincalon->tgl_cuti_selesai->format('d M Y') }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <span class="text-muted block text-xs">Desa Pengusul</span>
                            <span class="text-ink font-medium">{{ $izincalon->desa->nama_desa }}</span>
                        </div>
                        <div>
                            <span class="text-muted block text-xs">Tanggal Pengajuan</span>
                            <span class="text-ink font-medium">{{ $izincalon->created_at->format('d M Y') }}</span>
                        </div>
                    </div>

                    <h4 class="text-xs font-semibold text-ink uppercase tracking-wider mb-3">Dokumen Permohonan</h4>
                    <div class="space-y-3 text-xs font-semibold text-ink">
                        <div class="p-3 bg-gray-50/50 rounded border border-border flex items-center justify-between">
                            <span>1. Surat Permohonan Izin Cuti/Pencalonan</span>
                            @if($izincalon->surat_permohonan_path)
                                <a href="{{ asset('storage/' . $izincalon->surat_permohonan_path) }}" target="_blank"
                                    class="text-primary hover:underline font-medium">Lihat Dokumen</a>
                            @else <span class="text-muted font-normal">Belum diunggah</span> @endif
                        </div>
                        <div class="p-3 bg-gray-50/50 rounded border border-border flex items-center justify-between">
                            <span>2. Berkas Syarat Administratif</span>
                            @if($izincalon->berkas_syarat_path)
                                <a href="{{ asset('storage/' . $izincalon->berkas_syarat_path) }}" target="_blank"
                                    class="text-primary hover:underline font-medium">Lihat Dokumen</a>
                            @else <span class="text-muted font-normal">Belum diunggah</span> @endif
                        </div>
                        @if($izincalon->surat_pengunduran_diri_path)
                            <div
                                class="p-3 bg-yellow-50/50 rounded border border-yellow-200 flex items-center justify-between">
                                <span>3. Surat Pengunduran Diri (Perangkat Desa)</span>
                                <a href="{{ asset('storage/' . $izincalon->surat_pengunduran_diri_path) }}" target="_blank"
                                    class="text-primary hover:underline font-medium">Lihat Dokumen</a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Audit Trail --}}
                @if($izincalon->status !== 'submitted')
                    <div class="bg-white rounded-card shadow-sm border border-border p-6">
                        <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Rekam Jejak
                            Verifikasi</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-muted block text-xs">Diverifikasi Oleh</span>
                                <span class="text-ink font-bold">{{ $izincalon->verifikator->name ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="text-muted block text-xs">Waktu Verifikasi</span>
                                <span
                                    class="text-ink font-medium">{{ $izincalon->verified_at?->format('d M Y, H:i') ?? '—' }}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-muted block text-xs">Catatan Inspektorat</span>
                                <span class="text-ink font-medium">{{ $izincalon->catatan_inspektorat ?? '—' }}</span>
                            </div>
                        </div>
                        @if($izincalon->surat_izin_bupati_path)
                            <a href="{{ asset('storage/' . $izincalon->surat_izin_bupati_path) }}" target="_blank"
                                class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-btn font-medium text-sm transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Unduh Surat Izin Bupati
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Kanan: Panel Gatekeeper --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-card shadow-sm border border-border p-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">🔐 Panel
                        Inspektorat & Penerbitan Izin</h3>

                    @if($izincalon->status === 'approved')
                        <div class="p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm">
                            <strong class="font-bold block">✅ Surat Izin Bupati Telah Diterbitkan</strong>
                            <p class="text-xs mt-1">Calon dinyatakan bebas temuan Inspektorat. Surat Izin resmi dapat
                                diunduh sebagai syarat pendaftaran ke Panitia Pilkades.</p>
                        </div>
                    @elseif($izincalon->status === 'rejected')
                        <div class="p-4 bg-red-50 text-red-800 rounded border border-red-200 text-sm">
                            <strong class="font-bold block">❌ Permohonan Ditolak</strong>
                            <p class="text-xs mt-1">{{ $izincalon->catatan_inspektorat }}</p>
                        </div>
                    @else
                        {{-- Active Gatekeeper Form --}}
                        <div
                            class="p-3 bg-amber-50 rounded border border-amber-200 text-xs text-amber-800 font-medium mb-4">
                            ⚠️ Dinpermasdes wajib melakukan cek rekam jejak calon ke sistem Inspektorat Daerah sebelum
                            menerbitkan izin Bupati.
                        </div>

                        <form action="{{ route('admin.izincalon.verifikasi', $izincalon) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4">
                                <label class="block text-xs font-semibold text-ink uppercase tracking-wider mb-2">Status
                                    Rekam Jejak Inspektorat</label>

                                <div class="space-y-2">
                                    <label
                                        class="flex items-center gap-3 p-3 bg-green-50 border border-green-200 rounded cursor-pointer hover:bg-green-100 transition-colors">
                                        <input type="radio" name="has_temuan_inspektorat" value="0" required
                                            onchange="toggleInspektField(false)" class="text-green-600">
                                        <div>
                                            <span class="text-sm font-semibold text-green-800">✅ Bebas Temuan</span>
                                            <p class="text-xs text-green-700">Calon tidak memiliki temuan kerugian
                                                negara/desa yang belum diselesaikan.</p>
                                        </div>
                                    </label>
                                    <label
                                        class="flex items-center gap-3 p-3 bg-red-50 border border-red-200 rounded cursor-pointer hover:bg-red-100 transition-colors">
                                        <input type="radio" name="has_temuan_inspektorat" value="1"
                                            onchange="toggleInspektField(true)" class="text-red-600">
                                        <div>
                                            <span class="text-sm font-semibold text-red-800">❌ Ada Temuan Aktif</span>
                                            <p class="text-xs text-red-700">Sistem akan mengunci penerbitan izin dan
                                                permohonan ditolak otomatis.</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="catatan_inspektorat" class="block text-xs font-medium text-ink mb-1">Catatan
                                    dari Dinpermasdes</label>
                                <textarea name="catatan_inspektorat" id="catatan_inspektorat" rows="3"
                                    class="w-full text-xs rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                                    placeholder="Opsional. Tulis catatan penolakan jika ada temuan..."></textarea>
                            </div>

                            {{-- Upload SK Izin Bupati (hanya muncul jika bebas) --}}
                            <div id="sk-upload-field" class="hidden mb-4 p-3 bg-green-50 rounded border border-green-200">
                                <label for="surat_izin_bupati" class="block text-xs font-medium text-ink mb-1">Upload Surat
                                    Izin Bupati (.pdf) <span class="text-red-500">*</span></label>
                                <input type="file" name="surat_izin_bupati" id="surat_izin_bupati" accept=".pdf"
                                    class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1">
                            </div>

                            @if ($errors->any())
                                <div class="p-3 bg-red-50 text-red-800 rounded text-xs mb-4">
                                    @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                                </div>
                            @endif

                            <button type="submit"
                                class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors text-sm shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Proses Keputusan
                            </button>
                        </form>

                        <script>
                            function toggleInspektField(hasTemu) {
                                const field = document.getElementById('sk-upload-field');
                                const input = document.getElementById('surat_izin_bupati');
                                if (hasTemu) {
                                    field.classList.add('hidden');
                                    input.required = false;
                                } else {
                                    field.classList.remove('hidden');
                                    input.required = true;
                                }
                            }
                        </script>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>