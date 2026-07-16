<x-app-layout>
    @section('title', 'Buat Ajuan Rekomendasi Baru')

    <div class="mb-6">
        <a href="{{ route('desa.ajuan.index') }}" class="inline-flex items-center text-sm font-medium text-muted hover:text-ink">
            <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Ajuan
        </a>
    </div>

    <!-- Alert Error -->
    @if($errors->any())
        <div class="mb-6 p-4 rounded-card bg-red-50 border border-red-200 text-red-700 flex items-start">
            <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <strong class="font-medium">Terjadi Kesalahan:</strong>
                <ul class="list-disc list-inside mt-1 text-sm">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="bg-surface rounded-card border border-border shadow-sm p-8 max-w-3xl mx-auto" x-data="ajuanForm()">
        <h2 class="text-2xl font-display font-bold text-ink mb-2">Form Ajuan Layanan</h2>
        <p class="text-muted text-sm mb-8">Pilih jenis layanan, lalu pilih perangkat desa. Checklist dokumen akan otomatis menyesuaikan jenis layanan yang Anda pilih. Anda dapat mengunggah dokumen nanti di halaman detail.</p>
        
        <form method="POST" action="{{ route('desa.ajuan.store') }}">
            @csrf

            <!-- 1. Jenis Layanan -->
            <div class="mb-6">
                <label for="jenis_layanan_id" class="block text-sm font-medium text-ink mb-2">Jenis Layanan <span class="text-danger">*</span></label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($jenisLayanans as $jl)
                        <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none" :class="{ 'border-primary ring-1 ring-primary bg-primary-soft/30': jenisLayanan == {{ $jl->id }}, 'border-border': jenisLayanan != {{ $jl->id }} }">
                            <input type="radio" name="jenis_layanan_id" value="{{ $jl->id }}" class="sr-only" x-model="jenisLayanan" @change="checkAlasanRequired('{{ strtolower($jl->nama) }}')">
                            <span class="flex flex-1">
                                <span class="flex flex-col">
                                    <span class="block text-sm font-medium" :class="{ 'text-primary': jenisLayanan == {{ $jl->id }}, 'text-ink': jenisLayanan != {{ $jl->id }} }">{{ $jl->nama }}</span>
                                </span>
                            </span>
                            <svg class="h-5 w-5 text-primary" :class="{ 'invisible': jenisLayanan != {{ $jl->id }} }" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- 2. Alasan Pemberhentian (Hanya jika jenis = Pemberhentian) -->
            <div class="mb-6 p-5 bg-gray-50 rounded-lg border border-border" x-show="showAlasan" style="display: none;" x-transition>
                <label for="alasan_pemberhentian_id" class="block text-sm font-medium text-ink mb-2">Alasan Pemberhentian <span class="text-danger">*</span></label>
                <select name="alasan_pemberhentian_id" id="alasan_pemberhentian_id" class="w-full rounded-btn border-border text-sm text-ink focus:ring-primary focus:border-primary shadow-sm" x-bind:required="showAlasan">
                    <option value="">-- Pilih Alasan --</option>
                    @foreach($alasanPemberhentians as $alasan)
                        <option value="{{ $alasan->id }}" {{ old('alasan_pemberhentian_id') == $alasan->id ? 'selected' : '' }}>{{ $alasan->nama }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-muted mt-2"><svg class="w-4 h-4 inline-block mr-1 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Checklist dokumen pemberhentian akan berbeda tergantung pada alasan yang dipilih (misal: syarat untuk meninggal dunia berbeda dengan purna tugas).</p>
            </div>

            <!-- 3. Perangkat Desa -->
            <div class="mb-8">
                <label for="perangkat_desa_id" class="block text-sm font-medium text-ink mb-2">Pilih Perangkat Desa <span class="text-danger">*</span></label>
                <select name="perangkat_desa_id" id="perangkat_desa_id" class="w-full rounded-btn border-border text-sm text-ink focus:ring-primary focus:border-primary shadow-sm" required>
                    <option value="">-- Pilih Perangkat --</option>
                    @foreach($perangkatDesas as $perangkat)
                        <option value="{{ $perangkat->id }}" {{ old('perangkat_desa_id') == $perangkat->id ? 'selected' : '' }}>{{ $perangkat->nama }} — {{ $perangkat->jabatan }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-muted mt-2">Jika perangkat tidak ada, pastikan data perangkat sudah ditambahkan di menu Data Perangkat Desa.</p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-border">
                <a href="{{ route('desa.ajuan.index') }}" class="px-5 py-2.5 text-sm font-medium text-ink bg-white border border-border rounded-btn hover:bg-gray-50 transition-colors shadow-sm">Batal</a>
                <button type="submit" name="draft" value="1" class="px-5 py-2.5 text-sm font-medium text-primary bg-primary-soft border border-primary-soft rounded-btn hover:bg-primary-light hover:text-white transition-colors shadow-sm">Simpan sebagai Draft</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-primary rounded-btn hover:bg-primary-light transition-colors shadow-sm shadow-primary/30">Buat Ajuan & Lanjut Upload</button>
            </div>
        </form>
    </div>

    <!-- Script for interaction -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('ajuanForm', () => ({
                jenisLayanan: '{{ old('jenis_layanan_id') }}',
                showAlasan: false,
                
                init() {
                    // Cek di awal jika old data terisi
                    @if(old('jenis_layanan_id'))
                        const selectedJl = {!! \App\Models\JenisLayanan::find(old('jenis_layanan_id'))->toJson() ?? 'null' !!};
                        if (selectedJl && selectedJl.nama.toLowerCase() === 'pemberhentian') {
                            this.showAlasan = true;
                        }
                    @endif
                },
                
                checkAlasanRequired(namaLayanan) {
                    this.showAlasan = namaLayanan === 'pemberhentian';
                    if (!this.showAlasan) {
                        document.getElementById('alasan_pemberhentian_id').value = '';
                    }
                }
            }))
        })
    </script>
</x-app-layout>
