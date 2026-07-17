<x-app-layout>
    @section('title', 'Tinjau Izin Pencalonan')

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('admin.izincalon.index') }}"
                    class="text-sm font-medium text-primary hover:underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    Kembali ke Daftar Izin
                </a>
                <h2 class="text-xl font-display font-bold text-ink mt-2">Verifikasi Izin Pencalonan:
                    {{ $izincalon->nama_calon }}</h2>
                <span class="text-xs text-muted block mt-1">Asal Desa: <strong
                        class="text-ink font-semibold">{{ $izincalon->desa->nama_desa }}</strong></span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-card shadow-sm border border-border p-6 mb-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Berkas
                        Persyaratan Calon</h3>

                    <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                        <div>
                            <span class="text-muted block text-xs">Nama Lengkap Bakal Calon</span>
                            <span class="text-ink font-bold font-display">{{ $izincalon->nama_calon }}</span>
                        </div>
                        <div>
                            <span class="text-muted block text-xs">Jabatan Saat Ini</span>
                            <span class="text-ink font-medium">{{ $izincalon->jabatan_sekarang }}</span>
                        </div>
                        <div>
                            <span class="text-muted block text-xs">Kategori Aparatur</span>
                            <span class="text-ink font-medium capitalize">{{ $izincalon->jenis_calon }}</span>
                        </div>
                    </div>

                    <h4 class="text-xs font-semibold text-ink uppercase tracking-wider mb-3">Dokumen Persyaratan</h4>
                    <div class="space-y-3 text-xs font-semibold text-ink">
                        <div class="p-3 bg-gray-50/50 rounded border border-border flex items-center justify-between">
                            <span>1. Surat Keterangan Bebas Temuan Inspektorat</span>
                            <a href="{{ asset('storage/' . $izincalon->bebas_temuan_inspektorat_path) }}"
                                target="_blank" class="text-primary hover:underline font-medium">Lihat Dokumen</a>
                        </div>
                        <div class="p-3 bg-gray-50/50 rounded border border-border flex items-center justify-between">
                            <span>2. Berkas Syarat Administratif Lainnya</span>
                            <a href="{{ asset('storage/' . $izincalon->berkas_syarat_path) }}" target="_blank"
                                class="text-primary hover:underline font-medium">Lihat Dokumen</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-card shadow-sm border border-border p-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Keputusan
                        Kelayakan (Clearance)</h3>

                    @if($izincalon->status === 'approved')
                        <div class="p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm">
                            <strong class="font-bold block">Status: Disetujui (Lolos clearance)</strong>
                            <p class="text-xs mt-1">Bakal calon bersih dari temuan tuntutan ganti rugi Inspektorat. Izin
                                pencalonan diterbitkan.</p>
                            @if($izincalon->catatan_inspektorat)
                                <div class="mt-3 p-3 bg-white/70 rounded border border-green-200 text-xs">
                                    <strong>Catatan:</strong>
                                    <p class="mt-0.5">{{ $izincalon->catatan_inspektorat }}</p>
                                </div>
                            @endif
                        </div>
                    @elseif($izincalon->status === 'rejected')
                        <div class="p-4 bg-red-50 text-red-800 rounded border border-red-200 text-sm">
                            <strong class="font-bold block">Status: Ditolak</strong>
                            <p class="text-xs mt-1">Calon memiliki catatan temuan atau berkas bermasalah. Izin ditolak.</p>
                        </div>
                    @else
                        <!-- Form check -->
                        <form action="{{ route('admin.izincalon.verifikasi', $izincalon) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-xs font-semibold text-ink uppercase tracking-wider mb-2">Terdeteksi
                                    Temuan Inspektorat?</label>
                                <div class="flex items-center gap-4">
                                    <label class="inline-flex items-center text-sm text-ink pointer-events-auto">
                                        <input type="radio" name="has_temuan" value="no" checked
                                            class="form-radio text-primary focus:ring-primary">
                                        <span class="ml-2 font-semibold">Tidak (Clean)</span>
                                    </label>
                                    <label class="inline-flex items-center text-sm text-ink pointer-events-auto">
                                        <input type="radio" name="has_temuan" value="yes"
                                            class="form-radio text-primary focus:ring-primary">
                                        <span class="ml-2 font-semibold text-red-600">Ya (Ada Temuan)</span>
                                    </label>
                                </div>
                                <span class="text-[10px] text-muted block mt-1">Aturan Sistem: Jika diatur "Ya (Ada
                                    Temuan)", persetujuan izin akan terkunci (auto-reject) oleh sistem.</span>
                            </div>

                            <div class="mb-4">
                                <label for="status"
                                    class="block text-xs font-semibold text-ink uppercase tracking-wider mb-1">Keputusan
                                    Administrasi</label>
                                <select name="status" id="status" required
                                    class="w-full text-xs rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm">
                                    <option value="approved">Setujui Izin Pencalonan</option>
                                    <option value="rejected">Tolak Izin Pencalonan</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="catatan_inspektorat"
                                    class="block text-xs font-semibold text-ink uppercase tracking-wider mb-1">Catatan
                                    Keterangan Inspektorat</label>
                                <textarea name="catatan_inspektorat" id="catatan_inspektorat" rows="4"
                                    class="w-full text-xs rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                                    placeholder="Contoh: Bebas dari temuan per April 2026..."></textarea>
                            </div>

                            <button type="submit"
                                class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors text-sm shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Simpan Verifikasi
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>