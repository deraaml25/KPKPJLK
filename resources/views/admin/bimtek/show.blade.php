<x-app-layout>
    @section('title', 'Detail Bimtek: ' . $bimtek->judul)

    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
            <a href="{{ route('admin.bimtek.index') }}"
                class="text-sm font-medium text-primary hover:underline flex items-center gap-1 mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Daftar Bimtek
            </a>
            <h2 class="text-xl font-display font-bold text-ink">{{ $bimtek->judul }}</h2>
            <div class="flex items-center gap-4 mt-2 text-sm text-muted">
                <span>📅 {{ $bimtek->tanggal_pelaksanaan->format('d M Y') }}</span>
                <span>📍 {{ $bimtek->tempat }}</span>
                <span>👥 {{ $bimtek->pendaftarans->count() }} / {{ $bimtek->kuota }} peserta</span>
            </div>
            @if($bimtek->file_undangan)
                <a href="{{ asset('storage/' . $bimtek->file_undangan) }}" target="_blank"
                    class="inline-flex items-center mt-3 px-3 py-1.5 border border-border text-ink hover:bg-gray-50 font-medium rounded-btn text-sm">📄
                    Unduh Surat Undangan</a>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Panel Kiri: Upload Materi & Sertifikat -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-card shadow-sm border border-border p-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Materi &
                        Sertifikat</h3>

                    @if($bimtek->file_materi)
                        <div class="mb-3"><span class="text-xs text-muted block mb-1">Materi Bimtek</span><a
                                href="{{ asset('storage/' . $bimtek->file_materi) }}" target="_blank"
                                class="text-primary text-sm hover:underline">📥 Unduh Materi</a></div>
                    @endif

                    @if($bimtek->file_sertifikat)
                        <div class="mb-4"><span class="text-xs text-muted block mb-1">Template Sertifikat</span><a
                                href="{{ asset('storage/' . $bimtek->file_sertifikat) }}" target="_blank"
                                class="text-primary text-sm hover:underline">📥 Unduh Sertifikat</a></div>
                    @endif

                    <form action="{{ route('admin.bimtek.upload-materi', $bimtek) }}" method="POST"
                        enctype="multipart/form-data" class="border-t border-border pt-4 mt-4">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-xs font-semibold text-ink mb-1">Upload Materi (PDF/PPT)</label>
                            <input type="file" name="file_materi" class="w-full text-xs rounded-md border-border"
                                accept=".pdf,.pptx,.ppt,.doc,.docx">
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-semibold text-ink mb-1">Upload Sertifikat (PDF)</label>
                            <input type="file" name="file_sertifikat" class="w-full text-xs rounded-md border-border"
                                accept=".pdf">
                        </div>
                        <button type="submit"
                            class="w-full px-3 py-2 bg-primary text-white text-sm font-medium rounded-btn hover:bg-primary-light transition-colors">Upload
                            File</button>
                    </form>
                </div>

                <!-- Analitik RTL -->
                <div class="bg-white rounded-card shadow-sm border border-border p-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Analitik RTL
                    </h3>
                    @php
                        $totalPeserta = $bimtek->pendaftarans->count();
                        $hadirCount = $bimtek->pendaftarans->where('status_presensi', 'hadir')->count();
                        $rtlMasuk = $bimtek->pendaftarans->whereNotNull('file_rtl')->count();
                        $rtlSelesai = $bimtek->pendaftarans->where('status_rtl', 'selesai')->count();
                    @endphp
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-muted">Total Terdaftar</span><span
                                class="font-bold text-ink">{{ $totalPeserta }}</span></div>
                        <div class="flex justify-between"><span class="text-muted">Hadir</span><span
                                class="font-bold text-green-600">{{ $hadirCount }}</span></div>
                        <div class="flex justify-between"><span class="text-muted">RTL Masuk</span><span
                                class="font-bold text-yellow-600">{{ $rtlMasuk }}</span></div>
                        <div class="flex justify-between"><span class="text-muted">RTL Tuntas</span><span
                                class="font-bold text-green-600">{{ $rtlSelesai }}</span></div>
                        <div class="flex justify-between"><span class="text-muted">Belum RTL</span><span
                                class="font-bold text-red-600">{{ $hadirCount - $rtlMasuk }}</span></div>
                    </div>
                </div>
            </div>

            <!-- Panel Kanan: Daftar Peserta + Presensi + RTL -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
                    <div class="px-6 py-4 border-b border-border">
                        <h3 class="text-md font-display font-bold text-ink">Daftar Peserta & Manajemen</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-muted uppercase">Desa</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-muted uppercase">Perangkat
                                        Ditugaskan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-muted uppercase">Presensi
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-muted uppercase">Status RTL
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-muted uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-border">
                                @forelse ($bimtek->pendaftarans as $p)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="px-4 py-3 text-sm text-ink font-medium">{{ $p->desa->nama_desa ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-ink">{{ $p->perangkatDesa->nama ?? '-' }} <span
                                                class="text-xs text-muted block">{{ $p->perangkatDesa->jabatan ?? '' }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($p->status_presensi === 'hadir')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">✅
                                                    Hadir</span>
                                            @else
                                                <form action="{{ route('admin.bimtek.presensi', $p) }}" method="POST"
                                                    class="inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status_presensi" value="hadir">
                                                    <button type="submit"
                                                        class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 transition-colors">Validasi
                                                        Hadir</button>
                                                </form>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($p->status_rtl === 'selesai')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">✅
                                                    Tuntas</span>
                                            @elseif($p->status_rtl === 'menunggu_validasi')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">🟡
                                                    Menunggu Validasi</span>
                                            @elseif($p->status_rtl === 'revisi')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">🔴
                                                    Revisi</span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Menunggu
                                                    RTL</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($p->file_rtl)
                                                <a href="{{ asset('storage/' . $p->file_rtl) }}" target="_blank"
                                                    class="text-primary text-xs hover:underline block mb-1">📄 Lihat RTL</a>
                                            @endif

                                            @if($p->status_rtl === 'menunggu_validasi')
                                                <div class="flex gap-1 mt-1">
                                                    <form action="{{ route('admin.bimtek.validasi-rtl', $p) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status_rtl" value="selesai">
                                                        <button type="submit"
                                                            class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded hover:bg-green-100 border border-green-200">✓
                                                            Tuntas</button>
                                                    </form>
                                                    <form action="{{ route('admin.bimtek.validasi-rtl', $p) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status_rtl" value="revisi">
                                                        <button type="submit"
                                                            class="px-2 py-1 bg-red-50 text-red-700 text-xs rounded hover:bg-red-100 border border-red-200">✗
                                                            Revisi</button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-10 text-center text-sm text-muted">Belum ada peserta
                                            terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>