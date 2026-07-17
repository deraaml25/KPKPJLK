<x-app-layout>
    @section('title', 'Data Perangkat Desa')

    <div class="bg-surface rounded-layout p-6 shadow-sm border border-border">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-ink">Direktori Perangkat & Staf Desa</h3>
                <p class="text-sm text-text-muted mt-1">Data master perangkat aktif di
                    {{ auth()->user()->desa->nama_desa ?? 'desa ini' }} berdasarkan rekaman database sistem.
                </p>
            </div>
            <div>
                <span
                    class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                    Data Tersinkronisasi
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink">
                <thead class="bg-surface-alt text-text-muted uppercase text-xs font-semibold">
                    <tr>
                        <th scope="col" class="px-6 py-3 rounded-tl-lg">Nama Lengkap</th>
                        <th scope="col" class="px-6 py-3">Jabatan</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3 rounded-tr-lg">Desa / Instansi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($perangkats as $p)
                        <tr class="hover:bg-primary-soft/10 transition-colors">
                            <td class="px-6 py-4 font-medium text-ink">
                                {{ $p->nama }}
                            </td>
                            <td class="px-6 py-4 text-text-muted">
                                {{ $p->jabatan }}
                            </td>
                            <td class="px-6 py-4">
                                @if($p->status_aktif)
                                    <span
                                        class="inline-flex items-center gap-1.5 py-1 px-2 rounded-md text-xs font-medium bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 py-1 px-2 rounded-md text-xs font-medium bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/10">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                        Non Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-text-muted">
                                {{ $p->desa->nama_desa ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-text-muted">
                                Belum ada data perangkat desa yang diimpor.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $perangkats->links() }}
        </div>
    </div>
</x-app-layout>