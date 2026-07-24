<x-app-layout>
    @section('title', 'Data BPD')

    <div class="bg-surface rounded-layout p-6 shadow-sm border border-border">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h3 class="text-lg font-bold text-ink">Data BPD</h3>
                <p class="text-sm text-text-muted mt-1">Data sentral bpd aktif seluruh desa di kabupaten.</p>
            </div>

            <form action="{{ route('admin.bpd.index') }}" method="GET" class="w-full md:w-72">
                <div class="relative mt-2 rounded-md shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, jabatan..."
                        class="block w-full rounded-md border-0 py-2 pl-10 pr-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6">
                </div>
            </form>
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
                    @forelse($bpd as $p)
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
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-ink">{{ $p->desa->nama_desa ?? '-' }}</div>
                                <div class="text-xs text-text-muted">{{ $p->desa->kecamatan->nama_kecamatan ?? '-' }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-text-muted">
                                @if(request('search'))
                                    Data bpd tidak ditemukan untuk pencarian '{{ request('search') }}'.
                                @else
                                    Belum ada data bpd desa di sistem.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $bpd->links() }}
        </div>
    </div>
</x-app-layout>