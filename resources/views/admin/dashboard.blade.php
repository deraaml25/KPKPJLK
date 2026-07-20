<x-app-layout>
    @section('title', 'Dashboard Super Admin')

    {{-- PERINGATAN DINI: Pj Kades hampir/sudah berakhir --}}
    @if(isset($pjKadesAlert) && $pjKadesAlert->count() > 0)
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-r-card shadow-sm mb-6">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <div>
                    <strong class="font-bold block text-sm">⚠️ PERINGATAN — Krisis Kepemimpinan Desa</strong>
                    <p class="text-xs mt-0.5">Terdapat <strong>{{ $pjKadesAlert->count() }}</strong> Pj Kades yang masa
                        jabatannya akan/telah berakhir:</p>
                    <ul class="list-disc ml-4 mt-1 text-xs space-y-0.5">
                        @foreach($pjKadesAlert as $alert)
                            <li>
                                <strong>{{ $alert->nama_pns }}</strong> ({{ $alert->desa->nama_desa }})
                                — @if($alert->sudah_berakhir) <span class="text-red-700 font-bold">SUDAH BERAKHIR</span> @else
                                Sisa <strong>{{ $alert->sisa_hari }} hari</strong> @endif
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('admin.pjkades.index') }}"
                        class="text-xs font-bold mt-2 inline-block hover:underline">Buka Modul e-Pj Kades →</a>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        @php($stats = [
            ['label' => 'Total Ajuan Berjalan', 'value' => '12', 'tone' => 'primary', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />'],
            ['label' => 'Perlu Perbaikan', 'value' => '3', 'tone' => 'danger', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />'],
            ['label' => 'Mendekati SLA', 'value' => '2', 'tone' => 'warning', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />'],
            ['label' => 'Lewat SLA', 'value' => '0', 'tone' => 'danger', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'],
        ])
        @foreach($stats as $stat)
            <div class="rounded-3xl border border-slate-200 bg-white/90 p-5 shadow-[0_16px_50px_rgba(15,23,42,0.06)] backdrop-blur">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">{{ $stat['label'] }}</p>
                        <h3 class="mt-2 text-3xl font-semibold tracking-tight text-slate-800">{{ $stat['value'] }}</h3>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-700">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            {!! $stat['icon'] !!}
                        </svg>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1.4fr_0.8fr] gap-8">
        <div class="rounded-3xl border border-slate-200 bg-white/90 p-0 shadow-[0_16px_50px_rgba(15,23,42,0.06)] backdrop-blur overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.3em] text-slate-400">Prioritas Hari Ini</p>
                    <h3 class="text-lg font-semibold text-slate-800">Ajuan Prioritas</h3>
                </div>
                <a href="#" class="text-sm font-medium text-[#930500] hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-slate-200">
                <div class="flex items-center justify-between p-6 transition-colors hover:bg-slate-50">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#95BBEA]/25 text-[#930500] font-semibold">K</div>
                        <div>
                            <h4 class="font-semibold text-slate-800">Desa Karangendep</h4>
                            <div class="mt-1 flex items-center gap-2">
                                <span class="rounded-full bg-[#95BBEA]/20 px-2.5 py-1 text-xs font-medium text-[#930500]">Pengangkatan</span>
                                <span class="font-mono text-xs text-slate-500">PGKT/2026/07/0032</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-slate-700">Tahap 4: Verifikasi &amp; Validasi Kabid PDPD</p>
                        <p class="mt-1 text-xs text-amber-600">Sisa 3 Hari Kerja</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-6 transition-colors hover:bg-slate-50">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-red-50 text-red-600 font-semibold">S</div>
                        <div>
                            <h4 class="font-semibold text-slate-800">Desa Sumbang</h4>
                            <div class="mt-1 flex items-center gap-2">
                                <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-600">Pemberhentian</span>
                                <span class="font-mono text-xs text-slate-500">PBRH/2026/07/0014</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-slate-700">Tahap 2: Verifikasi &amp; Validasi</p>
                        <p class="mt-1 text-xs text-emerald-600">Sisa 18 Hari Kerja</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-[0_16px_50px_rgba(15,23,42,0.06)] backdrop-blur">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.3em] text-slate-400">Distribusi</p>
                    <h3 class="text-lg font-semibold text-slate-800">Jenis Layanan</h3>
                </div>
            </div>
            <div class="relative h-48 w-full">
                <canvas id="jenisLayananChart"></canvas>
            </div>
            <div class="mt-6 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-[#930500]"></span>
                        <span class="text-slate-500">Pengangkatan</span>
                    </div>
                    <span class="font-semibold text-slate-700">65%</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-red-500"></span>
                        <span class="text-slate-500">Pemberhentian</span>
                    </div>
                    <span class="font-semibold text-slate-700">25%</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-[#95BBEA]"></span>
                        <span class="text-slate-500">Rotasi</span>
                    </div>
                    <span class="font-semibold text-slate-700">10%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Ajuan Prioritas List -->
        <div class="lg:col-span-2">
            <div class="bg-surface rounded-card shadow-floating border border-border overflow-hidden">
                <div class="px-6 py-5 border-b border-border flex justify-between items-center">
                    <h3 class="text-lg font-display font-semibold">Ajuan Prioritas</h3>
                    <a href="#" class="text-sm text-primary hover:text-primary-light font-medium">Lihat Semua</a>
                </div>
                <div class="divide-y divide-border">
                    <!-- Placeholder Item -->
                    <div class="p-6 hover:bg-gray-50 transition-colors flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 rounded-full bg-primary-soft flex items-center justify-center text-primary font-bold">
                                K
                            </div>
                            <div>
                                <h4 class="font-semibold text-ink">Desa Karangendep</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary-soft text-primary">
                                        Pengangkatan
                                    </span>
                                    <span class="text-xs text-muted font-mono">PGKT/2026/07/0032</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-ink">Tahap 4: Verifikasi & Validasi Kabid PDPD</p>
                            <p class="text-xs text-warning mt-1">Sisa 3 Hari Kerja</p>
                        </div>
                    </div>

                    <div class="p-6 hover:bg-gray-50 transition-colors flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-danger font-bold">
                                S
                            </div>
                            <div>
                                <h4 class="font-semibold text-ink">Desa Sumbang</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-danger">
                                        Pemberhentian
                                    </span>
                                    <span class="text-xs text-muted font-mono">PBRH/2026/07/0014</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-ink">Tahap 2: Verifikasi & Validasi</p>
                            <p class="text-xs text-success mt-1">Sisa 18 Hari Kerja</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Widget Statistik -->
        <div>
            <div class="bg-surface rounded-card shadow-floating border border-border p-6 h-full">
                <h3 class="text-lg font-display font-semibold mb-6">Statistik Jenis Layanan</h3>
                <div class="relative h-48 w-full flex items-center justify-center">
                    <canvas id="jenisLayananChart"></canvas>
                </div>
                <div class="mt-6 space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-primary"></span>
                            <span class="text-muted">Pengangkatan</span>
                        </div>
                        <span class="font-semibold">65%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-danger"></span>
                            <span class="text-muted">Pemberhentian</span>
                        </div>
                        <span class="font-semibold">25%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full" style="background-color: #6F7FD8"></span>
                            <span class="text-muted">Rotasi</span>
                        </div>
                        <span class="font-semibold">10%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script for Chart -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (document.getElementById('jenisLayananChart')) {
                const ctx = document.getElementById('jenisLayananChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pengangkatan', 'Pemberhentian', 'Rotasi'],
                        datasets: [{
                            data: [65, 25, 10],
                            backgroundColor: ['#4B3F9E', '#D9534F', '#6F7FD8'],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>