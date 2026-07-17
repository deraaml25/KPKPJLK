<x-app-layout>
    @section('title', 'Ajukan Penataan Wilayah')

    <div class="max-w-3xl mx-auto bg-white rounded-card shadow-sm border border-border p-8 mb-8">
        <h2 class="text-xl font-display font-bold text-ink mb-2">Ajukan Usulan Penataan Wilayah</h2>
        <p class="text-muted text-sm mb-6">Ajukan berkas rancangan pemekaran, penggabungan, atau perubahan batas
            administratif desa Anda.</p>

        <form action="{{ route('desa.penataan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="tipe" class="block text-sm font-medium text-ink mb-1">Tipe Penataan</label>
                    <select name="tipe" id="tipe" required
                        class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm">
                        <option value="pemekaran">Pemekaran Wilayah / Dusun baru</option>
                        <option value="penggabungan">Penggabungan Dusun / Desa</option>
                        <option value="perubahan_batas">Perubahan Batas Administrasi/Peta</option>
                    </select>
                </div>
                <div>
                    <label for="nama_wilayah_baru" class="block text-sm font-medium text-ink mb-1">Nama Wilayah / Dusun
                        Baru</label>
                    <input type="text" name="nama_wilayah_baru" id="nama_wilayah_baru" required
                        class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                        placeholder="Contoh: Dusun Karanggandul / batas utara">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="jumlah_penduduk" class="block text-sm font-medium text-ink mb-1">Jumlah Penduduk
                        Pendukung (Jiwa)</label>
                    <input type="number" name="jumlah_penduduk" id="jumlah_penduduk" required min="0"
                        class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                        placeholder="Contoh: 6150">
                </div>
                <div>
                    <label for="jumlah_kk" class="block text-sm font-medium text-ink mb-1">Jumlah KK Pendukung</label>
                    <input type="number" name="jumlah_kk" id="jumlah_kk" required min="0"
                        class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                        placeholder="Contoh: 1250">
                </div>
            </div>

            <div class="space-y-4 mb-6">
                <div>
                    <label for="proposal" class="block text-sm font-medium text-ink mb-1">Dokumen Proposal Kajian &
                        Berkas Pendukung (.pdf)</label>
                    <input type="file" name="proposal" id="proposal" required
                        class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-border pt-6">
                <a href="{{ route('desa.penataan.index') }}"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-ink font-medium rounded-btn transition-colors text-sm">Batal</a>
                <button type="submit"
                    class="px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">Kirim
                    Usulan Penataan</button>
            </div>
        </form>
    </div>
</x-app-layout>