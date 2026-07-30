<x-app-layout>
    @section('title', 'Verifikasi Ajuan BPD (PAW)')

    <!-- Tabel -->
    <div>
        <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-border">
                        <th class="py-3 px-4 text-xs font-bold text-muted uppercase tracking-wider">No Registrasi</th>
                        <th class="py-3 px-4 text-xs font-bold text-muted uppercase tracking-wider">Desa</th>
                        <th class="py-3 px-4 text-xs font-bold text-muted uppercase tracking-wider">Jenis Ajuan</th>
                        <th class="py-3 px-4 text-xs font-bold text-muted uppercase tracking-wider">Metode</th>
                        <th class="py-3 px-4 text-xs font-bold text-muted uppercase tracking-wider">Tgl Diajukan</th>
                        <th class="py-3 px-4 text-xs font-bold text-muted uppercase tracking-wider">Status</th>
                        <th class="py-3 px-4 text-xs font-bold text-muted uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($ajuans as $ajuan)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4">
                                <span class="font-bold text-ink">{{ $ajuan->no_registrasi }}</span>
                            </td>
                            <td class="py-3 px-4">
                                {{ $ajuan->desa->nama_desa ?? '-' }}
                            </td>
                            <td class="py-3 px-4 text-sm">
                                <span class="uppercase font-bold">{{ str_replace('_', ' ', $ajuan->jenis_ajuan) }}</span>
                            </td>
                            <td class="py-3 px-4 text-sm">
                                {{ ucfirst($ajuan->metode) }}
                            </td>
                            <td class="py-3 px-4 text-sm">
                                {{ $ajuan->tgl_diajukan ? \Carbon\Carbon::parse($ajuan->tgl_diajukan)->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 bg-slate-100 text-slate-700 text-xs font-bold rounded">
                                    {{ strtoupper($ajuan->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.ajuan-bpd.show', $ajuan) }}" class="text-sm font-bold text-primary hover:underline">Verifikasi</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-muted">Belum ada ajuan BPD.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($ajuans->hasPages())
            <div class="mt-6">{{ $ajuans->links() }}</div>
        @endif
    </div>
</x-app-layout>
