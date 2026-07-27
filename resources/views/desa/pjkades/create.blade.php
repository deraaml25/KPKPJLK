<x-app-layout>
    @section('title', 'Buat Usulan Pemberhentian & SK Kades')

    <div class="max-w-4xl mx-auto bg-white rounded-card shadow-sm border border-border p-8 mb-8" x-data="{
        kategori: 'pj_kades',
        metode: 'online',
        alasanPjId: '{{ $alasanPj->first()->id ?? '' }}',
        alasanPltId: '{{ $alasanPlt->first()->id ?? '' }}'
    }">
        <div class="mb-6 border-b border-border pb-4">
            <h2 class="text-xl font-display font-bold text-ink">Buat Usulan Pemberhentian & SK Kades</h2>
            <p class="text-muted text-sm mt-1">Pilih jenis pemberhentian Kepala Desa terlebih dahulu (Pemberhentian Definitif atau Pemberhentian Sementara / Cuti), lalu tentukan alasan dan data Pj/Plt pengganti.</p>
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
                            <span class="block text-sm font-bold text-ink">Online (Unggah ZIP)</span>
                            <span class="block text-xs text-muted mt-0.5">Unggah seluruh syarat dalam 1 file ZIP di web.</span>
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
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Opsi A: Pemberhentian Definitif (Pj Kades) --}}
                    <label class="relative flex flex-col p-5 rounded-xl border-2 cursor-pointer transition-all"
                        :class="kategori === 'pj_kades' ? 'border-primary bg-primary/5 shadow-sm' : 'border-border hover:border-gray-300'">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-bold text-ink flex items-center gap-2">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                Pemberhentian Kades (Definitif)
                            </span>
                            <input type="radio" name="kategori" value="pj_kades" x-model="kategori" class="text-primary focus:ring-primary">
                        </div>
                        <p class="text-xs text-muted mb-2">Pemberhentian Kades secara permanen (Meninggal Dunia, Permintaan Sendiri, atau Diberhentikan Tidak Hormat).</p>
                        <div class="mt-auto pt-2 border-t border-gray-200/60">
                            <span class="text-[11px] font-bold text-primary bg-primary/10 px-2 py-0.5 rounded">
                                Pengganti: Penjabat (Pj) Kades — Unsur PNS
                            </span>
                        </div>
                    </label>

                    {{-- Opsi B: Pemberhentian Sementara / Cuti (Plt Kades) --}}
                    <label class="relative flex flex-col p-5 rounded-xl border-2 cursor-pointer transition-all"
                        :class="kategori === 'plt_kades' ? 'border-amber-500 bg-amber-50/50 shadow-sm' : 'border-border hover:border-gray-300'">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-bold text-ink flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Pemberhentian Sementara / Cuti Kades
                            </span>
                            <input type="radio" name="kategori" value="plt_kades" x-model="kategori" class="text-amber-600 focus:ring-amber-500">
                        </div>
                        <p class="text-xs text-muted mb-2">Pemberhentian Kades bersifat sementara atau dalam masa cuti (Hukdis, Sakit, Umroh/Haji, Tahunan, Bersalin, Alasan Penting).</p>
                        <div class="mt-auto pt-2 border-t border-gray-200/60">
                            <span class="text-[11px] font-bold text-amber-800 bg-amber-100 px-2 py-0.5 rounded">
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
                        <p class="text-xs text-muted mt-2">Daftar checklist berkas otomatis mencakup: <strong>Dokumen Pemberhentian Kades (sesuai alasan) + 14 Dokumen Persyaratan Pj Kades PNS</strong>.</p>
                    </div>
                </template>

                {{-- Alasan untuk Pemberhentian Sementara / Cuti (Plt Kades) --}}
                <template x-if="kategori === 'plt_kades'">
                    <div>
                        <select name="alasan_pemberhentian_id" class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-amber-500 focus:ring-amber-500 shadow-sm" required>
                            @foreach ($alasanPlt as $alasan)
                                <option value="{{ $alasan->id }}">{{ $alasan->nama }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-muted mt-2">Daftar checklist berkas otomatis mencakup: <strong>Dokumen Khusus Alasan Cuti/Sementara + 6 Dokumen Pendukung Plt Sekdes</strong>.</p>
                    </div>
                </template>
            </div>

            {{-- STEP 3: Data Pengganti (Pj Kades PNS vs Plt Kades Sekdes) --}}
            <div class="mb-6">
                <h3 class="text-sm font-bold text-ink uppercase tracking-wider mb-3 border-t border-border pt-4">
                    3. Data Pengganti Kades (<span x-text="kategori === 'pj_kades' ? 'Penjabat Pj Kades - PNS' : 'Pelaksana Tugas Plt Kades - Sekdes'"></span>)
                </h3>

                {{-- Data Pj Kades (PNS) --}}
                <template x-if="kategori === 'pj_kades'">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_pns" class="block text-sm font-medium text-ink mb-1">Nama Lengkap PNS Calon Pj Kades <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_pns" id="nama_pns" required value="{{ old('nama_pns') }}"
                                    class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                                    placeholder="Nama beserta Gelar">
                            </div>
                            <div>
                                <label for="nip" class="block text-sm font-medium text-ink mb-1">NIP PNS <span class="text-red-500">*</span></label>
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
                <template x-if="kategori === 'plt_kades'">
                    <div class="space-y-4">
                        <div>
                            <label for="nama_plt" class="block text-sm font-medium text-ink mb-1">Nama Sekretaris Desa (Calon Plt Kades) <span class="text-red-500">*</span></label>
                            <select name="nama_plt" id="nama_plt" class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-amber-500 focus:ring-amber-500 shadow-sm" required>
                                <option value="">-- Pilih Sekretaris Desa --</option>
                                @foreach($perangkatDesas as $p)
                                    <option value="{{ $p->nama }}">{{ $p->nama }} ({{ $p->jabatan }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nip_plt" class="block text-sm font-medium text-ink mb-1">NIP / NIPD Sekdes (Opsional)</label>
                                <input type="text" name="nip_plt" id="nip_plt" value="{{ old('nip_plt') }}"
                                    class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-amber-500 focus:ring-amber-500 shadow-sm"
                                    placeholder="Nomor Induk Perangkat Desa">
                            </div>
                            <div>
                                <label for="pangkat_plt" class="block text-sm font-medium text-ink mb-1">Jabatan (Opsional)</label>
                                <input type="text" name="pangkat_plt" id="pangkat_plt" value="{{ old('pangkat_plt') }}"
                                    class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-amber-500 focus:ring-amber-500 shadow-sm"
                                    placeholder="Sekretaris Desa">
                            </div>
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