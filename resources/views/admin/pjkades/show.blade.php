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
                @if($pjkades->metode === 'online' && $pjkades->berkas_zip)
                    <div class="bg-blue-50 border border-blue-200 rounded-card p-4 mb-6 flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-bold text-blue-900">Berkas Persyaratan (ZIP/PDF)</h4>
                            <p class="text-xs text-blue-700">Desa telah mengunggah keseluruhan berkas persyaratan dalam satu file.</p>
                        </div>
                        <a href="{{ Storage::disk('public')->url($pjkades->berkas_zip) }}" target="_blank"
                           class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-btn shadow-sm hover:bg-blue-700 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Unduh Berkas
                        </a>
                    </div>
                @endif

                {{-- Checklist Dokumen Verification Table --}}
                <div class="bg-surface rounded-card shadow-sm border border-border overflow-hidden">
                    <div class="px-5 py-4 border-b border-border bg-gray-50 flex items-center justify-between">
                        <h3 class="text-base font-display font-semibold text-ink">Verifikasi Syarat Formil</h3>
                        @php
                            $approvedCount = $pjkades->checklists->where('status_verifikasi', 'valid')->count();
                            $totalCount = $pjkades->checklists->count();
                        @endphp
                        <span class="text-xs font-bold px-2.5 py-1 bg-white border border-border rounded-full text-ink shadow-sm" id="memenuhi-count">
                            <span id="approved-count-val">{{ $approvedCount }}</span> / {{ $totalCount }} Memenuhi
                        </span>
                    </div>

                    <div class="divide-y divide-border">
                        @foreach($pjkades->checklists as $index => $item)
                            <div class="p-4 border-l-4 transition-colors {{ $item->status_verifikasi == 'valid' ? 'border-green-500 bg-green-50/40' : ($item->status_verifikasi == 'tidak_sesuai' ? 'border-red-500 bg-red-50/40' : 'border-amber-400 bg-amber-50/30') }}">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-white text-xs font-bold text-ink border border-border shadow-sm flex-shrink-0">{{ $index + 1 }}</span>
                                    
                                    <div class="flex-1 flex flex-col sm:flex-row sm:items-center gap-2 justify-between">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <p class="text-sm font-semibold text-ink leading-tight">
                                                {{ $item->nama_dokumen }}
                                            </p>

                                            @if($item->file_path)
                                                <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank"
                                                    class="ml-2 inline-flex items-center text-xs px-2 py-1 bg-white hover:bg-gray-50 border border-gray-300 rounded font-medium text-ink transition-colors shadow-sm">
                                                    Lihat Dokumen
                                                </a>
                                            @endif
                                        </div>

                                        <form action="{{ route('admin.pjkades.verify-checklist', [$pjkades->id, $item->id]) }}" method="POST" class="verify-form flex-shrink-0 ml-auto sm:ml-4" data-url="{{ route('admin.pjkades.verify-checklist', [$pjkades->id, $item->id]) }}">
                                            @csrf
                                            <input type="hidden" name="status_verifikasi" value="tidak_sesuai">
                                            <input type="checkbox" name="status_verifikasi" value="valid" 
                                                   class="verify-checkbox w-7 h-7 text-blue-400 focus:ring-blue-400 border-gray-300 rounded shadow-sm cursor-pointer transition-colors" 
                                                   {{ $item->status_verifikasi == 'valid' ? 'checked' : '' }}
                                                   title="Tandai Sesuai">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Kolom Catatan Admin --}}
                <div class="mt-6 bg-surface rounded-card shadow-sm border border-border p-6 relative">
                    <form action="{{ route('admin.pjkades.update-catatan', $pjkades->id) }}" method="POST">
                        @csrf
                        <label class="block text-sm font-display font-semibold text-ink mb-2">Evaluasi Syarat Formil</label>
                        <p class="text-xs text-muted mb-3">Tuliskan jika ada syarat yang kurang (khususnya untuk metode offline) atau perbaikan yang harus dilakukan desa.</p>
                        
                        <textarea name="catatan_admin" rows="3" placeholder="Tulis catatan jika ada berkas yang kurang/salah..." 
                                  class="w-full text-sm rounded-lg border-border focus:ring-primary focus:border-primary placeholder-gray-400">{{ old('catatan_admin', $pjkades->catatan_admin) }}</textarea>
                                  
                        <div class="mt-3 flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm">
                                Simpan Catatan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Kolom Kanan: Disposisi & Penerbitan SK --}}
            <div class="lg:col-span-1 space-y-6">

                <div class="bg-surface rounded-card shadow-sm border border-border p-6">
                    <h3 class="text-base font-display font-semibold text-ink mb-1">Status Proses</h3>
                    <p class="text-xs text-muted mb-4 pb-4 border-b border-border">Pantau tahapan perjalanan usulan SK Kades.</p>
                    
                    <x-pjkades-tracker :posisiAktif="$pjkades->posisi_surat ?? 'Berkas Diterima'" :status="$pjkades->status" :pjkades="$pjkades" />
                </div>

                @php
                    $posisiOptions = [
                        'Berkas Diterima',
                        'Verifikasi & Validasi Petugas',
                        'Penyusunan Draft Rekomendasi',
                        'Verifikasi & Validasi Kabid PDPD',
                        'Verifikasi & Validasi Sekretaris Dinas',
                        'Verifikasi & Validasi Kepala Dinas',
                        'Verifikasi & Validasi Kepala Bagian Hukum',
                        'Verifikasi & Validasi Asisten Pemerintahan & Kesra',
                        'Verifikasi & Validasi Sekda',
                        'Tanda Tangan Bupati',
                        'Penomoran TU Umum Setda & Selesai'
                    ];
                    $currentIndex = array_search($pjkades->posisi_surat ?? 'Berkas Diterima', $posisiOptions);
                    if ($currentIndex === false) $currentIndex = 0; // Default to first if unknown
                    $nextPosisi = isset($posisiOptions[$currentIndex + 1]) ? $posisiOptions[$currentIndex + 1] : null;
                @endphp

                <div class="bg-surface rounded-card shadow-sm border border-border overflow-hidden">
                    <div class="px-5 py-4 bg-gray-50 border-b border-border">
                        <h3 class="text-base font-display font-semibold text-ink">Disposisi & Tindak Lanjut</h3>
                    </div>
                    <div class="p-5 flex flex-col gap-3">
                        @if($nextPosisi)
                        <form action="{{ route('admin.pjkades.disposisi', $pjkades->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="posisi_surat" value="{{ $nextPosisi }}">
                            <button type="submit"
                                class="w-full py-2.5 px-3 bg-primary rounded-btn text-white text-sm font-medium hover:bg-primary-light transition-colors flex items-center justify-center shadow-sm">
                                Lanjutkan ke : {{ $nextPosisi }}
                            </button>
                        </form>
                        @endif

                        <form action="{{ route('admin.pjkades.disposisi', $pjkades->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="posisi_surat" value="Berkas Diterima">
                            <input type="hidden" name="status_baru" value="direvisi">
                            <button type="submit"
                                class="w-full py-2.5 px-3 bg-white border border-red-300 text-red-600 rounded-btn text-sm font-medium hover:bg-red-50 transition-colors flex items-center justify-center shadow-sm" title="Kembalikan ke awal (Butuh Revisi)">
                                Revisi
                            </button>
                        </form>
                    </div>
                </div>

                <div class="bg-surface rounded-card shadow-sm border border-border p-6">
                    <h3 class="text-base font-display font-semibold text-ink mb-4 pb-2 border-b border-border">Penerbitan SK & Penetapan Masa Jabatan</h3>

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
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const countEl = document.getElementById('approved-count-val');
                
                document.querySelectorAll('.verify-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const form = this.closest('form');
                        const url = form.getAttribute('data-url');
                        const token = form.querySelector('input[name="_token"]').value;
                        const status = this.checked ? 'valid' : 'tidak_sesuai';
                        
                        // Optimistic UI update
                        const row = form.closest('.p-4');
                        if (this.checked) {
                            row.classList.remove('border-red-500', 'bg-red-50/40', 'border-amber-400', 'bg-amber-50/30');
                            row.classList.add('border-green-500', 'bg-green-50/40');
                            if (countEl) countEl.innerText = parseInt(countEl.innerText) + 1;
                        } else {
                            row.classList.remove('border-green-500', 'bg-green-50/40', 'border-red-500', 'bg-red-50/40');
                            row.classList.add('border-red-500', 'bg-red-50/40'); // Or amber if that was the default
                            if (countEl) countEl.innerText = parseInt(countEl.innerText) - 1;
                        }

                        fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ status_verifikasi: status })
                        }).catch(err => {
                            console.error('Network error during verification update', err);
                            // Revert UI on network error
                            this.checked = !this.checked;
                            if (this.checked) {
                                row.classList.remove('border-red-500', 'bg-red-50/40');
                                row.classList.add('border-green-500', 'bg-green-50/40');
                                if (countEl) countEl.innerText = parseInt(countEl.innerText) + 1;
                            } else {
                                row.classList.remove('border-green-500', 'bg-green-50/40');
                                row.classList.add('border-red-500', 'bg-red-50/40');
                                if (countEl) countEl.innerText = parseInt(countEl.innerText) - 1;
                            }
                        });
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>