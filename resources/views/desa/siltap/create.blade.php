<x-app-layout>
    @section('title', 'Ajukan Pencairan Siltap')

    <div class="max-w-3xl mx-auto bg-white rounded-card shadow-sm border border-border p-8 mb-8">
        <h2 class="text-xl font-display font-bold text-ink mb-2">Ajukan Pencairan Siltap Baru</h2>
        <p class="text-muted text-sm mb-6">Ajukan berkas kelengkapan pencairan Penghasilan Tetap (Siltap) Desa untuk bulan tertentu.</p>

        <form action="{{ route('desa.siltap.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="bulan" class="block text-sm font-medium text-ink mb-1">Bulan Pencairan</label>
                    <select name="bulan" id="bulan" required class="w-full rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm text-sm">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                        @endselect
                    </select>
                </div>
                <div>
                    <label for="tahun" class="block text-sm font-medium text-ink mb-1">Tahun</label>
                    <input type="number" name="tahun" id="tahun" required min="2020" value="{{ now()->format('Y') }}" class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm">
                </div>
            </div>

            <div class="space-y-4 mb-6">
                <div>
                    <label for="rekomendasi_camat" class="block text-sm font-medium text-ink mb-1">Rekomendasi Camat (.pdf)</label>
                    <input type="file" name="rekomendasi_camat" id="rekomendasi_camat" required class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1">
                </div>
                <div>
                    <label for="bukti_bpjs" class="block text-sm font-medium text-ink mb-1">Surat Keterangan Bebas Pinjaman Daerah / Bukti Pembayaran BPJS (.pdf)</label>
                    <input type="file" name="bukti_bpjs" id="bukti_bpjs" required class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1">
                </div>
                <div>
                    <label for="spj_sebelumnya" class="block text-sm font-medium text-ink mb-1">Laporan Pertanggungjawaban (SPJ) Bulan Sebelumnya (.pdf)</label>
                    <input type="file" name="spj_sebelumnya" id="spj_sebelumnya" required class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-border pt-6">
                <a href="{{ route('desa.siltap.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-ink font-medium rounded-btn transition-colors text-sm">Batal</a>
                <button type="submit" class="px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">Kirim Ajuan Siltap</button>
            </div>
        </form>
    </div>
</x-app-layout>
