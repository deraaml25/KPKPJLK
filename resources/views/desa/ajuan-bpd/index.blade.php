<x-app-layout>
    @section('title', 'Ajuan BPD')

    <div class="flex items-center justify-between mb-6 mt-1">
        <div>
            <p class="text-muted text-sm mt-1">Kelola pengajuan pemberhentian dan peresmian BPD (PAW) Desa Anda.</p>
        </div>
        <a href="{{ route('desa.ajuan-bpd.create') }}"
            class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-btn hover:bg-primary-light hover:-translate-y-0.5 hover:shadow-lg transition-all active:scale-95 shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Ajuan Baru
        </a>
    </div>

    <!-- Tabel -->
    <div>
        <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden transition-shadow duration-300 hover:shadow-md">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-border">
                        <th class="py-3 px-4 text-xs font-bold text-muted uppercase tracking-wider">No Registrasi</th>
                        <th class="py-3 px-4 text-xs font-bold text-muted uppercase tracking-wider">Jenis Ajuan</th>
                        <th class="py-3 px-4 text-xs font-bold text-muted uppercase tracking-wider">Metode</th>
                        <th class="py-3 px-4 text-xs font-bold text-muted uppercase tracking-wider">Tgl Diajukan</th>
                        <th class="py-3 px-4 text-xs font-bold text-muted uppercase tracking-wider">Status</th>
                        <th class="py-3 px-4 text-xs font-bold text-muted uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($ajuans as $ajuan)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="py-3 px-4">
                                <span class="font-bold text-ink">{{ $ajuan->no_registrasi }}</span>
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
                                <a href="{{ route('desa.ajuan-bpd.show', $ajuan) }}" class="text-sm font-bold text-primary hover:underline transition-all inline-block group-hover:translate-x-1">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-muted">Belum ada ajuan BPD.</td>
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
