<x-app-layout>
    @section('title', 'Edit Informasi Pembinaan')

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
                <h2 class="text-xl font-display font-bold text-ink">Edit Informasi Pembinaan</h2>
                <p class="text-muted text-sm mt-1">Perbarui konten informasi: <strong>{{ $bimtekInformasi->judul }}</strong></p>
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

            <form action="{{ route('admin.bimtek-informasi.update', $bimtekInformasi) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf @method('PUT')

                <div>
                    <label for="judul" class="block text-sm font-medium text-ink mb-1">Judul <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" id="judul" required value="{{ old('judul', $bimtekInformasi->judul) }}"
                        class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm">
                </div>

                <div>
                    <label for="kategori" class="block text-sm font-medium text-ink mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori" id="kategori" required
                        class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm">
                        <option value="informasi" {{ old('kategori', $bimtekInformasi->kategori) === 'informasi' ? 'selected' : '' }}>📋 Informasi Umum</option>
                        <option value="dokumentasi" {{ old('kategori', $bimtekInformasi->kategori) === 'dokumentasi' ? 'selected' : '' }}>📷 Dokumentasi Kegiatan</option>
                        <option value="pengumuman" {{ old('kategori', $bimtekInformasi->kategori) === 'pengumuman' ? 'selected' : '' }}>📢 Pengumuman</option>
                    </select>
                </div>

                <div>
                    <label for="konten" class="block text-sm font-medium text-ink mb-1">Konten / Isi <span class="text-red-500">*</span></label>
                    <textarea name="konten" id="konten" rows="8" required
                        class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm">{{ old('konten', $bimtekInformasi->konten) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="foto" class="block text-sm font-medium text-ink mb-1">Foto Baru (Opsional)</label>
                        @if($bimtekInformasi->foto)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $bimtekInformasi->foto) }}" class="h-20 rounded object-cover" alt="Foto saat ini">
                                <p class="text-xs text-muted mt-1">Foto saat ini. Upload baru untuk mengganti.</p>
                            </div>
                        @endif
                        <input type="file" name="foto" id="foto" accept="image/*"
                            class="w-full rounded-md border-border text-ink bg-white text-sm p-1">
                    </div>

                    <div>
                        <label for="file_lampiran" class="block text-sm font-medium text-ink mb-1">File Lampiran Baru (Opsional)</label>
                        @if($bimtekInformasi->file_lampiran)
                            <a href="{{ asset('storage/' . $bimtekInformasi->file_lampiran) }}" target="_blank"
                                class="text-primary text-xs hover:underline block mb-2">📎 Lihat lampiran saat ini</a>
                        @endif
                        <input type="file" name="file_lampiran" id="file_lampiran" accept=".pdf,.doc,.docx"
                            class="w-full rounded-md border-border text-ink bg-white text-sm p-1">
                    </div>
                </div>

                <div>
                    <label for="published_at" class="block text-sm font-medium text-ink mb-1">Tanggal Publikasi</label>
                    <input type="datetime-local" name="published_at" id="published_at"
                        value="{{ old('published_at', $bimtekInformasi->published_at?->format('Y-m-d\TH:i')) }}"
                        class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm text-sm">
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-border pt-5">
                    <a href="{{ route('admin.bimtek-informasi.index') }}"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-ink font-medium rounded-btn transition-colors text-sm">Batal</a>
                    <button type="submit"
                        class="px-5 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
