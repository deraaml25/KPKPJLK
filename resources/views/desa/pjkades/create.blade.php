<x-app-layout>
    @section('title', 'Buat Usulan Pemberhentian & SK Kades')

    <div class="max-w-4xl mx-auto bg-white rounded-card shadow-sm border border-border p-8 mb-8" x-data="{
        kategori: 'pj_kades',
        metode: 'online',
        alasanPjId: '{{ $alasanPj->first()->id ?? '' }}',
        alasanSementaraId: '{{ $alasanSementara->first()->id ?? '' }}',
        alasanCutiId: '{{ $alasanCuti->first()->id ?? '' }}'
    }">
        <div class="mb-6 border-b border-border pb-4">
            <h2 class="text-xl font-display font-bold text-ink">Buat Usulan Pemberhentian & SK Kades</h2>
        </div>

        @if ($errors->any())
            <div class="p-4 bg-red-50 text-red-800 rounded border border-red-200 text-sm mb-6">
                <strong class="font-bold">Terjadi kesalahan input:</strong>
                <ul class="list-disc ml-5 mt-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('desa.pjkades.store') }}" method="POST">
            @csrf

            {{-- Metode Penyerahan --}}
            <div class="mb-8">
                <label class="block text-sm font-bold text-ink mb-2">Metode Penyerahan Berkas <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all"
                        :class="metode === 'online' ? 'border-primary bg-primary/5 shadow-sm' : 'border-border hover:border-gray-300'">
                        <input type="radio" name="metode" value="online" x-model="metode" class="text-primary focus:ring-primary w-4 h-4 mr-3">
                        <div>
                            <span class="block text-sm font-bold text-ink">Online</span>
                            <span class="block text-xs text-muted mt-0.5">Unggah seluruh syarat dokumen di web.</span>
                        </div>
                    </label>
                    <label class="relative flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all"
                        :class="metode === 'offline' ? 'border-primary bg-primary/5 shadow-sm' : 'border-border hover:border-gray-300'">
                        <input type="radio" name="metode" value="offline" x-model="metode" class="text-primary focus:ring-primary w-4 h-4 mr-3">
                        <div>
                            <span class="block text-sm font-bold text-ink">Offline (Tatap Muka)</span>
                            <span class="block text-xs text-muted mt-0.5">Berkas fisik diantar langsung ke Dinpermasdes.</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- STEP 1: Pilih Jenis Pemberhentian Kepala Desa --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-ink mb-2">1. Pilih Jenis Pemberhentian Kepala Desa <span class="text-red-500">*</span></label>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Opsi A: Pemberhentian Definitif (Pj Kades) --}}
                    <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition-all"
                        :class="kategori === 'pj_kades' ? 'border-primary bg-primary/5 shadow-sm' : 'border-border hover:border-gray-300'">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-bold text-ink flex items-center gap-2">
                                Pemberhentian Kades
                            </span>
                            <input type="radio" name="kategori" value="pj_kades" x-model="kategori" class="text-primary focus:ring-primary">
                        </div>

                        <div class="mt-auto pt-2 border-t border-gray-200/60">
                            <span class="text-[11px] font-bold text-primary bg-primary/10 px-2 py-0.5 rounded leading-tight inline-block">
                                Pengganti: Penjabat (Pj) Kades — ASN
                            </span>
                        </div>
                    </label>

                    {{-- Opsi B: Pemberhentian Sementara --}}
                    <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition-all"
                        :class="kategori === 'plt_sementara' ? 'border-amber-500 bg-amber-50/50 shadow-sm' : 'border-border hover:border-gray-300'">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-bold text-ink flex items-center gap-2">
                                Pemberhentian Sementara
                            </span>
                            <input type="radio" name="kategori" value="plt_sementara" x-model="kategori" class="text-amber-600 focus:ring-amber-500">
                        </div>

                        <div class="mt-auto pt-2 border-t border-gray-200/60">
                            <span class="text-[11px] font-bold text-amber-800 bg-amber-100 px-2 py-0.5 rounded leading-tight inline-block">
                                Pengganti: Pelaksana Tugas (Plt) Kades — Sekdes
                            </span>
                        </div>
                    </label>
                    
                    {{-- Opsi C: Cuti --}}
                    <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition-all"
                        :class="kategori === 'plt_cuti' ? 'border-green-500 bg-green-50/50 shadow-sm' : 'border-border hover:border-gray-300'">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-bold text-ink flex items-center gap-2">
                                Cuti
                            </span>
                            <input type="radio" name="kategori" value="plt_cuti" x-model="kategori" class="text-green-600 focus:ring-green-500">
                        </div>

                        <div class="mt-auto pt-2 border-t border-gray-200/60">
                            <span class="text-[11px] font-bold text-green-800 bg-green-100 px-2 py-0.5 rounded leading-tight inline-block">
                                Pengganti: Pelaksana Tugas (Plt) Kades — Sekdes
                            </span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- STEP 2: Pilih Alasan Pemberhentian / Cuti --}}
            <div class="mb-6 p-5 bg-gray-50 rounded-xl border border-border">
                <label class="block text-sm font-bold text-ink mb-2">2. Pilih Alasan Pemberhentian / Cuti <span class="text-red-500">*</span></label>

                {{-- Alasan untuk Pemberhentian Definitif (Pj Kades) --}}
                <template x-if="kategori === 'pj_kades'">
                    <div>
                        <select name="alasan_pemberhentian_id" class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm" required>
                            @foreach ($alasanPj as $alasan)
                                <option value="{{ $alasan->id }}">{{ $alasan->nama }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-muted mt-2">Daftar checklist berkas otomatis mencakup: <strong>Dokumen Pemberhentian Kades (sesuai alasan) + 14 Dokumen Persyaratan Pj Kades ASN</strong>.</p>
                    </div>
                </template>

                {{-- Alasan untuk Pemberhentian Sementara --}}
                <template x-if="kategori === 'plt_sementara'">
                    <div>
                        <select name="alasan_pemberhentian_id" x-model="alasanSementaraId" class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-amber-500 focus:ring-amber-500 shadow-sm" required>
                            @foreach ($alasanSementara as $alasan)
                                <option value="{{ $alasan->id }}">{{ $alasan->nama }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-muted mt-2">Daftar checklist berkas otomatis mencakup: <strong>Dokumen Pemberhentian Sementara + 6 Dokumen Pendukung Plt Sekdes</strong>.</p>
                    </div>
                </template>
                
                {{-- Alasan untuk Cuti --}}
                <template x-if="kategori === 'plt_cuti'">
                    <div>
                        @php
                            $idAlasanPenting = $alasanCuti->where('nama', 'Cuti Alasan Penting')->first()->id ?? null;
                        @endphp
                        <select name="alasan_pemberhentian_id" x-model="alasanCutiId" class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-green-500 focus:ring-green-500 shadow-sm" required>
                            @foreach ($alasanCuti as $alasan)
                                <option value="{{ $alasan->id }}">{{ $alasan->nama }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-muted mt-2">Daftar checklist berkas otomatis mencakup: <strong>Dokumen Cuti (seperti surat keterangan) + 6 Dokumen Pendukung Plt Sekdes</strong>.</p>
                        
                        <template x-if="alasanCutiId == '{{ $idAlasanPenting }}'">
                            <div class="mt-4">
                                <label for="keterangan_cuti" class="block text-sm font-bold text-ink mb-1">Keterangan Alasan Penting <span class="text-red-500">*</span></label>
                                <input type="text" name="keterangan_cuti" id="keterangan_cuti" required
                                    class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-green-500 focus:ring-green-500 shadow-sm"
                                    placeholder="Contoh: Ada keluarga meninggal, berangkat Umroh/Haji, dll.">
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            {{-- STEP 3: Data Pengganti (Pj Kades PNS vs Plt Kades Sekdes) --}}
            <div class="mb-6">
                <h3 class="text-sm font-bold text-ink uppercase tracking-wider mb-3 border-t border-border pt-4">
                    3. Data Pengganti Kades (<span x-text="kategori === 'pj_kades' ? 'Penjabat Pj Kades - ASN' : 'Pelaksana Tugas Plt Kades - Sekdes'"></span>)
                </h3>

                {{-- Data Pj Kades (PNS) --}}
                <template x-if="kategori === 'pj_kades'">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_pns" class="block text-sm font-medium text-ink mb-1">Nama Lengkap ASN Calon Pj Kades <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_pns" id="nama_pns" required value="{{ old('nama_pns') }}"
                                    class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                                    placeholder="Nama beserta Gelar">
                            </div>
                            <div>
                                <label for="nip" class="block text-sm font-medium text-ink mb-1">NIP ASN <span class="text-red-500">*</span></label>
                                <input type="text" name="nip" id="nip" required value="{{ old('nip') }}"
                                    class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                                    placeholder="1980xxxxxxxxxxxxxx">
                            </div>
                        </div>

                        <div>
                            <label for="pangkat" class="block text-sm font-medium text-ink mb-1">Pangkat / Golongan Ruang <span class="text-red-500">*</span></label>
                            <input type="text" name="pangkat" id="pangkat" required value="{{ old('pangkat') }}"
                                class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                                placeholder="Contoh: Penata / III c">
                        </div>
                    </div>
                </template>

                {{-- Data Plt Kades (Sekdes) --}}
                <template x-if="['plt_sementara', 'plt_cuti'].includes(kategori)">
                    <div class="space-y-4">
                        <div>
                            <label for="nama_plt" class="block text-sm font-bold text-ink mb-1">Nama Sekretaris Desa (Calon Plt Kades) <span class="text-red-500">*</span></label>
                            @if($sekdes)
                                <input type="text" name="nama_plt" id="nama_plt" value="{{ $sekdes->nama }}" readonly
                                    class="w-full text-sm rounded-md border-border bg-gray-100 text-gray-500 cursor-not-allowed focus:border-amber-500 focus:ring-amber-500 shadow-sm">
                                <p class="text-xs text-muted mt-2">Sistem otomatis mengambil nama Sekretaris Desa aktif.</p>
                            @else
                                <div class="p-3 bg-red-50 text-red-600 rounded-md border border-red-200 text-sm">
                                    Data Sekretaris Desa belum terdaftar atau tidak aktif di menu Data Perangkat Desa.
                                </div>
                            @endif
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-border pt-6">
                <a href="{{ route('desa.pjkades.index') }}"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-ink font-medium rounded-btn transition-colors text-sm">Batal</a>
                <button type="submit"
                    class="px-5 py-2.5 bg-primary text-white font-semibold rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">
                    Buat Draft Usulan & Generate Checklist
                </button>
            </div>
        </form>
    </div>
</x-app-layout>