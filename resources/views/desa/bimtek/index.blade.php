<x-app-layout>
    @section('title', 'e-Bimtek Desa')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <h2 class="text-xl font-display font-bold text-ink">e-Bimtek (Bimbingan Teknis)</h2>
        <p class="text-muted text-sm mt-1">Jadwal pelatihan aparatur desa dari Dinpermasdes. Daftarkan perangkat desa
            Anda untuk mengikuti Bimtek.</p>
    </div>

    <!-- Jadwal Bimtek Tersedia -->
    <div class="bg-white rounded-card shadow-sm border border-border p-6 mb-6">
        <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">📅 Jadwal Bimtek</h3>
        <div class="space-y-4">
            @forelse ($bimteks as $bimtek)
                <div
                    class="border border-border rounded-lg p-4 hover:shadow-sm transition-shadow {{ in_array($bimtek->id, $registeredBimtekIds) ? 'bg-green-50 border-green-200' : '' }}">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div class="flex-1">
                            <h4 class="font-display font-bold text-ink text-base">{{ $bimtek->judul }}</h4>
                            <div class="flex items-center gap-3 text-xs text-muted mt-1">
                                <span>📅 {{ $bimtek->tanggal_pelaksanaan->format('d M Y') }}</span>
                                <span>📍 {{ $bimtek->tempat }}</span>
                                <span>👥
                                    {{ $bimtek->pendaftarans_count ?? $bimtek->pendaftarans->count() }}/{{ $bimtek->kuota }}
                                    peserta</span>
                            </div>
                            @if($bimtek->deskripsi)
                                <p class="text-sm text-muted mt-2">{{ Str::limit($bimtek->deskripsi, 150) }}</p>
                            @endif
                            @if($bimtek->file_undangan)
                                <a href="{{ asset('storage/' . $bimtek->file_undangan) }}" target="_blank"
                                    class="inline-flex items-center text-primary text-xs hover:underline mt-2">📄 Unduh Surat
                                    Undangan</a>
                            @endif
                        </div>

                        <div class="flex-shrink-0">
                            @if(in_array($bimtek->id, $registeredBimtekIds))
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">✅
                                    Sudah Terdaftar</span>
                            @elseif($bimtek->sisa_kuota <= 0)
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Kuota
                                    Penuh</span>
                            @else
                                <form action="{{ route('desa.bimtek.daftar', $bimtek) }}" method="POST" class="inline">
                                    @csrf
                                    <div class="flex items-center gap-2">
                                        <select name="perangkat_desa_id" required
                                            class="text-xs rounded-md border-border shadow-sm">
                                            <option value="">-- Pilih Perangkat --</option>
                                            @foreach(\App\Models\PerangkatDesa::where('desa_id', auth()->user()->desa_id)->where('status_aktif', true)->get() as $p)
                                                <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->jabatan }})</option>
                                            @endforeach
                                        </select>
                                        <button type="submit"
                                            class="px-3 py-1.5 bg-primary text-white text-xs font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm whitespace-nowrap">Daftar</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <x-empty-state
                    icon="<path stroke-linecap='round' stroke-linejoin='round' d='M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' />"
                    title="Jadwal Bimtek Kosong" message="Belum ada publikasi jadwal Bimtek dari Dinpermasdes Kabupaten." />
            @endforelse
        </div>
    </div>

    <!-- Riwayat Pendaftaran & RTL -->
    @if($myPendaftarans->isNotEmpty())
        <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
            <div class="px-6 py-4 border-b border-border">
                <h3 class="text-md font-display font-bold text-ink">📋 Riwayat Pendaftaran & RTL</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-border">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-muted uppercase">Bimtek</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-muted uppercase">Perangkat</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-muted uppercase">Presensi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-muted uppercase">Status RTL</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-muted uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-border">
                        @foreach($myPendaftarans as $pendaftaran)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-4 py-3 text-sm text-ink font-medium">{{ $pendaftaran->bimtek->judul ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-ink">
                                    {{ $pendaftaran->perangkatDesa->nama ?? '-' }}
                                    <span
                                        class="text-xs text-muted block">{{ $pendaftaran->perangkatDesa->jabatan ?? '' }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($pendaftaran->status_presensi === 'hadir')
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">✅
                                            Hadir</span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">⏳
                                            Menunggu Presensi</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($pendaftaran->status_rtl === 'selesai')
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">✅
                                            Tuntas</span>
                                    @elseif($pendaftaran->status_rtl === 'menunggu_validasi')
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">🟡
                                            Menunggu Validasi</span>
                                    @elseif($pendaftaran->status_rtl === 'revisi')
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">🔴
                                            Perlu Revisi</span>
                                        @if($pendaftaran->catatan_revisi_rtl)
                                            <span
                                                class="text-xs text-red-600 block mt-0.5">{{ $pendaftaran->catatan_revisi_rtl }}</span>
                                        @endif
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Menunggu
                                            RTL</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    {{-- Materi & Sertifikat hanya muncul jika sudah hadir --}}
                                    @if($pendaftaran->status_presensi === 'hadir')
                                        @if($pendaftaran->bimtek->file_materi)
                                            <a href="{{ asset('storage/' . $pendaftaran->bimtek->file_materi) }}" target="_blank"
                                                class="text-primary text-xs hover:underline block">📥 Unduh Materi</a>
                                        @endif
                                        @if($pendaftaran->bimtek->file_sertifikat)
                                            <a href="{{ asset('storage/' . $pendaftaran->bimtek->file_sertifikat) }}" target="_blank"
                                                class="text-primary text-xs hover:underline block">📥 e-Sertifikat</a>
                                        @endif

                                        {{-- Form Upload RTL --}}
                                        @if(in_array($pendaftaran->status_rtl, ['menunggu_rtl', 'revisi']))
                                            <form action="{{ route('desa.bimtek.upload-rtl', $pendaftaran) }}" method="POST"
                                                enctype="multipart/form-data" class="mt-2 border-t border-border pt-2">
                                                @csrf
                                                <input type="file" name="file_rtl" class="w-full text-xs rounded-md border-border mb-1"
                                                    accept=".pdf,.doc,.docx" required>
                                                <button type="submit"
                                                    class="w-full px-2 py-1 bg-primary text-white text-xs font-medium rounded hover:bg-primary-light transition-colors">Unggah
                                                    RTL</button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-xs text-muted italic">Akses materi dikunci hingga presensi
                                            divalidasi</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-app-layout>