<x-app-layout>
    @section('title', 'Tinjau Rekap Pilkades')

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('admin.pilkades.index') }}" class="text-sm font-medium text-primary hover:underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Kembali ke Daftar Pilkades
                </a>
                <h2 class="text-xl font-display font-bold text-ink mt-2">Rekapitulasi Suara & Penetapan pilkades: {{ $pilkades->desa->nama_desa }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs text-muted">Tanggal Voting: {{ $pilkades->tanggal_pemungutan ? $pilkades->tanggal_pemungutan->format('d M Y') : '-' }}</span>
                    <span class="text-gray-300">•</span>
                    <span class="text-xs text-muted">Status: <strong class="text-ink capitalize">{{ $pilkades->status }}</strong></span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-card shadow-sm border border-border p-6 mb-6">
                    <h3 class="text-md font-display font-bold text-ink pb-3 border-b border-border mb-4">Laporan Hitung Suara TPS</h3>
                    
                    <div class="overflow-x-auto">
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
                                @forelse($pilkades->suaras as $suara)
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
                                @if($pilkades->suaras->count() > 0)
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
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-card shadow-sm border border-border p-6">
                    <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Ketuk Selesai & SK Bupati</h3>

                    @if($pilkades->status === 'selesai')
                        <div class="p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm">
                            <strong class="font-bold block">Status: Pilkades Rampung</strong>
                            <p class="text-xs mt-1">Keputusan pemenang Pilkades dan Surat Keputusan (SK) Bupati pelantikan kades telah diterbitkan.</p>
                            <div class="mt-3 p-3 bg-white/70 rounded border border-green-200 text-xs">
                                <strong>Kades Terpilih:</strong>
                                <p class="mt-0.5 font-bold">{{ $pilkades->pemenang_nama }}</p>
                            </div>
                            <a href="{{ asset('storage/' . $pilkades->sk_bupati_path) }}" target="_blank" class="mt-3 block hover:underline text-xs font-semibold">Tinjau File SK Pelantikan →</a>
                        </div>
                    @else
                        <form action="{{ route('admin.pilkades.generate-sk', $pilkades) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="pemenang_nama" class="block text-xs font-semibold text-ink uppercase tracking-wider mb-1">Nama Kades Terpilih</label>
                                <input type="text" name="pemenang_nama" id="pemenang_nama" required class="w-full text-xs rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm" placeholder="Contoh: Budi Santoso, S.Sos">
                            </div>
                            
                            <div class="mb-4">
                                <label for="sk_bupati" class="block text-xs font-semibold text-ink uppercase tracking-wider mb-1">Unggah PDF SK Bupati Pelantikan (.pdf)</label>
                                <input type="file" name="sk_bupati" id="sk_bupati" required class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1">
                            </div>

                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors text-sm shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Sahkan Hasil & Terbit SK
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
