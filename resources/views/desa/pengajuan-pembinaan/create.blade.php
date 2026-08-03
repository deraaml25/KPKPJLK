<x-app-layout>
    @section('title', 'Ajukan Pembinaan ke Dinpermasdes')

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-card shadow-sm border border-border p-8">
            <div class="mb-6">
                <a href="{{ route('desa.pengajuan-pembinaan.index') }}"
                    class="text-sm text-primary hover:underline flex items-center gap-1 mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </a>
                <h2 class="text-xl font-display font-bold text-ink">Ajukan Permohonan Pembinaan</h2>
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

            <form action="{{ route('desa.pengajuan-pembinaan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="judul_kegiatan" class="block text-sm font-medium text-ink mb-1">
                        Judul / Nama Kegiatan Pembinaan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul_kegiatan" id="judul_kegiatan" required value="{{ old('judul_kegiatan') }}"
                        class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                        placeholder="Contoh: Pelatihan Pengelolaan Keuangan Desa Tahun 2026">
                </div>

                <div>
                    <label for="tanggal_diajukan" class="block text-sm font-medium text-ink mb-1">
                        Tanggal Rencana Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_diajukan" id="tanggal_diajukan" required
                        value="{{ old('tanggal_diajukan') }}"
                        class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm text-sm p-2">
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-ink mb-1">Deskripsi / Latar Belakang</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4"
                        class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                        placeholder="Jelaskan latar belakang, tujuan, dan manfaat kegiatan pembinaan yang diusulkan...">{{ old('deskripsi') }}</textarea>
                </div>

                <!-- Upload Persyaratan -->
                <div class="border border-blue-200 bg-blue-50 rounded-lg p-5">
                    <h3 class="text-sm font-display font-bold text-blue-800 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Dokumen Persyaratan
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label for="file_surat_permohonan" class="block text-sm font-medium text-ink mb-1">
                                Surat Permohonan / Undangan Narasumber
                                <span class="text-xs text-muted font-normal">(PDF, maks 10MB)</span>
                            </label>
                            <input type="file" name="file_surat_permohonan" id="file_surat_permohonan"
                                accept=".pdf"
                                class="w-full rounded-md border-border bg-white text-ink text-sm p-1.5" required>
                            <p class="text-xs text-blue-600 mt-1">
                                Surat resmi dari Kepala Desa yang ditujukan kepada Kepala Dinpermasdes.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-border pt-5">
                    <a href="{{ route('desa.pengajuan-pembinaan.index') }}"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-ink font-medium rounded-btn transition-colors text-sm">Batal</a>
                    <button type="submit"
                        class="px-5 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
