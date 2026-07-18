<x-app-layout>
    @section('title', 'Ajukan Pencairan Siltap')

    <div class="max-w-3xl mx-auto bg-white rounded-card shadow-sm border border-border p-8 mb-8">
        <h2 class="text-xl font-display font-bold text-ink mb-2">Ajukan Pencairan Siltap</h2>
        <p class="text-muted text-sm mb-6">Upload 3 dokumen wajib untuk mengajukan pencairan penghasilan tetap &
            tunjangan perangkat desa.</p>

        <div class="bg-blue-50 text-blue-800 p-4 rounded-md text-sm mb-6 border border-blue-200">
            <strong>ℹ️ Info Pengajuan</strong>
            <p class="mt-1">Periode: <strong>Bulan {{ $bulanIni }} / {{ $tahunIni }}</strong> — Jumlah Perangkat Aktif:
                <strong>{{ $jumlahPerangkatAktif }} orang</strong></p>
        </div>

        @if($errors->has('lock'))
            <div class="bg-red-50 text-red-700 p-4 rounded-md text-sm mb-6 border border-red-200">
                🔒 {{ $errors->first('lock') }}
            </div>
        @endif

        <form action="{{ route('desa.siltap.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="bulan" value="{{ $bulanIni }}">
            <input type="hidden" name="tahun" value="{{ $tahunIni }}">

            <div class="space-y-5">
                <div>
                    <label for="rekomendasi_camat" class="block text-sm font-medium text-ink mb-1">
                        1. Surat Rekomendasi Kecamatan (PDF) <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="rekomendasi_camat" id="rekomendasi_camat" required accept=".pdf"
                        class="w-full rounded-md border-border text-ink bg-white shadow-sm text-sm p-1">
                    <p class="text-xs text-muted mt-1">Rekomendasi Camat terkait pencairan Alokasi Dana Desa.</p>
                </div>

                <div>
                    <label for="bukti_bpjs" class="block text-sm font-medium text-ink mb-1">
                        2. Bukti Setor BPJS Kesehatan & Ketenagakerjaan (PDF) <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="bukti_bpjs" id="bukti_bpjs" required accept=".pdf"
                        class="w-full rounded-md border-border text-ink bg-white shadow-sm text-sm p-1">
                    <p class="text-xs text-muted mt-1">Bukti pembayaran iuran BPJS untuk seluruh perangkat desa.</p>
                </div>

                <div>
                    <label for="spj_sebelumnya" class="block text-sm font-medium text-ink mb-1">
                        3. Laporan SPJ Penggunaan Dana Bulan Lalu (PDF) <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="spj_sebelumnya" id="spj_sebelumnya" required accept=".pdf"
                        class="w-full rounded-md border-border text-ink bg-white shadow-sm text-sm p-1">
                    <p class="text-xs text-muted mt-1">Surat Pertanggungjawaban penggunaan dana bulan sebelumnya.</p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-border pt-6 mt-6">
                <a href="{{ route('desa.siltap.index') }}"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-ink font-medium rounded-btn transition-colors text-sm">Batal</a>
                <button type="submit"
                    class="px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">Kirim
                    Pengajuan</button>
            </div>
        </form>
    </div>
</x-app-layout>