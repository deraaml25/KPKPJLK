<x-app-layout>
    @section('title', 'e-Izin Calon - Daftar Permohonan')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <h2 class="text-xl font-display font-bold text-ink">Verifikasi Izin Calon Kepala Desa</h2>
        <p class="text-muted text-sm mt-1">Seluruh permohonan izin pencalonan Kades dari desa-desa se-Kabupaten.
            Dinpermasdes berperan sebagai <em>gatekeeper</em> berdasarkan rekam Inspektorat.</p>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm mb-6 font-medium">
            {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50 text-red-800 rounded border border-red-200 text-sm mb-6 font-medium">
            {{ session('error') }}</div>
    @endif

    {{-- Stat Cards --}}
    @php
        $total = $izins->total();
        $menunggu = $izins->getCollection()->where('status', 'submitted')->count();
        $diizinkan = $izins->getCollection()->where('status', 'approved')->count();
        $ditolak = $izins->getCollection()->where('status', 'rejected')->count();
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-card p-4 shadow-sm border border-border text-center">
            <div class="text-2xl font-bold text-primary font-display">{{ $total }}</div>
            <div class="text-xs text-muted mt-1">Total Permohonan</div>
        </div>
        <div class="bg-white rounded-card p-4 shadow-sm border border-border text-center">
            <div class="text-2xl font-bold text-blue-600 font-display">{{ $menunggu }}</div>
            <div class="text-xs text-muted mt-1">Menunggu Validasi</div>
        </div>
        <div class="bg-white rounded-card p-4 shadow-sm border border-border text-center">
            <div class="text-2xl font-bold text-green-600 font-display">{{ $diizinkan }}</div>
            <div class="text-xs text-muted mt-1">Izin Diterbitkan</div>
        </div>
        <div class="bg-white rounded-card p-4 shadow-sm border border-border text-center">
            <div class="text-2xl font-bold text-red-600 font-display">{{ $ditolak }}</div>
            <div class="text-xs text-muted mt-1">Ditolak</div>
        </div>
    </div>

    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Desa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Nama
                            Calon / Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Jabatan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Temuan
                            Inspektorat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-muted uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse ($izins as $izin)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-ink">
                                {{ $izin->desa->nama_desa }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-ink">{{ $izin->nama_calon }}</div>
                                <div class="text-xs text-muted mt-0.5">{{ $izin->label_jenis_calon }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-ink">{{ $izin->jabatan_sekarang }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($izin->has_temuan_inspektorat)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-800">Ada
                                        Temuan ⚠️</span>
                                @elseif($izin->status !== 'submitted')
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800">Bebas
                                        Temuan ✅</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-600">Belum
                                        Dicek</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($izin->status === 'approved')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Izin
                                        Diterbitkan</span>
                                @elseif($izin->status === 'rejected')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Proses
                                        Validasi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('admin.izincalon.show', $izin) }}"
                                    class="text-primary hover:text-primary-light bg-primary-soft px-3 py-1.5 rounded-md font-medium transition-colors">Tinjau</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-muted">Belum ada permohonan izin
                                calon yang masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($izins->hasPages())
            <div class="px-6 py-4 border-t border-border">{{ $izins->links() }}</div>
        @endif
    </div>
</x-app-layout>