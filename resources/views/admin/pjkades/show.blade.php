<x-app-layout>
    @section('title', 'Verifikasi Usulan SK Kades')

    <div class="max-w-6xl mx-auto mb-8">
        {{-- Header --}}
        <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
            <a href="{{ route('admin.pjkades.index') }}" class="text-sm font-medium text-primary hover:underline flex items-center gap-1 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke Daftar Evaluasi SK Kades
            </a>
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
                        Verifikasi Usulan SK Kades — Desa {{ $pjkades->desa->nama_desa }} (Kec. {{ $pjkades->desa->kecamatan->nama_kecamatan ?? '-' }})
                    </h2>
                    <p class="text-muted text-sm mt-1">
                        Alasan: <strong class="text-ink">{{ $pjkades->alasan_nama ?? ($pjkades->alasanPemberhentian->nama ?? '-') }}</strong>
                    </p>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Kolom Kiri & Tengah: Profil & Verification Checklist --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Profil Calon --}}
                <div class="bg-white rounded-card shadow-sm border border-border p-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">
                        Profil Calon {{ $pjkades->kategori === 'plt_kades' ? 'Plt Kepala Desa (Sekdes)' : 'Pj Kepala Desa (PNS)' }}
                    </h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        @if($pjkades->kategori === 'plt_kades')
                            <div>
                                <span class="text-muted block text-xs">Nama Sekretaris Desa / Plt</span>
                                <span class="text-ink font-bold font-display">{{ $pjkades->nama_plt ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-muted block text-xs">NIP / NIPD</span>
                                <span class="text-ink font-mono font-medium">{{ $pjkades->nip_plt ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-muted block text-xs">Pangkat / Jabatan</span>
                                <span class="text-ink font-medium">{{ $pjkades->pangkat_plt ?? 'Sekretaris Desa' }}</span>
                            </div>
                        @else
                            <div>
                                <span class="text-muted block text-xs">Nama Lengkap PNS</span>
                                <span class="text-ink font-bold font-display">{{ $pjkades->nama_pns ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-muted block text-xs">NIP PNS</span>
                                <span class="text-ink font-mono font-medium">{{ $pjkades->nip ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-muted block text-xs">Pangkat / Golongan</span>
                                <span class="text-ink font-medium">{{ $pjkades->pangkat ?? '-' }}</span>
                            </div>
                        @endif
                        <div>
                            <span class="text-muted block text-xs">Tanggal Diajukan</span>
                            <span class="text-ink font-medium">{{ $pjkades->tgl_diajukan ? $pjkades->tgl_diajukan->format('d M Y') : '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Checklist Dokumen Verification Table --}}
                <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
                    <div class="p-6 border-b border-border flex items-center justify-between">
                        <h3 class="text-md font-display font-bold text-ink">Pemeriksaan & Verifikasi Dokumen Persyaratan</h3>
                        @php
                            $approvedCount = $pjkades->checklists->where('status_verifikasi', 'disetujui')->count();
                            $totalCount = $pjkades->checklists->count();
                        @endphp
                        <span class="text-xs font-bold px-2.5 py-1 bg-gray-100 rounded-full text-ink">
                            {{ $approvedCount }} / {{ $totalCount }} Disetujui
                        </span>
                    </div>

                    <div class="divide-y divide-border">
                        @foreach($pjkades->checklists as $index => $item)
                            <div class="p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 hover:bg-gray-50/50">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="w-5 h-5 rounded-full bg-gray-100 text-ink text-xs font-bold flex items-center justify-center font-mono">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="text-sm font-semibold text-ink">{{ $item->nama_dokumen }}</span>
                                        @if($item->wajib)
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">Wajib</span>
                                        @endif
                                    </div>

                                    <div class="ml-7 text-xs">
                                        @if($item->file_path)
                                            <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="text-primary hover:underline font-medium inline-flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Buka / Download File
                                            </a>
                                            <span class="text-muted ml-2">Diunggah: {{ $item->tgl_diunggah ? $item->tgl_diunggah->format('d/m/Y H:i') : '-' }}</span>
                                        @else
                                            <span class="text-amber-600 italic">Belum diunggah oleh desa</span>
                                        @endif

                                        @if($item->catatan_revisi)
                                            <div class="mt-1 text-red-600 font-medium">Catatan Revisi: {{ $item->catatan_revisi }}</div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Action Form Verifikasi --}}
                                <div class="flex items-center gap-2 ml-7 md:ml-0" x-data="{ showRevisi: false }">
                                    @if($item->status_verifikasi === 'disetujui')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-green-100 text-green-800">
                                            ✓ Disetujui
                                        </span>
                                    @endif

                                    @if($item->file_path)
                                        <form action="{{ route('admin.pjkades.verify-checklist', [$pjkades->id, $item->id]) }}" method="POST" class="inline flex items-center gap-2">
                                            @csrf
                                            <input type="hidden" name="status_verifikasi" value="disetujui">
                                            <button type="submit" class="px-2.5 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition-colors">
                                                Setujui
                                            </button>
                                        </form>

                                        <button type="button" @click="showRevisi = !showRevisi" class="px-2.5 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors">
                                            Tolak / Revisi
                                        </button>

                                        <div x-show="showRevisi" class="mt-2 p-2 bg-gray-50 border rounded text-xs" style="display: none;">
                                            <form action="{{ route('admin.pjkades.verify-checklist', [$pjkades->id, $item->id]) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status_verifikasi" value="ditolak">
                                                <input type="text" name="catatan_revisi" required placeholder="Tuliskan catatan revisi..." class="w-full text-xs rounded border-gray-300 p-1 mb-1">
                                                <button type="submit" class="w-full bg-red-600 text-white py-1 rounded text-xs font-bold">Kirim Revisi</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Verifikasi Disiplin & Penerbitan SK Bupati --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-card shadow-sm border border-border p-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Penerbitan SK & Penetapan Masa Jabatan</h3>

                    @if($pjkades->status === 'approved')
                        <div class="p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm mb-4">
                            <strong class="font-bold block">✓ SK Berhasil Diterbitkan</strong>
                            <p class="text-xs mt-1">SK Bupati / Camat sudah resmi berlaku sampai <strong>{{ $pjkades->tgl_selesai ? $pjkades->tgl_selesai->format('d M Y') : '-' }}</strong>.</p>
                        </div>
                        @if($pjkades->sk_bupati_path)
                            <a href="{{ asset('storage/' . $pjkades->sk_bupati_path) }}" target="_blank" class="w-full block text-center py-2 bg-primary text-white text-xs font-bold rounded hover:bg-primary-light transition-colors">
                                Download File SK Bupati / Camat
                            </a>
                        @endif
                    @else
                        <form action="{{ route('admin.pjkades.generate-sk', $pjkades->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            @if($pjkades->kategori === 'pj_kades')
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-ink mb-1">Verifikasi Rekam Jejak / Hukdis PNS <span class="text-red-500">*</span></label>
                                    <select name="status_bebas_hukdis" required class="w-full text-xs rounded border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm">
                                        <option value="clean">Bersih / Bebas Hukdis</option>
                                        <option value="has_issues">Ada Temuan / Sedang Menjalani Hukdis</option>
                                    </select>
                                </div>
                            @endif

                            <div class="mb-4">
                                <label class="block text-xs font-bold text-ink mb-1">Unggah SK Bupati / Camat <span class="text-red-500">*</span></label>
                                <input type="file" name="sk_bupati" accept=".pdf" required class="w-full text-xs rounded border border-border bg-white text-ink p-1 shadow-sm">
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs font-bold text-ink mb-1">Tanggal Mulai SK <span class="text-red-500">*</span></label>
                                <input type="date" name="tgl_mulai" required value="{{ old('tgl_mulai', now()->format('Y-m-d')) }}" class="w-full text-xs rounded border-border text-ink bg-white shadow-sm">
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs font-bold text-ink mb-1">Tanggal Berakhir SK (Maks 1 Tahun) <span class="text-red-500">*</span></label>
                                <input type="date" name="tgl_selesai" required value="{{ old('tgl_selesai', now()->addYear()->format('Y-m-d')) }}" class="w-full text-xs rounded border-border text-ink bg-white shadow-sm">
                            </div>

                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyetujui dan menerbitkan SK ini?')"
                                class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded text-xs shadow-sm transition-colors">
                                Terbitkan SK & Setujui Usulan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>