<x-app-layout>
    @section('title', 'e-Pilkades Desa')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <div>
            <h2 class="text-xl font-display font-bold text-ink">e-Pilkades (Real-time Q-Count & Tracking)</h2>
            <p class="text-muted text-sm mt-1">Pemantauan tahapan persiapan, pemilihan, rekapitulasi suara TPS, hingga penetapan kepala desa terpilih secara real-time.</p>
        </div>
    </div>

    @if(!$pilkadesObj)
        <div class="p-6 bg-white border border border-border text-center rounded-card text-muted text-sm shadow-sm">
            Fasilitasi Pilkades untuk desa Anda belum didaftarkan/dijadwalkan oleh dinas terkait kab.
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-card shadow-sm border border-border p-6 mb-6">
                    <div class="flex items-center justify-between pb-3 border-b border-border mb-4">
                        <h3 class="text-md font-display font-bold text-ink">Laporan Hasil Hitung Suara TPS</h3>
                        <span class="text-xs text-muted font-bold font-mono">Total TPS: {{ $pilkadesObj->total_tps }}</span>
                    </div>

                    <!-- TPS Table -->
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full divide-y divide-border text-xs">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-2 text-left font-medium text-muted uppercase">Nama TPS</th>
                                    <th scope="col" class="px-4 py-2 text-center font-medium text-muted uppercase">Calon 1</th>
                                    <th scope="col" class="px-4 py-2 text-center font-medium text-muted uppercase">Calon 2</th>
                                    <th scope="col" class="px-4 py-2 text-center font-medium text-muted uppercase">Calon 3</th>
                                    <th scope="col" class="px-4 py-2 text-center font-medium text-muted uppercase font-bold">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                @php 
                                    $tot1 = 0; $tot2 = 0; $tot3 = 0;
                                @endphp
                                @forelse($pilkadesObj->suaras as $suara)
                                    @php
                                        $tot1 += $suara->suara_calon_1;
                                        $tot2 += $suara->suara_calon_2;
                                        $tot3 += $suara->suara_calon_3;
                                        $sub = $suara->suara_calon_1 + $suara->suara_calon_2 + $suara->suara_calon_3;
                                    @endphp
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="px-4 py-3 font-semibold text-ink">{{ $suara->tps_name }}</td>
                                        <td class="px-4 py-3 text-center text-ink">{{ $suara->suara_calon_1 }}</td>
                                        <td class="px-4 py-3 text-center text-ink">{{ $suara->suara_calon_2 }}</td>
                                        <td class="px-4 py-3 text-center text-ink">{{ $suara->suara_calon_3 }}</td>
                                        <td class="px-4 py-3 text-center text-ink font-bold">{{ $sub }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-muted text-xs">Belum ada input perolehan suara TPS.</td>
                                    </tr>
                                @endforelse
                                @if($pilkadesObj->suaras->count() > 0)
                                    <tr class="bg-gray-50 font-bold text-ink">
                                        <td class="px-4 py-3">Total Akumulasi</td>
                                        <td class="px-4 py-3 text-center">{{ $tot1 }}</td>
                                        <td class="px-4 py-3 text-center">{{ $tot2 }}</td>
                                        <td class="px-4 py-3 text-center">{{ $tot3 }}</td>
                                        <td class="px-4 py-3 text-center text-primary font-extrabold">{{ $tot1 + $tot2 + $tot3 }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @if($pilkadesObj->status === 'pemilihan' || $pilkadesObj->status === 'persiapan')
                        <h4 class="text-xs font-semibold text-ink uppercase tracking-wider mb-3">Input Hasil Suara TPS baru</h4>
                        <form action="{{ route('desa.pilkades.store-suara', $pilkadesObj) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            @csrf
                            <div>
                                <label for="tps_name" class="block text-[11px] font-medium text-ink mb-1">Nama TPS</label>
                                <input type="text" name="tps_name" id="tps_name" required class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1.5" placeholder="Contoh: TPS 01 / TPS 02">
                            </div>
                            <div>
                                <label for="suara_calon_1" class="block text-[11px] font-medium text-ink mb-1">Calon 1</label>
                                <input type="number" name="suara_calon_1" id="suara_calon_1" required min="0" class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1.5" placeholder="Suara">
                            </div>
                            <div>
                                <label for="suara_calon_2" class="block text-[11px] font-medium text-ink mb-1">Calon 2</label>
                                <input type="number" name="suara_calon_2" id="suara_calon_2" required min="0" class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1.5" placeholder="Suara">
                            </div>
                            <div>
                                <label for="suara_calon_3" class="block text-[11px] font-medium text-ink mb-1">Calon 3</label>
                                <input type="number" name="suara_calon_3" id="suara_calon_3" required min="0" class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1.5" placeholder="Suara">
                            </div>
                            <div class="md:col-span-4 flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-primary text-white font-medium text-xs font-semibold rounded-btn hover:bg-primary-light transition-colors">
                                    Simpan Suara TPS
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-card shadow-sm border border-border p-6 mb-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Status Pilkades</h3>
                    
                    <div class="space-y-4 text-sm text-ink">
                        <div>
                            <span class="text-xs text-muted block">Tanggal Pemungutan Suara</span>
                            <span class="font-bold">{{ $pilkadesObj->tanggal_pemungutan ? $pilkadesObj->tanggal_pemungutan->format('d F Y') : '-' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-muted block">Tahapan Saat Ini</span>
                            @if($pilkadesObj->status === 'selesai')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800 uppercase">Selesai & Disahkan</span>
                            @elseif($pilkadesObj->status === 'pemilihan')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-800 uppercase">Pemungutan/Rekap</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-800 uppercase">Persiapan BPD</span>
                            @endif
                        </div>

                        @if($pilkadesObj->status === 'selesai')
                            <div class="p-3 bg-green-50 border border-green-200 text-green-800 rounded text-xs">
                                <strong>Kades Terpilih Resmi:</strong>
                                <p class="mt-0.5 font-bold">{{ $pilkadesObj->pemenang_nama }}</p>
                                <a href="{{ asset('storage/' . $pilkadesObj->sk_bupati_path) }}" target="_blank" class="mt-2 block hover:underline font-semibold text-primary">Download SK Bupati Kades Terpilih →</a>
                            </div>
                        @else
                            <div class="p-3 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded text-xs">
                                <strong>Menunggu Hasil & SK Kades:</strong>
                                <p class="mt-0.5">Penetapan kades terpilih dan penerbitan SK Bupati akan diproses dinas setelah penginputan data rekapitulasi TPS dirasa lengkap dan ditutup.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
