<x-app-layout>
    @section('title', 'Buat Agenda Bimtek Baru')

    <div class="max-w-3xl mx-auto bg-white rounded-card shadow-sm border border-border p-8 mb-8">
        <h2 class="text-xl font-display font-bold text-ink mb-2">Buat Agenda Bimtek Baru</h2>
        <p class="text-muted text-sm mb-6">Tambahkan jadwal kelas pelatihan atau kapasitas peningkatan kompetensi
            aparatur desa.</p>

        <form action="{{ route('admin.bimtek.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="judul" class="block text-sm font-medium text-ink mb-1">Judul Agenda Bimtek</label>
                <input type="text" name="judul" id="judul" required
                    class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                    placeholder="Contoh: Bimbingan Teknis Pengelolaan Keuangan Desa Berbasis Siskeudes 2026">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="tanggal_pelaksanaan" class="block text-sm font-medium text-ink mb-1">Tanggal
                        Pelaksanaan</label>
                    <input type="date" name="tanggal_pelaksanaan" id="tanggal_pelaksanaan" required
                        class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm text-sm p-1">
                </div>
                <div>
                    <label for="kuota" class="block text-sm font-medium text-ink mb-1">Kuota Peserta</label>
                    <input type="number" name="kuota" id="kuota" required min="1"
                        class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                        placeholder="40">
                </div>
                <div>
                    <label for="tempat" class="block text-sm font-medium text-ink mb-1">Tempat / Tautan Meeting</label>
                    <input type="text" name="tempat" id="tempat" required
                        class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                        placeholder="Contoh: Balai Kabupaten / Zoom Link">
                </div>
            </div>

            <div class="mb-6">
                <label for="deskripsi" class="block text-sm font-medium text-ink mb-1">Deskripsi Singkat Materi</label>
                <textarea name="deskripsi" id="deskripsi" rows="4"
                    class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                    placeholder="Jelaskan secara singkat materi detail pelatihan..."></textarea>
                <div class="mb-6">
                    <label for="file_undangan" class="block text-sm font-medium text-ink mb-1">Surat Undangan Resmi
                        (PDF, Opsional)</label>
                    <input type="file" name="file_undangan" id="file_undangan" accept=".pdf"
                        class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm text-sm p-1">
                    <p class="text-xs text-muted mt-1">Unggah surat undangan yang akan dilihat oleh seluruh desa.</p>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-border pt-6">
                    <a href="{{ route('admin.bimtek.index') }}"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-ink font-medium rounded-btn transition-colors text-sm">Batal</a>
                    <button type="submit"
                        class="px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">Simpan
                        Jadwal</button>
                </div>
        </form>
    </div>
</x-app-layout>