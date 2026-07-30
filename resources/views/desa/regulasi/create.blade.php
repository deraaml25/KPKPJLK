<x-app-layout>
    @section('title', 'Ajukan Regulasi Baru')

    <div class="max-w-3xl mx-auto bg-white rounded-card shadow-sm border border-border p-8 mb-8">
        <h2 class="text-xl font-display font-bold text-ink mb-2">Ajukan Regulasi Baru</h2>
        <p class="text-muted text-sm mb-6">Ajukan draf atau rancangan perdes, perkades, atau SK kades untuk dievaluasi
            oleh Dinpermasdes.</p>

        @if($errors->any())
            <div class="mb-5 p-4 rounded-card bg-red-50 border border-red-200 text-red-800 flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <ul class="text-sm list-disc list-inside">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('desa.regulasi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="judul" class="block text-sm font-medium text-ink mb-1">Judul Regulasi</label>
                <input type="text" name="judul" id="judul" required value="{{ old('judul') }}"
                    class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                    placeholder="Contoh: Peraturan Desa tentang Rencana Kerja Pemerintah Desa">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="tipe" class="block text-sm font-medium text-ink mb-1">Tipe Produk Hukum</label>
                    <select name="tipe" id="tipe" required
                        class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm">
                        <option value="perdes">Peraturan Desa (Perdes)</option>
                        <option value="perkades">Peraturan Kepala Desa (Perkades)</option>
                        <option value="sk_kades">Keputusan Kepala Desa (SK Kades)</option>
                    </select>
                </div>
                <div>
                    <label for="file" class="block text-sm font-medium text-ink mb-1">Draf Dokumen (Wajib .doc / .docx)</label>
                    <input type="file" name="file" id="file" required
                        accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                        class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm text-sm p-1">
                </div>
            </div>

            <div class="mb-6">
                <label for="deskripsi" class="block text-sm font-medium text-ink mb-1">Keterangan / Deskripsi Singkat</label>
                <textarea name="deskripsi" id="deskripsi" rows="4"
                    class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                    placeholder="Jelaskan secara singkat materi pokok regulasi ini...">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-border pt-6">
                <a href="{{ route('desa.regulasi.index') }}"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-ink font-medium rounded-btn transition-colors text-sm">Batal</a>
                <button type="submit"
                    class="px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">Kirim
                    Usulan</button>
            </div>
        </form>
    </div>
</x-app-layout>