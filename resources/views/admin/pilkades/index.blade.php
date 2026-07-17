<x-app-layout>
    @section('title', 'e-Pilkades Admin')

    <div class="bg-white rounded-card p-6 shadow-sm border border-border mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-display font-bold text-ink">Fasilitasi Pilkades (Demokrasi Desa)</h2>
                <p class="text-muted text-sm mt-1">Daftarkan fasilitasi pemilihan kepala desa serentak, pantau
                    rekapitulasi suara TPS, serta buat SK Bupati pelantikan kades terpilih.</p>
            </div>
            <div>
                <!-- Toggle Form Modal/Btn -->
                <button onclick="document.getElementById('createModal').classList.toggle('hidden')"
                    class="inline-flex items-center px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Daftarkan Pilkades Baru
                    </a>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div id="createModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-card max-w-md w-full p-6 shadow-xl border border-border relative">
            <button onclick="document.getElementById('createModal').classList.toggle('hidden')"
                class="absolute top-4 right-4 text-muted hover:text-ink">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
            <h3 class="text-lg font-bold text-ink font-display mb-4">Daftarkan Pilkades Baru</h3>
            <form action="{{ route('admin.pilkades.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="desa_id"
                        class="block text-xs font-semibold text-ink uppercase tracking-wider mb-1">Pilih Desa
                        Pelaksana</label>
                    <select name="desa_id" id="desa_id" required
                        class="w-full text-xs rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm">
                        @foreach (\App\Models\Desa::all() as $d)
                            <option value="{{ $d->id }}">{{ $d->nama_desa }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="tanggal_pemungutan"
                        class="block text-xs font-semibold text-ink uppercase tracking-wider mb-1">Tanggal Pemungutan
                        Suara</label>
                    <input type="date" name="tanggal_pemungutan" id="tanggal_pemungutan" required
                        class="w-full text-xs rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm p-1.5">
                </div>
                <div class="mb-4">
                    <label for="total_tps"
                        class="block text-xs font-semibold text-ink uppercase tracking-wider mb-1">Jumlah TPS
                        Ditetapkan</label>
                    <input type="number" name="total_tps" id="total_tps" required min="1"
                        class="w-full text-xs rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                        placeholder="Contoh: 5">
                </div>
                <div class="flex justify-end gap-3 mt-6 border-t border-border pt-4">
                    <button type="button" onclick="document.getElementById('createModal').classList.toggle('hidden')"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-ink text-xs font-bold rounded">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-primary text-white text-xs font-bold rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Desa
                            Pelaksana</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Tanggal
                            Pemungutan</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Total
                            TPS</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted tracking-wider uppercase">Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted tracking-wider uppercase">Kades
                            Terpilih</th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-muted tracking-wider uppercase">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse ($pilkades as $p)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-ink">
                                {{ $p->desa->nama_desa }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-ink font-medium">
                                {{ $p->tanggal_pemungutan ? $p->tanggal_pemungutan->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text- ink">
                                {{ $p->total_tps }} TPS
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($p->status === 'selesai')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                                @elseif($p->status === 'pemilihan')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Pemungutan</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Persiapan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text- ink font-semibold">
                                {{ $p->pemenang_nama ?? 'Belum Ditentukan' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('admin.pilkades.show', $p) }}"
                                    class="text-primary hover:text-primary-light bg-primary-soft px-3 py-1.5 rounded-md transition-colors">Tinjau
                                    Rekap</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-muted">Belum ada data fasilitasi
                                Pilkades serentak kabupaten.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pilkades->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $pilkades->links() }}
            </div>
        @endif
    </div>
</x-app-layout>