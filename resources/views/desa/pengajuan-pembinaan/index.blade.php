<x-app-layout>
    @section('title', 'Informasi & Pengajuan Pembinaan')

    <!-- Tabs Nav -->
    <div class="border-b border-slate-200 mb-6">
        <nav class="flex space-x-8" aria-label="Tabs">
            <a href="{{ route('desa.bimtek-informasi.index') }}"
               class="border-b-2 py-4 px-1 text-sm font-semibold {{ request()->routeIs('desa.bimtek-informasi.*') ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                📰 Berita & Informasi Pembinaan
            </a>
            <a href="{{ route('desa.pengajuan-pembinaan.index') }}"
               class="border-b-2 py-4 px-1 text-sm font-semibold {{ request()->routeIs('desa.pengajuan-pembinaan.*') ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                📨 Pengajuan Pembinaan Desa
            </a>
        </nav>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Daftar Pengajuan Pembinaan</h3>
                <p class="text-sm text-slate-500 mt-1">Kelola pengajuan permohonan narasumber atau pembinaan untuk desa Anda.</p>
            </div>
            <a href="{{ route('desa.pengajuan-pembinaan.create') }}" class="bg-[#0A1A3A] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-900 transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Buat Pengajuan Baru
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tgl Pengajuan</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Judul Kegiatan</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $pengajuan)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                        <td class="py-3 px-4 text-sm text-slate-600">
                            {{ $pengajuan->created_at->format('d M Y') }}
                        </td>
                        <td class="py-3 px-4">
                            <p class="text-sm font-bold text-slate-900">{{ $pengajuan->judul_kegiatan }}</p>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ ucfirst($pengajuan->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('desa.pengajuan-pembinaan.show', $pengajuan->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-slate-500">
                            Belum ada pengajuan pembinaan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
