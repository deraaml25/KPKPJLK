<x-app-layout>
    @section('title', 'Ajukan Surat Izin Pencalonan')

    <div class="max-w-3xl mx-auto bg-white rounded-card shadow-sm border border-border p-8 mb-8">
        <h2 class="text-xl font-display font-bold text-ink mb-2">Ajukan Surat Izin Pencalonan</h2>
        <p class="text-muted text-sm mb-6">Ajukan berkas seleksi administrasi izin pencalonan bagi perangkat desa atau
            PNS aktif.</p>

        <form action="{{ route('desa.izincalon.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="nama_calon" class="block text-sm font-medium text-ink mb-1">Nama Bakal Calon</label>
                    <input type="text" name="nama_calon" id="nama_calon" required
                        class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                        placeholder="Nama Lengkap Calon">
                </div>
                <div>
                    <label for="jabatan_sekarang" class="block text-sm font-medium text-ink mb-1">Jabatan
                        Sekarang</label>
                    <input type="text" name="jabatan_sekarang" id="jabatan_sekarang" required
                        class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                        placeholder="Contoh: Kepala Dusun I / PNS Staf">
                </div>
            </div>

            <div class="mb-4">
                <label for="jenis_calon" class="block text-sm font-medium text-ink mb-1">Kategori Aparatur Calon</label>
                <select name="jenis_calon" id="jenis_calon" required
                    class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm text-sm">
                    <option value="kades">Kepala Desa Aktif</option>
                    <option value="perangkat">Perangkat Desa Aktif</option>
                    <option value="pns">PNS / ASN Daerah</option>
                </select>
            </div>

            <div class="space-y-4 mb-6">
                <div>
                    <label for="bebas_temuan_inspektorat" class="block text-sm font-medium text-ink mb-1">Surat
                        Keterangan Bebas Temuan Inspektorat (.pdf)</label>
                    <input type="file" name="bebas_temuan_inspektorat" id="bebas_temuan_inspektorat" required
                        class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1">
                </div>
                <div>
                    <label for="berkas_syarat" class="block text-sm font-medium text-ink mb-1">Berkas / Dokumen
                        Persyaratan Administrasi Lainnya (.pdf)</label>
                    <input type="file" name="berkas_syarat" id="berkas_syarat" required
                        class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-border pt-6">
                <a href="{{ route('desa.izincalon.index') }}"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-ink font-medium rounded-btn transition-colors text-sm">Batal</a>
                <button type="submit"
                    class="px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">Kirim
                    Ajuan Izin</button>
            </div>
        </form>
    </div>
</x-app-layout>