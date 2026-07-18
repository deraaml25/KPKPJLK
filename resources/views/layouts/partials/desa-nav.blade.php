<a href="{{ route('desa.dashboard') }}"
    class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('desa.dashboard') ? 'bg-primary-light text-white' : 'text-white/70 hover:bg-primary-light hover:text-white' }}">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
        </path>
    </svg>
    Dashboard
</a>

<!-- Pilar 1: Hukum & Pembinaan -->
<div class="pt-4 pb-1">
    <p class="px-3 text-xs font-semibold text-white/70 uppercase tracking-wider opacity-60">Hukum & Pembinaan</p>
</div>
<a href="{{ route('desa.regulasi.index') }}"
    class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('desa.regulasi.*') ? 'bg-primary-light text-white' : 'text-white/70 hover:bg-primary-light hover:text-white' }}">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
        </path>
    </svg>
    Draft Regulasi
</a>
<a href="{{ route('desa.bimtek.index') }}"
    class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('desa.bimtek.*') ? 'bg-primary-light text-white' : 'text-white/70 hover:bg-primary-light hover:text-white' }}">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
        </path>
    </svg>
    Pembinaan
</a>

<!-- Pilar 2: Kepegawaian & Rekomendasi -->
<div class="pt-4 pb-1">
    <p class="px-3 text-xs font-semibold text-white/70 uppercase tracking-wider opacity-60">Kepegawaian &
        Rekomendasi</p>
</div>
<a href="{{ route('desa.ajuan.index') }}"
    class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('desa.ajuan.*') ? 'bg-primary-light text-white' : 'text-white/70 hover:bg-primary-light hover:text-white' }}">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    e-Rekomendasi
</a>

<a href="{{ route('desa.pjkades.index') }}"
    class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('desa.pjkades.*') ? 'bg-primary-light text-white' : 'text-white/70 hover:bg-primary-light hover:text-white' }}">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
    </svg>
    SK Kades
</a>
<!-- Arsip Rekomendasi dihapus sesuai permintaan -->



<!-- Pilar 4: Penataan Wilayah -->
<div class="pt-4 pb-1">
    <p class="px-3 text-xs font-semibold text-white/70 uppercase tracking-wider opacity-60">Penataan Wilayah</p>
</div>
<a href="{{ route('desa.penataan.index') }}"
    class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('desa.penataan.*') ? 'bg-primary-light text-white' : 'text-white/70 hover:bg-primary-light hover:text-white' }}">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
        </path>
    </svg>
    e-Penataan Desa
</a>

<!-- Data Master -->
<div class="pt-4 pb-1">
    <p class="px-3 text-xs font-semibold text-white/70 uppercase tracking-wider opacity-60">Data Master</p>
</div>
<a href="{{ route('desa.perangkat.index') }}"
    class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('desa.perangkat.*') ? 'bg-primary-light text-white' : 'text-white/70 hover:bg-primary-light hover:text-white' }}">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
        </path>
    </svg>
    Data Perangkat Desa
</a>