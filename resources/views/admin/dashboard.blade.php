<x-app-layout>
    @section('title', 'Dashboard')

    <!-- Welcome Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 font-display">Halo, Admin Dinpermasdes</h1>
            <p class="text-slate-500 mt-0.5 text-sm">Selamat datang kembali di pusat kendali administrasi desa Anda.</p>
        </div>
        <button class="bg-[#111827] text-white px-4 py-2 rounded-lg flex items-center gap-2 font-semibold hover:bg-slate-800 transition-colors text-sm">
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            Buat Dokumen Baru
        </button>
    </div>

    <!-- 4 Stat Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl border-2 border-slate-900 p-5 shadow-sm relative overflow-hidden">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-[#111827]">
                    <span class="material-symbols-outlined text-[20px]">description</span>
                </div>
                <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded-md">+12%</span>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-slate-900 mb-0.5">Draft Regulasi</p>
                <h3 class="text-3xl font-bold text-slate-900 font-display">245</h3>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-2xl border-2 border-slate-300 hover:border-slate-400 p-5 shadow-sm transition-colors relative overflow-hidden">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400">
                    <span class="material-symbols-outlined text-[20px]">group</span>
                </div>
                <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-1 rounded-md">Aktif</span>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-slate-900 mb-0.5">Perangkat Desa</p>
                <h3 class="text-3xl font-bold text-slate-900 font-display">42</h3>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-2xl border-2 border-slate-900 p-5 shadow-sm relative overflow-hidden">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400">
                    <span class="material-symbols-outlined text-[20px]">approval</span>
                </div>
                <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-1 rounded-md">8 Pending</span>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-slate-900 mb-0.5">e-Rekomendasi</p>
                <h3 class="text-3xl font-bold text-slate-900 font-display">1,084</h3>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white rounded-2xl border-2 border-slate-300 hover:border-slate-400 p-5 shadow-sm transition-colors relative overflow-hidden">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400">
                    <span class="material-symbols-outlined text-[20px]">archive</span>
                </div>
                <span class="bg-slate-200 text-slate-700 text-[10px] font-bold px-2 py-1 rounded-md">Total</span>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-slate-900 mb-0.5">Arsip Terdata</p>
                <h3 class="text-3xl font-bold text-slate-900 font-display">5,201</h3>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-[1.5fr_1fr] gap-8 mb-8">
        <!-- Aktivitas Terkini -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-900">Aktivitas Terkini</h3>
                <a href="#" class="text-sm font-semibold text-slate-500 hover:text-slate-700">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 bg-slate-50">Dokumen</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 bg-slate-50">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 bg-slate-50">Admin</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 bg-slate-50">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-slate-400">description</span>
                                    <span class="text-sm font-semibold text-slate-700">Draft SK Kades No.<br>24/2023</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Review</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">Siska<br>Amelia</td>
                            <td class="px-6 py-4 text-sm text-slate-600">Hari ini,<br>10:24</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-slate-400">approval</span>
                                    <span class="text-sm font-semibold text-slate-700">Rekomendasi Penataan<br>Lahan</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Selesai</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">Budi<br>Santoso</td>
                            <td class="px-6 py-4 text-sm text-slate-600">Kemarin,<br>14:55</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-slate-400">history_edu</span>
                                    <span class="text-sm font-semibold text-slate-700">Pembinaan<br>Kesejahteraan Desa</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-slate-200 text-slate-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Draft</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">Admin<br>Unsoed</td>
                            <td class="px-6 py-4 text-sm text-slate-600">12 Okt 2023</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Column Widgets -->
        <div class="flex flex-col gap-6">
            <!-- Widget 1: Informasi Wilayah -->
            <div class="rounded-2xl overflow-hidden relative shadow-sm" style="background-image: url('https://images.unsplash.com/photo-1542224566-6e85f2e6772f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'); background-size: cover; background-position: center;">
                <div class="absolute inset-0 bg-black/40"></div>
                <div class="relative p-6 h-full flex flex-col justify-end min-h-[180px]">
                    <h3 class="text-xl font-bold text-white mb-2">Informasi Wilayah</h3>
                    <p class="text-white/80 text-sm mb-4">Data spasial dan demografi desa terbaru.</p>
                    <a href="https://maps.app.goo.gl/jFDWxg1pKXNHyZz78" target="_blank" class="bg-white text-slate-900 text-sm font-bold py-2.5 rounded-lg w-full hover:bg-slate-50 transition-colors text-center block">
                        Buka Peta Desa
                    </a>
                </div>
            </div>

            <!-- Widget 2: Sistem Update -->
            <div class="bg-[#7895CB] rounded-2xl p-6 text-white shadow-sm flex-1 flex flex-col justify-center">
                <h3 class="text-lg font-bold mb-2 text-slate-900">Sistem Update Berkala</h3>
                <p class="text-slate-800 text-sm opacity-90 leading-relaxed">
                    Pemeliharaan server akan dilakukan pada 15 Oktober pukul 23:00 WIB.
                </p>
            </div>
        </div>
    </div>

    <!-- Bottom Circular Charts -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Chart 1 -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 text-center shadow-sm">
            <div class="w-32 h-32 mx-auto relative flex items-center justify-center">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                    <path class="text-slate-100" stroke-width="4" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path class="text-[#111827]" stroke-width="4" stroke-dasharray="85, 100" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-2xl font-bold font-display text-slate-900">85%</span>
                </div>
            </div>
            <h4 class="mt-6 font-bold text-slate-900">Efisiensi Layanan</h4>
            <p class="text-xs text-slate-500 mt-1">Peningkatan 5% dari bulan lalu</p>
        </div>
        
        <!-- Chart 2 -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 text-center shadow-sm">
            <div class="w-32 h-32 mx-auto relative flex items-center justify-center">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                    <path class="text-slate-100" stroke-width="4" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path class="text-[#6fa7e9]" stroke-width="4" stroke-dasharray="100, 100" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-2xl font-bold font-display text-slate-900">128</span>
                </div>
            </div>
            <h4 class="mt-6 font-bold text-slate-900">Pemohon Aktif</h4>
            <p class="text-xs text-slate-500 mt-1">Total warga yang mengakses layanan</p>
        </div>

        <!-- Chart 3 -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 text-center shadow-sm">
            <div class="w-32 h-32 mx-auto relative flex items-center justify-center">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                    <path class="text-slate-100" stroke-width="4" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path class="text-slate-400" stroke-width="4" stroke-dasharray="92, 100" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-2xl font-bold font-display text-slate-900">92%</span>
                </div>
            </div>
            <h4 class="mt-6 font-bold text-slate-900">Akurasi Data</h4>
            <p class="text-xs text-slate-500 mt-1">Verifikasi kependudukan sukses</p>
        </div>
    </div>
</x-app-layout>