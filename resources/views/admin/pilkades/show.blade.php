<x-app-layout>
    @section('title', 'Tinjau Live Count Pilkades')

    {{-- Header --}}
    <div class="max-w-6xl mx-auto mb-6">
        <a href="{{ route('admin.pilkades.index') }}"
            class="text-sm font-medium text-primary hover:underline flex items-center gap-1 mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Daftar Event
        </a>
        <div class="bg-indigo-900 rounded-card p-8 shadow-floating text-white relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-3xl font-display font-bold">PIlKADES SERENTAK —
                    {{ strtoupper($pilkades->desa->nama_desa) }}</h2>
                <div class="flex items-center gap-4 mt-3 text-sm font-medium opacity-90">
                    <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg> {{ $pilkades->tanggal_pemungutan->format('d F Y') }}</span>
                    <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg> DPT: {{ number_format($pilkades->total_dpt) }} Pemilih</span>
                    <span class="px-2 py-0.5 bg-white/20 rounded">{{ $pilkades->tps_lapor }} /
                        {{ $pilkades->total_tps }} TPS Masuk</span>
                </div>
            </div>
            <!-- Decorative circle -->
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
        </div>
    </div>

    @if(session('success'))
        <div
            class="max-w-6xl mx-auto p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm mb-6 font-medium">
            {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="max-w-6xl mx-auto p-4 bg-red-50 text-red-800 rounded border border-red-200 text-sm mb-6 font-medium">
            {{ session('error') }}</div>
    @endif

    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kiri: Live Quick Count Result --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-card shadow-sm border border-border p-6">
                <div class="flex justify-between items-center mb-6 pb-2 border-b border-border">
                    <h3 class="text-lg font-display font-bold text-ink flex items-center gap-2">
                        @if(!$pilkades->isLocked()) <span class="relative flex h-3 w-3"><span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span><span
                        class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span></span> @endif
                        Live Quick Count Result
                    </h3>
                    @if($pilkades->total_suara_sah > 0)
                        <span class="text-xs font-medium text-muted bg-gray-100 px-2 py-1 rounded">Total Suara Masuk:
                            {{ number_format($pilkades->total_pemilih_hadir) }}</span>
                    @endif
                </div>

                @if($pilkades->total_suara_sah === 0)
                    <div class="text-center py-12 text-muted">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        <p class="text-lg font-bold text-ink">Menunggu Data Suara</p>
                        <p class="text-sm">TPS desa belum mengirimkan satupun perolehan suara.</p>
                    </div>
                @else
                    <div class="space-y-5">
                        @php
                            $total = $pilkades->total_suara_sah;
                            $calons = [
                                ['nama' => $pilkades->calon_1_nama, 'suara' => $pilkades->total_suara_calon_1, 'color' => 'bg-indigo-500'],
                                ['nama' => $pilkades->calon_2_nama, 'suara' => $pilkades->total_suara_calon_2, 'color' => 'bg-emerald-500'],
                                ['nama' => $pilkades->calon_3_nama, 'suara' => $pilkades->total_suara_calon_3, 'color' => 'bg-amber-500'],
                            ];
                            // Filter valid calons
                            $calons = array_filter($calons, fn($c) => !empty($c['nama']));
                            usort($calons, fn($a, $b) => $b['suara'] <=> $a['suara']);
                        @endphp

                        @foreach($calons as $index => $c)
                            @php $pct = $total > 0 ? round(($c['suara'] / $total) * 100, 1) : 0; @endphp
                            <div
                                class="relative p-4 rounded-lg bg-gray-50 border border-gray-100 {{ $index === 0 ? 'ring-2 ring-primary bg-primary-soft/30' : '' }}">
                                @if($index === 0)
                                    <div class="absolute -top-3 -right-3 text-2xl" title="Perolehan Tertinggi">👑</div>
                                @endif
                                <div class="flex justify-between items-end mb-2">
                                    <div>
                                        <span
                                            class="text-xs font-bold text-muted uppercase tracking-wider block mb-0.5">Kandidat
                                            {{ $index + 1 }}</span>
                                        <h4 class="text-lg font-display font-bold text-ink">{{ $c['nama'] }}</h4>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-black {{ $index === 0 ? 'text-primary' : 'text-ink' }}">
                                            {{ number_format($c['suara']) }}</div>
                                        <div class="text-xs font-bold text-muted">{{ $pct }}%</div>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="{{ $c['color'] }} h-3 rounded-full transition-all duration-1000"
                                        style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Audit Laporan Masuk --}}
            <div class="bg-white rounded-card shadow-sm border border-border p-6">
                <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Log Audit Suara
                    Masuk TPS</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-border text-xs">
                        <thead class="bg-gray-50 text-muted uppercase">
                            <tr>
                                <th class="px-3 py-2 text-left">TPS</th>
                                <th class="px-3 py-2 text-left">Suara Sah</th>
                                <th class="px-3 py-2 text-left">Tidak Sah</th>
                                <th class="px-3 py-2 text-left">Diinput Oleh</th>
                                <th class="px-3 py-2 text-right">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach($pilkades->suaras as $s)
                                <tr>
                                    <td class="px-3 py-2 font-bold text-ink">{{ $s->tps_name }}</td>
                                    <td class="px-3 py-2 text-green-700 font-medium">{{ $s->suara_sah }}</td>
                                    <td class="px-3 py-2 text-red-700 font-medium">{{ $s->suara_tidak_sah }}</td>
                                    <td class="px-3 py-2 text-muted truncate max-w-[120px]"
                                        title="IP: {{ $s->ip_address }}">{{ $s->inputter->name ?? '-' }}</td>
                                    <td class="px-3 py-2 text-right text-muted">{{ $s->updated_at->format('H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($pilkades->suaras->isEmpty())
                        <p class="text-center text-muted py-4 text-xs">Belum ada log masuk.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kanan: Form Penetapan Pengesahan --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Statistik Error/Partisipasi --}}
            <div class="bg-white rounded-card shadow-sm border border-border p-6 flex gap-4 text-sm">
                <div class="flex-1 text-center border-r border-border">
                    <span class="block text-muted text-xs mb-1">Partisipasi</span>
                    <strong class="text-lg font-bold text-ink">
                        {{ $pilkades->total_dpt > 0 ? round(($pilkades->total_pemilih_hadir / $pilkades->total_dpt) * 100, 1) : 0 }}%
                    </strong>
                </div>
                <div class="flex-1 text-center border-r border-border">
                    <span class="block text-muted text-xs mb-1">Total Sah</span>
                    <strong
                        class="text-lg font-bold text-green-600">{{ number_format($pilkades->total_suara_sah) }}</strong>
                </div>
                <div class="flex-1 text-center">
                    <span class="block text-muted text-xs mb-1">Tdk Sah</span>
                    <strong
                        class="text-lg font-bold text-red-600">{{ number_format($pilkades->total_suara_tidak_sah ?? ($pilkades->suaras->sum('suara_tidak_sah'))) }}</strong>
                </div>
            </div>

            <div class="bg-white rounded-card shadow-sm border border-border p-6">
                <h3 class="text-md font-display font-bold text-ink mb-4 pb-2 border-b border-border">Penerbitan SK
                    Pemenang Pilkades</h3>

                @if($pilkades->isLocked())
                    <div class="p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm">
                        <strong class="font-bold flex items-center gap-2 mb-1"><svg class="w-5 h-5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg> Data Telah Terkunci Formal</strong>
                        <p class="text-xs mb-3">SK Bupati telah diterbitkan untuk
                            <strong>{{ $pilkades->pemenang_nama }}</strong>. Data input desa sudah dibekukan.</p>
                        @if($pilkades->sk_bupati_path)
                            <a href="{{ asset('storage/' . $pilkades->sk_bupati_path) }}" target="_blank"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-btn transition-colors text-sm font-medium">
                                Unduh SK Bukti Keputusan
                            </a>
                        @endif
                        <p class="text-[10px] text-green-700 mt-2 text-center">Disahkan oleh:
                            {{ $pilkades->pengesah->name ?? 'Admin' }} pada
                            {{ $pilkades->disahkan_at->format('d/m/Y H:i') }}</p>
                    </div>
                @else
                    <div class="p-3 bg-amber-50 border border-amber-200 text-amber-800 text-xs rounded font-medium mb-4">
                        Peringatan: Verifikasi Form C1 / lampiran BA fisik dari TPS sebelum menerbitkan SK. Penerbitan SK
                        akan mengunci permanen (seal) input data suara dari operator desa.
                    </div>

                    <form action="{{ route('admin.pilkades.generate-sk', $pilkades) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-xs font-medium text-ink mb-1">Cetak SK Untuk (Otomatis By
                                System):</label>
                            <input type="text" readonly disabled value="{{ $pilkades->pemenang ?? 'Belum ada data masuk' }}"
                                class="w-full text-sm font-bold bg-gray-100 border-gray-200 text-ink rounded px-3 py-2 shadow-inner">
                        </div>

                        <div class="mb-4 p-4 bg-gray-50 border border-border rounded">
                            <label for="sk_bupati" class="block text-xs font-bold text-ink mb-2">Upload Salinan SK Ber-TTE
                                (.pdf) <span class="text-red-500">*</span></label>
                            <input type="file" name="sk_bupati" id="sk_bupati" accept=".pdf" required
                                class="w-full text-xs rounded border border-border bg-white text-ink shadow-sm p-1">
                        </div>

                        <button type="submit"
                            onclick="return confirm('SAHKAN DAN KUNCI PERMANEN?\nDengan ini Anda menerbitkan SK Pemenang dan membekukan data TPS.\nTindakan ini tidak bisa dibatalkan dari aplikasi.')"
                            class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors text-sm shadow-sm"
                            {{ !$pilkades->pemenang ? 'disabled' : '' }}>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                            Kunci Data & Terbitkan SK
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>