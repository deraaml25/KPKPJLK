<x-app-layout>
    @section('title', 'Tambah Informasi Pembinaan')

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-card shadow-sm border border-border p-8">
            <div class="mb-6">
                <a href="{{ route('admin.bimtek-informasi.index') }}"
                    class="text-sm text-primary hover:underline flex items-center gap-1 mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </a>
                <h2 class="text-xl font-display font-bold text-ink">Tambah Informasi Pembinaan</h2>
                <p class="text-muted text-sm mt-1">Publikasikan berita, dokumentasi, atau pengumuman kegiatan pembinaan.</p>
            </div>

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.bimtek-informasi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="judul" class="block text-sm font-medium text-ink mb-1">Judul <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" id="judul" required value="{{ old('judul') }}"
                        class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                        placeholder="Judul berita atau informasi pembinaan">
                </div>

                <div>
                    <label for="kategori" class="block text-sm font-medium text-ink mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori" id="kategori" required
                        class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm">
                        <option value="informasi" {{ old('kategori') === 'informasi' ? 'selected' : '' }}>📋 Informasi Umum</option>
                        <option value="dokumentasi" {{ old('kategori') === 'dokumentasi' ? 'selected' : '' }}>📷 Dokumentasi Kegiatan</option>
                        <option value="pengumuman" {{ old('kategori') === 'pengumuman' ? 'selected' : '' }}>📢 Pengumuman</option>
                    </select>
                </div>

                <div>
                    <label for="konten" class="block text-sm font-medium text-ink mb-1">Konten / Isi <span class="text-red-500">*</span></label>
                    <textarea name="konten" id="konten" rows="8" required
                        class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                        placeholder="Tulis isi berita, deskripsi kegiatan, atau informasi pembinaan secara lengkap...">{{ old('konten') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="foto" class="block text-sm font-medium text-ink mb-1">Foto / Gambar (Opsional)</label>
                        <input type="file" name="foto" id="foto" accept="image/*"
                            class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm text-sm p-1">
                        <p class="text-xs text-muted mt-1">Format: JPG, PNG, WebP. Maks 5MB.</p>
                    </div>

                    <div>
                        <label for="file_lampiran" class="block text-sm font-medium text-ink mb-1">File Lampiran (Opsional)</label>
                        <input type="file" name="file_lampiran" id="file_lampiran" accept=".pdf,.doc,.docx"
                            class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm text-sm p-1">
                        <p class="text-xs text-muted mt-1">Format: PDF, DOC. Maks 10MB.</p>
                    </div>
                </div>

                <div>
                    <label for="published_at" class="block text-sm font-medium text-ink mb-1">Tanggal Publikasi</label>
                    <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}"
                        class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm text-sm">
                    <p class="text-xs text-muted mt-1">Kosongkan jika ingin disimpan sebagai draft.</p>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-border pt-5">
                    <a href="{{ route('admin.bimtek-informasi.index') }}"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-ink font-medium rounded-btn transition-colors text-sm">Batal</a>
                    <button type="submit"
                        class="px-5 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">
                        Publikasikan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
