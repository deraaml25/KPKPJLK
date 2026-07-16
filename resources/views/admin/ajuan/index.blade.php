<x-app-layout>
    @section('title', 'Daftar Ajuan Masuk')

    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-muted text-sm mt-1">Semua ajuan yang telah disubmit oleh operator desa.</p>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.ajuan.index') }}" class="bg-surface rounded-card border border-border p-5 mb-6 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-muted mb-1">Jenis Layanan</label>
                <select name="jenis_layanan_id" class="w-full rounded-btn border-border text-sm text-ink focus:ring-primary focus:border-primary">
                    <option value="">Semua Jenis</option>
                    @foreach(\App\Models\JenisLayanan::all() as $jl)
                        <option value="{{ $jl->id }}" @selected(request('jenis_layanan_id') == $jl->id)>{{ $jl->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-muted mb-1">Status</label>
                <select name="status" class="w-full rounded-btn border-border text-sm text-ink focus:ring-primary focus:border-primary">
                    <option value="">Semua Status</option>
                    @foreach(['submitted' => 'Diajukan', 'diverifikasi' => 'Diverifikasi', 'direvisi' => 'Perlu Revisi', 'diproses' => 'Diproses', 'selesai' => 'Selesai'] as $val => $label)
                        <option value="{{ $val }}" @selected(request('status') == $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2 flex items-end gap-2">
                <button type="submit" id="btn-filter" class="px-5 py-2.5 bg-primary text-white text-sm font-medium rounded-btn hover:bg-primary-light transition-colors">Filter</button>
                <a href="{{ route('admin.ajuan.index') }}" class="px-5 py-2.5 bg-gray-100 text-ink text-sm font-medium rounded-btn hover:bg-gray-200 transition-colors">Reset</a>
            </div>
        </div>
    </form>

    <!-- Ajuan Table -->
    <div class="bg-surface rounded-card border border-border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">No. Registrasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Desa / Kecamatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Jenis Layanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Perangkat Desa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">SLA</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-muted uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($ajuans as $ajuan)
                        @php
                            $slaHariBerjalan = $ajuan->tgl_diajukan ? now()->diffInDaysFiltered(fn($d) => !$d->isWeekend(), $ajuan->tgl_diajukan) : 0;
                            $slaWarna = $slaHariBerjalan >= 20 ? 'danger' : ($slaHariBerjalan >= 15 ? 'warning' : 'success');
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm font-semibold text-ink">{{ $ajuan->no_registrasi }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-ink">{{ $ajuan->desa->nama_desa }}</div>
                                <div class="text-xs text-muted">Kec. {{ $ajuan->desa->kecamatan->nama_kecamatan }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $badgeColor = match($ajuan->jenisLayanan->nama) {
                                        'Pengangkatan' => 'bg-primary-soft text-primary',
                                        'Pemberhentian' => 'bg-red-100 text-danger',
                                        'Rotasi' => 'bg-indigo-100 text-indigo-600',
                                        default => 'bg-gray-100 text-muted'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                                    {{ $ajuan->jenisLayanan->nama }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-ink">{{ $ajuan->perangkatDesa->nama }}</div>
                                <div class="text-xs text-muted">{{ $ajuan->perangkatDesa->jabatan }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusLabel = ['submitted' => 'Diajukan', 'direvisi' => 'Perlu Revisi', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'draft' => 'Draft'];
                                    $statusWarna = ['submitted' => 'bg-blue-100 text-blue-700', 'direvisi' => 'bg-red-100 text-danger', 'diproses' => 'bg-yellow-100 text-warning', 'selesai' => 'bg-green-100 text-success', 'draft' => 'bg-gray-100 text-muted'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusWarna[$ajuan->status] ?? 'bg-gray-100 text-muted' }}">
                                    {{ $statusLabel[$ajuan->status] ?? $ajuan->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-{{ $slaWarna === 'success' ? 'green' : ($slaWarna === 'warning' ? 'yellow' : 'red') }}-100 text-{{ $slaWarna }}">
                                    {{ $slaHariBerjalan }}/20 HK
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('admin.ajuan.show', $ajuan) }}" class="inline-flex items-center px-4 py-2 bg-primary-soft text-primary text-sm font-medium rounded-btn hover:bg-primary hover:text-white transition-all">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center text-muted">
                                    <svg class="w-12 h-12 mb-3 text-border" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <p class="font-medium">Belum ada ajuan masuk</p>
                                    <p class="text-sm mt-1">Ajuan dari desa akan muncul di sini setelah disubmit.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($ajuans->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $ajuans->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
