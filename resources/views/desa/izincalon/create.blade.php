<x-app-layout>
    @section('title', 'Ajukan Izin Pencalonan Kades')

    <div class="max-w-3xl mx-auto bg-white rounded-card shadow-sm border border-border p-8 mb-8">
        <h2 class="text-xl font-display font-bold text-ink mb-1">Formulir Permohonan Izin Pencalonan Kepala Desa</h2>
        <p class="text-muted text-sm mb-6">Berkas ini diproses oleh Dinas Pemberdayaan Masyarakat dan Desa
            (Dinpermasdes). Izin tertulis Bupati wajib dimiliki sebelum mendaftar ke Panitia Pilkades.</p>

        @if ($errors->any())
            <div class="p-4 bg-red-50 text-red-800 rounded border border-red-200 text-sm mb-6">
                <strong class="font-bold">Terjadi kesalahan:</strong>
                <ul class="list-disc ml-5 mt-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('desa.izincalon.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Identitas Calon --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="nama_calon" class="block text-sm font-medium text-ink mb-1">Nama Lengkap Calon</label>
                    <input type="text" name="nama_calon" id="nama_calon" required value="{{ old('nama_calon') }}"
                        class="w-full text-sm rounded-md border-border text-ink shadow-sm focus:border-primary focus:ring-primary"
                        placeholder="Nama lengkap beserta gelar">
                </div>
                <div>
                    <label for="jabatan_sekarang" class="block text-sm font-medium text-ink mb-1">Jabatan / Instansi
                        Saat Ini</label>
                    <input type="text" name="jabatan_sekarang" id="jabatan_sekarang" required
                        value="{{ old('jabatan_sekarang') }}"
                        class="w-full text-sm rounded-md border-border text-ink shadow-sm focus:border-primary focus:ring-primary"
                        placeholder="Contoh: Sekretaris Desa / Guru SD / Petani">
                </div>
            </div>

            <div class="mb-6">
                <label for="jenis_calon" class="block text-sm font-medium text-ink mb-1">Status / Jenis Calon</label>
                <select name="jenis_calon" id="jenis_calon" required onchange="toggleConditionalFields(this.value)"
                    class="w-full text-sm rounded-md border-border text-ink shadow-sm focus:border-primary focus:ring-primary">
                    <option value="">— Pilih Status Calon —</option>
                    <option value="kades" {{ old('jenis_calon') === 'kades' ? 'selected' : '' }}>Kepala Desa Petahana
                        (Incumbent)</option>
                    <option value="perangkat" {{ old('jenis_calon') === 'perangkat' ? 'selected' : '' }}>Perangkat Desa
                    </option>
                    <option value="pns" {{ old('jenis_calon') === 'pns' ? 'selected' : '' }}>Aparatur Sipil Negara (PNS)
                    </option>
                </select>
            </div>

            {{-- Cuti Petahana (hanya untuk Kades) --}}
            <div id="cuti-fields" class="hidden mb-6 p-4 bg-blue-50 rounded border border-blue-200">
                <h3 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-3">Periode Cuti Kampanye (Wajib
                    untuk Petahana)</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="tgl_cuti_mulai" class="block text-xs font-medium text-ink mb-1">Tanggal Mulai
                            Cuti</label>
                        <input type="date" name="tgl_cuti_mulai" id="tgl_cuti_mulai"
                            class="w-full text-xs rounded-md border-border text-ink shadow-sm focus:border-primary focus:ring-primary"
                            value="{{ old('tgl_cuti_mulai') }}">
                    </div>
                    <div>
                        <label for="tgl_cuti_selesai" class="block text-xs font-medium text-ink mb-1">Tanggal Selesai
                            Cuti</label>
                        <input type="date" name="tgl_cuti_selesai" id="tgl_cuti_selesai"
                            class="w-full text-xs rounded-md border-border text-ink shadow-sm focus:border-primary focus:ring-primary"
                            value="{{ old('tgl_cuti_selesai') }}">
                    </div>
                </div>
            </div>

            {{-- Dokumen Wajib --}}
            <h3 class="text-sm font-bold text-ink uppercase tracking-wider mb-3 mt-2 border-t border-border pt-4">Unggah
                Dokumen Persyaratan</h3>
            <div class="space-y-4 mb-6">
                <div class="p-4 bg-gray-50 rounded border border-border">
                    <label for="surat_permohonan" class="block text-sm font-medium text-ink mb-1">1. Surat Permohonan
                        Izin Cuti / Pencalonan <span class="text-red-500">*</span></label>
                    <p class="text-xs text-muted mb-2">Surat resmi permohonan izin cuti atau pencalonan yang
                        ditandatangani pejabat berwenang.</p>
                    <input type="file" name="surat_permohonan" id="surat_permohonan" accept=".pdf" required
                        class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1">
                </div>
                <div class="p-4 bg-gray-50 rounded border border-border">
                    <label for="berkas_syarat" class="block text-sm font-medium text-ink mb-1">2. Berkas Syarat
                        Administratif <span class="text-red-500">*</span></label>
                    <p class="text-xs text-muted mb-2">Lampiran dokumen persyaratan sesuai Peraturan Daerah tentang
                        Pilkades (KTP, KK, ijazah, SKCK, dll) dijadikan satu PDF.</p>
                    <input type="file" name="berkas_syarat" id="berkas_syarat" accept=".pdf" required
                        class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1">
                </div>

                {{-- Surat Pengunduran Diri (hanya untuk Perangkat Desa) --}}
                <div id="pengunduran-field" class="hidden p-4 bg-yellow-50 rounded border border-yellow-200">
                    <label for="surat_pengunduran_diri" class="block text-sm font-medium text-ink mb-1">3. Surat
                        Pengunduran Diri sebagai Perangkat Desa <span class="text-red-500">*</span></label>
                    <p class="text-xs text-muted mb-2">Sesuai peraturan perundang-undangan, Perangkat Desa yang
                        mencalonkan diri wajib mengundurkan diri dari jabatannya.</p>
                    <input type="file" name="surat_pengunduran_diri" id="surat_pengunduran_diri" accept=".pdf"
                        class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-border pt-6">
                <a href="{{ route('desa.izincalon.index') }}"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-ink font-medium rounded-btn transition-colors text-sm">Batal</a>
                <button type="submit"
                    class="px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">Kirim
                    Permohonan Izin</button>
            </div>
        </form>
    </div>

    <script>
        function toggleConditionalFields(val) {
            // Cuti fields for petahana
            const cutiFields = document.getElementById('cuti-fields');
            const tglMulai = document.getElementById('tgl_cuti_mulai');
            const tglSelesai = document.getElementById('tgl_cuti_selesai');
            if (val === 'kades') {
                cutiFields.classList.remove('hidden');
                tglMulai.required = true;
                tglSelesai.required = true;
            } else {
                cutiFields.classList.add('hidden');
                tglMulai.required = false;
                tglSelesai.required = false;
            }

            // Pengunduran diri for perangkat
            const pdField = document.getElementById('pengunduran-field');
            const pdInput = document.getElementById('surat_pengunduran_diri');
            if (val === 'perangkat') {
                pdField.classList.remove('hidden');
                pdInput.required = true;
            } else {
                pdField.classList.add('hidden');
                pdInput.required = false;
            }
        }
        // On page load (e.g. validation error re-render)
        const sel = document.getElementById('jenis_calon');
        if (sel.value) toggleConditionalFields(sel.value);
    </script>
</x-app-layout>