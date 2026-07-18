<x-app-layout>
    @section('title', 'Usulkan Pj Kades Baru')

    <div class="max-w-3xl mx-auto bg-white rounded-card shadow-sm border border-border p-8 mb-8">
        <h2 class="text-xl font-display font-bold text-ink mb-2">Usulkan Pj Kepala Desa Baru</h2>
        <p class="text-muted text-sm mb-6">Ajukan penunjukan Penjabat Kepala Desa (Pj Kades) dari unsur Pegawai Negeri
            Sipil (PNS). Seluruh berkas berikut wajib diisi.</p>

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

        <form action="{{ route('desa.pjkades.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Data PNS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="nama_pns" class="block text-sm font-medium text-ink mb-1">Nama Lengkap PNS</label>
                    <input type="text" name="nama_pns" id="nama_pns" required value="{{ old('nama_pns') }}"
                        class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                        placeholder="Nama Lengkap beserta Gelar">
                </div>
                <div>
                    <label for="nip" class="block text-sm font-medium text-ink mb-1">NIP PNS</label>
                    <input type="text" name="nip" id="nip" required value="{{ old('nip') }}"
                        class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                        placeholder="1980xxxxxxxxxxxxxx">
                </div>
            </div>

            <div class="mb-6">
                <label for="pangkat" class="block text-sm font-medium text-ink mb-1">Pangkat / Golongan Ruang</label>
                <input type="text" name="pangkat" id="pangkat" required value="{{ old('pangkat') }}"
                    class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                    placeholder="Contoh: Penata / III c">
            </div>

            {{-- Dokumen Upload --}}
            <h3 class="text-sm font-bold text-ink uppercase tracking-wider mb-3 mt-2 border-t border-border pt-4">Unggah
                Dokumen Persyaratan</h3>
            <div class="space-y-4 mb-6">
                <div class="p-4 bg-gray-50 rounded border border-border">
                    <label for="surat_camat" class="block text-sm font-medium text-ink mb-1">1. Surat Usulan dari Camat
                        <span class="text-red-500">*</span></label>
                    <p class="text-xs text-muted mb-2">Surat pengantar resmi dari Camat yang mengusulkan PNS sebagai Pj
                        Kades.</p>
                    <input type="file" name="surat_camat" id="surat_camat" accept=".pdf" required
                        class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1">
                </div>
                <div class="p-4 bg-gray-50 rounded border border-border">
                    <label for="sk_pangkat" class="block text-sm font-medium text-ink mb-1">2. Fotokopi SK Pangkat
                        Terakhir <span class="text-red-500">*</span></label>
                    <p class="text-xs text-muted mb-2">Salinan digital Surat Keputusan Pangkat terakhir PNS yang
                        diusulkan.</p>
                    <input type="file" name="sk_pangkat" id="sk_pangkat" accept=".pdf" required
                        class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1">
                </div>
                <div class="p-4 bg-gray-50 rounded border border-border">
                    <label for="riwayat_hidup" class="block text-sm font-medium text-ink mb-1">3. Daftar Riwayat Hidup
                        PNS <span class="text-red-500">*</span></label>
                    <p class="text-xs text-muted mb-2">CV / Curriculum Vitae lengkap calon Pj Kepala Desa.</p>
                    <input type="file" name="riwayat_hidup" id="riwayat_hidup" accept=".pdf" required
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