<x-app-layout>
    @section('title', 'Usulkan Pj Kades Baru')

    <div class="max-w-3xl mx-auto bg-white rounded-card shadow-sm border border-border p-8 mb-8">
        <h2 class="text-xl font-display font-bold text-ink mb-2">Usulkan Pj Kepala Desa Baru</h2>
        <p class="text-muted text-sm mb-6">Ajukan penunjukan Penjabat Kepala Desa (Pj Kades) dari unsur Pegawai Negeri
            Sipil (PNS).</p>

        <form action="{{ route('desa.pjkades.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="nama_pns" class="block text-sm font-medium text-ink mb-1">Nama PNS yang
                        Diusulkan</label>
                    <input type="text" name="nama_pns" id="nama_pns" required
                        class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                        placeholder="Nama Lengkap beserta Gelar">
                </div>
                <div>
                    <label for="nip" class="block text-sm font-medium text-ink mb-1">NIP PNS</label>
                    <input type="text" name="nip" id="nip" required
                        class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                        placeholder="1980xxxxxxxxxxxxxx">
                </div>
            </div>

            <div class="mb-4">
                <label for="pangkat" class="block text-sm font-medium text-ink mb-1">Pangkat / Golongan Ruang</label>
                <input type="text" name="pangkat" id="pangkat" required
                    class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                    placeholder="Contoh: Penata / III c">
            </div>

            <div class="space-y-4 mb-6">
                <div>
                    <label for="riwayat_hidup" class="block text-sm font-medium text-ink mb-1">Draf Riwayat Hidup / CV
                        Calon (.pdf)</label>
                    <input type="file" name="riwayat_hidup" id="riwayat_hidup" required
                        class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1">
                </div>
                <div>
                    <label for="sk_pangkat" class="block text-sm font-medium text-ink mb-1">Draf Surat Keputusan Pangkat
                        Terakhir (.pdf)</label>
                    <input type="file" name="sk_pangkat" id="sk_pangkat" required
                        class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-border pt-6">
                <a href="{{ route('desa.pjkades.index') }}"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-ink font-medium rounded-btn transition-colors text-sm">Batal</a>
                <button type="submit"
                    class="px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">Kirim
                    Usulan Pj Kades</button>
            </div>
        </form>
    </div>
</x-app-layout>