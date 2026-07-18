<x-app-layout>
    @section('title', 'e-Pilkades - Setup Master Event')

    <div
        class="bg-white rounded-card p-6 shadow-sm border border-border mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-xl font-display font-bold text-ink">e-Pilkades (Fasilitasi Pemilihan Kades)</h2>
            <p class="text-muted text-sm mt-1">Setup master data DPT dan fasilitasi hukum untuk pemilihan Kepala Desa
                serentak.</p>
        </div>
        <button onclick="document.getElementById('modal-create').classList.remove('hidden')"
            class="inline-flex items-center px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors text-sm shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Buat Event Pilkades
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-800 rounded border border-green-200 text-sm mb-6 font-medium">
            {{ session('success') }}</div>
    @endif

    {{-- Modal Create --}}
    <div id="modal-create"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-card w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-floating">
            <div class="p-6 border-b border-border flex justify-between items-center sticky top-0 bg-white">
                <h3 class="text-lg font-bold text-ink">Setup Penetapan Pilkades Baru</h3>
                <button onclick="document.getElementById('modal-create').classList.add('hidden')"
                    class="text-muted hover:text-ink">&times;</button>
            </div>
            <form action="{{ route('admin.pilkades.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-medium text-ink mb-1">Desa Penyelenggara</label>
                        <select name="desa_id" required class="w-full text-sm rounded-md border-border">
                            <option value="">— Pilih Desa —</option>
                            @foreach($desas as $desa)
                                <option value="{{ $desa->id }}">{{ $desa->nama_desa }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-medium text-ink mb-1">Tanggal Pemungutan Suara</label>
                        <input type="date" name="tanggal_pemungutan" required
                            class="w-full text-sm rounded-md border-border">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink mb-1">Jumlah Pemilih Tetap (DPT)</label>
                        <input type="number" name="total_dpt" required placeholder="0"
                            class="w-full text-sm rounded-md border-border">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink mb-1">Total TPS Tersedia</label>
                        <input type="number" name="total_tps" required placeholder="1"
                            class="w-full text-sm rounded-md border-border">
                    </div>
                </div>

                <div class="p-4 bg-gray-50 border border-border rounded mt-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider mb-3">Daftar Kandidat (Kosongkan Nama Jika
                        Kurang dari 3)</h4>
                    <div class="space-y-3">
                        <input type="text" name="calon_1_nama" placeholder="Nama Calon 1 (Cth: Budi Santoso)"
                            class="w-full text-sm rounded-md border-border">
                        <input type="text" name="calon_2_nama" placeholder="Nama Calon 2 (Cth: Siti Aminah)"
                            class="w-full text-sm rounded-md border-border">
                        <input type="text" name="calon_3_nama" placeholder="Nama Calon 3 (Cth: Joko Supriyanto)"
                            class="w-full text-sm rounded-md border-border">
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-border mt-6">
                    <button type="button" onclick="document.getElementById('modal-create').classList.add('hidden')"
                        class="px-4 py-2 text-sm bg-gray-100 rounded-btn font-medium text-ink">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm bg-primary text-white rounded-btn font-medium">Buka
                        Pilkades</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Desa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Hari Pencoblosan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Kandidat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Progress TPS</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-muted uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse ($pilkades as $event)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-ink">{{ $event->desa->nama_desa }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-ink">
                                {{ $event->tanggal_pemungutan->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-xs text-muted">
                                1. {{ $event->calon_1_nama ?: '-' }}<br>
                                2. {{ $event->calon_2_nama ?: '-' }}<br>
                                3. {{ $event->calon_3_nama ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1 w-32">
                                    @php $pct = $event->total_tps > 0 ? ($event->tps_lapor / $event->total_tps) * 100 : 0; @endphp
                                    <div class="bg-primary h-2.5 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-xs text-muted font-medium">{{ $event->tps_lapor }} dari
                                    {{ $event->total_tps }} TPS melapor</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($event->status === 'selesai')
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800">Selesai
                                        Ber-SK</span>
                                @elseif($event->status === 'pemungutan')
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-800 animate-pulse">🔴
                                        Live Count</span>
                                @else
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-yellow-100 text-yellow-800">Persiapan
                                        H-x</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('admin.pilkades.show', $event) }}"
                                    class="text-primary hover:text-primary-light bg-primary-soft px-3 py-1.5 rounded-md font-medium transition-colors">Tinjau
                                    & Validasi</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-muted">Belum ada setup event
                                Pilkades.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pilkades->hasPages())
            <div class="px-6 py-4 border-t border-border">{{ $pilkades->links() }}</div>
        @endif
    </div>
</x-app-layout>