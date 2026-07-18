<x-app-layout>
    @section('title', 'e-Pilkades - Ruang Komando Desa')

    <div class="max-w-4xl mx-auto">
        <div class="bg-primary rounded-card p-8 shadow-floating text-white relative overflow-hidden mb-6">
            <div class="relative z-10">
                <h2 class="text-3xl font-display font-bold">PIlKADES SERENTAK (Ruang Pandang Desa)</h2>
                <p class="mt-2 text-white/80 max-w-2xl">
                    Sistem Pemantauan dan Pelaporan Live Quick Count Pemilihan Kepala Desa. Laporkan perolehan suara
                    secara <em>real-time</em> dari masing-masing TPS agar dipantau langsung oleh Dinpermasdes tanpa
                    hambatan teknis rekapitulasi manual.
                </p>
                <div class="mt-6 flex flex-wrap gap-4 text-sm font-medium">
                    @if($pilkadesObj)
                        <span class="flex items-center gap-1 bg-white/10 px-3 py-1.5 rounded-full"><svg class="w-4 h-4"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg> {{ $pilkadesObj->tanggal_pemungutan->format('d F Y') }}</span>
                        <span class="flex items-center gap-1 bg-white/10 px-3 py-1.5 rounded-full">👥 DPT:
                            {{ number_format($pilkadesObj->total_dpt) }} Pemilih</span>
                        <span class="flex items-center gap-1 bg-white/10 px-3 py-1.5 rounded-full">🏠
                            {{ $pilkadesObj->total_tps }} TPS</span>
                    @endif
                </div>
            </div>
            <!-- Hitam background gradient -->
            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-primary-light/50 rounded-full blur-3xl"></div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm mb-6 font-medium">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-50 text-red-800 rounded border border-red-200 text-sm mb-6 font-medium">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="p-4 bg-red-50 text-red-800 rounded border border-red-200 text-sm mb-6">
                <strong class="font-bold block mb-1">Gagal Menyimpan Suara TPS (Masalah Integritas Data):</strong>
                <ul class="list-disc ml-5 mt-1 text-xs space-y-1">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        @if(!$pilkadesObj)
            <div class="bg-white rounded-card shadow-sm border border-border p-2">
                <x-empty-state
                    icon="<path stroke-linecap='round' stroke-linejoin='round' d='M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z' />"
                    title="Belum Ada Jadwal Pilkades"
                    message="Dinpermasdes belum menetapkan jadwal Pilkades serentak untuk desa ini. Ruang komando ini akan aktif otomatis saat jadwal diterbitkan." />
            </div>
        @else

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Kiri: Form Input Suara --}}
                <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden flex flex-col h-full">
                    <div class="p-6 border-b border-border bg-gray-50 flex-1">
                        <h3 class="text-md font-display font-bold text-ink mb-2">Input Hasil Suara Per-TPS</h3>
                        <p class="text-xs text-muted mb-6">Laporkan langsung hasil form perolehan suara (Form C1) per TPS.
                            Data dipantau real-time oleh Bupati.</p>

                        @if($pilkadesObj->isLocked())
                            <div class="p-4 bg-gray-100 border border-gray-200 rounded text-center">
                                <span class="text-4xl block mb-2">🔒</span>
                                <h4 class="font-bold text-ink mb-1">Pintu Pelaporan Ditutup</h4>
                                <p class="text-xs text-muted">Rekapitulasi telah disahkan dan di-lock oleh Dinpermasdes. Tidak
                                    ada lagi input/edit suara.</p>
                            </div>
                        @elseif($pilkadesObj->tps_lapor >= $pilkadesObj->total_tps && !$pilkadesObj->isLocked())
                            <div class="p-4 bg-green-50 border border-green-200 rounded text-center">
                                <span class="text-3xl block mb-2">🏁</span>
                                <h4 class="font-bold text-green-800 mb-1">Semua TPS Sudah Masuk</h4>
                                <p class="text-xs text-green-700">100% data selesai diinput. Menunggu verifikasi pengesahan / SK
                                    dari Admin Dinpermasdes.</p>
                            </div>
                        @else
                            <form action="{{ route('desa.pilkades.store-suara', $pilkadesObj->id) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="tps_name" class="block text-xs font-bold text-ink mb-1">Nama / Nomor TPS <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="tps_name" id="tps_name" required placeholder="Contoh: TPS 001"
                                        value="{{ old('tps_name') }}"
                                        class="w-full text-sm rounded bg-white border-border shadow-sm">
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-xs font-medium text-ink mb-1">Total Pemilih Hadir <span
                                                class="text-red-500">*</span></label>
                                        <input type="number" name="total_pemilih_hadir" required min="0"
                                            value="{{ old('total_pemilih_hadir', 0) }}"
                                            class="w-full text-sm rounded bg-white border-border shadow-sm text-center">
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block text-xs font-medium text-green-700 mb-1">Suara Sah</label>
                                            <input type="number" name="suara_sah" required min="0"
                                                value="{{ old('suara_sah', 0) }}"
                                                class="w-full text-sm rounded bg-green-50 border-green-200 text-green-800 shadow-sm text-center">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-red-700 mb-1">Tdk Sah</label>
                                            <input type="number" name="suara_tidak_sah" required min="0"
                                                value="{{ old('suara_tidak_sah', 0) }}"
                                                class="w-full text-sm rounded bg-red-50 border-red-200 text-red-800 shadow-sm text-center">
                                        </div>
                                    </div>
                                </div>

                                <div class="p-4 border border-border rounded bg-white mb-6">
                                    <h4
                                        class="text-xs font-bold uppercase tracking-wider mb-3 text-center border-b border-border pb-2">
                                        Rincian Perolehan Calon</h4>
                                    <div class="space-y-3">
                                        @if($pilkadesObj->calon_1_nama)
                                            <div class="flex items-center justify-between">
                                                <span
                                                    class="text-sm font-medium text-ink truncate mr-2">{{ $pilkadesObj->calon_1_nama }}
                                                    (C1)</span>
                                                <input type="number" name="suara_calon_1" required min="0"
                                                    value="{{ old('suara_calon_1', 0) }}"
                                                    class="w-24 text-sm rounded border-gray-300 text-center font-bold">
                                            </div>
                                        @else
                                            <input type="hidden" name="suara_calon_1" value="0">
                                        @endif

                                        @if($pilkadesObj->calon_2_nama)
                                            <div class="flex items-center justify-between">
                                                <span
                                                    class="text-sm font-medium text-ink truncate mr-2">{{ $pilkadesObj->calon_2_nama }}
                                                    (C2)</span>
                                                <input type="number" name="suara_calon_2" required min="0"
                                                    value="{{ old('suara_calon_2', 0) }}"
                                                    class="w-24 text-sm rounded border-gray-300 text-center font-bold">
                                            </div>
                                        @else
                                            <input type="hidden" name="suara_calon_2" value="0">
                                        @endif

                                        @if($pilkadesObj->calon_3_nama)
                                            <div class="flex items-center justify-between">
                                                <span
                                                    class="text-sm font-medium text-ink truncate mr-2">{{ $pilkadesObj->calon_3_nama }}
                                                    (C3)</span>
                                                <input type="number" name="suara_calon_3" required min="0"
                                                    value="{{ old('suara_calon_3', 0) }}"
                                                    class="w-24 text-sm rounded border-gray-300 text-center font-bold">
                                            </div>
                                        @else
                                            <input type="hidden" name="suara_calon_3" value="0">
                                        @endif
                                    </div>
                                </div>

                                <button type="submit"
                                    class="w-full px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">
                                    Submit Form C1 TPS
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- Kanan: TPS yang sudah masuk --}}
                <div class="bg-white rounded-card shadow-sm border border-border h-full max-h-[800px] overflow-y-auto">
                    <div class="p-6 border-b border-border sticky top-0 bg-white z-10 flex justify-between items-end">
                        <div>
                            <h3 class="text-md font-display font-bold text-ink">Laporan Masuk</h3>
                            <p class="text-xs text-muted mt-1">{{ $pilkadesObj->tps_lapor }} dari
                                {{ $pilkadesObj->total_tps }} TPS
                            </p>
                        </div>
                    </div>

                    <div class="p-6">
                        @if($pilkadesObj->suaras->isEmpty())
                            <div class="text-center py-10">
                                <span class="block text-4xl mb-2">📥</span>
                                <span class="text-sm text-muted">Belum ada TPS yang melaporkan masuk.</span>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($pilkadesObj->suaras as $tps)
                                    <div class="p-4 rounded-lg border border-gray-100 bg-gray-50 flex items-center justify-between">
                                        <div>
                                            <strong class="font-display text-primary">{{ $tps->tps_name }}</strong>
                                            <div class="text-xs text-muted mt-0.5 whitespace-nowrap">
                                                Sah: <span class="font-bold text-ink">{{ $tps->suara_sah }}</span> |
                                                Tdk Sah: <span class="font-bold text-ink">{{ $tps->suara_tidak_sah }}</span>
                                            </div>
                                        </div>
                                        <div class="text-right text-xs">
                                            <span class="block text-muted">{{ $tps->updated_at->format('H:i:s') }}</span>
                                            @if($pilkadesObj->isLocked())
                                                <span class="text-gray-500 font-bold bg-gray-200 px-1.5 py-0.5 rounded text-[10px]">🔒
                                                    TERKUNCI</span>
                                            @else
                                                <span class="text-green-600 font-bold bg-green-100 px-1.5 py-0.5 rounded text-[10px]">✅
                                                    TERSIMPAN</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Jika Selesai SK --}}
                    @if($pilkadesObj->sk_bupati_path)
                        <div class="p-6 border-t border-border bg-green-50 mt-auto sticky bottom-0">
                            <strong class="block text-green-800 text-sm mb-2">SK Bupati Tersedia</strong>
                            <a href="{{ asset('storage/' . $pilkadesObj->sk_bupati_path) }}" target="_blank"
                                class="block text-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded font-medium text-xs transition-colors">
                                Unduh SK Bupati Penetapan Pemenang
                            </a>
                        </div>
                    @endif
                </div>

            </div>
        @endif
    </div>
</x-app-layout>