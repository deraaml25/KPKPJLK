<a href="{{ route('admin.dashboard') }}"
    class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('admin.dashboard') ? 'bg-primary-light text-white' : 'text-white/70 hover:bg-primary-light hover:text-white' }}">
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
<a href="{{ route('admin.regulasi.index') }}"
    class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('admin.regulasi.*') ? 'bg-primary-light text-white' : 'text-white/70 hover:bg-primary-light hover:text-white' }}">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
        </path>
    </svg>
    e-Regulasi
</a>
<a href="{{ route('admin.bimtek.index') }}"
    class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('admin.bimtek.*') ? 'bg-primary-light text-white' : 'text-white/70 hover:bg-primary-light hover:text-white' }}">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
        </path>
    </svg>
    e-Bimtek
</a>

<!-- Pilar 2: Kepegawaian & Rekomendasi -->
<div class="pt-4 pb-1">
    <p class="px-3 text-xs font-semibold text-white/70 uppercase tracking-wider opacity-60">Kepegawaian &
        Rekomendasi</p>
</div>
<a href="{{ route('admin.ajuan.index') }}"
    class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('admin.ajuan.*') ? 'bg-primary-light text-white' : 'text-white/70 hover:bg-primary-light hover:text-white' }}">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
        </path>
    </svg>
    Ajuan Masuk
</a>
<a href="{{ route('admin.siltap.index') }}"
    class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('admin.siltap.*') ? 'bg-primary-light text-white' : 'text-white/70 hover:bg-primary-light hover:text-white' }}">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
        </path>
    </svg>
    e-Siltap
</a>
<a href="{{ route('admin.pjkades.index') }}"
    class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('admin.pjkades.*') ? 'bg-primary-light text-white' : 'text-white/70 hover:bg-primary-light hover:text-white' }}">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
    </svg>
    e-Pj Kades
</a>
<a href="{{ route('admin.drive.index') }}"
    class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('admin.drive.*') ? 'bg-primary-light text-white' : 'text-white/70 hover:bg-primary-light hover:text-white' }}">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
    </svg>
    Drive Dokumen
</a>
<a href="{{ route('admin.arsip.index') }}"
    class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('admin.arsip.*') ? 'bg-primary-light text-white' : 'text-white/70 hover:bg-primary-light hover:text-white' }}">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
    </svg>
    Arsip Rekomendasi
</a>

<!-- Pilar 3: Demokrasi Desa -->
<div class="pt-4 pb-1">
    <p class="px-3 text-xs font-semibold text-white/70 uppercase tracking-wider opacity-60">Demokrasi Desa</p>
</div>
<a href="{{ route('admin.izincalon.index') }}"
    class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('admin.izincalon.*') ? 'bg-primary-light text-white' : 'text-white/70 hover:bg-primary-light hover:text-white' }}">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
        </path>
    </svg>
    e-Izin Calon
</a>
<a href="{{ route('admin.pilkades.index') }}"
    class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('admin.pilkades.*') ? 'bg-primary-light text-white' : 'text-white/70 hover:bg-primary-light hover:text-white' }}">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
        </path>
    </svg>
    e-Pilkades
</a>

<!-- Pilar 4: Penataan Wilayah -->
<div class="pt-4 pb-1">
    <p class="px-3 text-xs font-semibold text-white/70 uppercase tracking-wider opacity-60">Penataan Wilayah</p>
</div>
<a href="{{ route('admin.penataan.index') }}"
    class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('admin.penataan.*') ? 'bg-primary-light text-white' : 'text-white/70 hover:bg-primary-light hover:text-white' }}">
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
<a href="{{ route('admin.perangkat.index') }}"
    class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('admin.perangkat.*') ? 'bg-primary-light text-white' : 'text-white/70 hover:bg-primary-light hover:text-white' }}">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
        </path>
    </svg>
    Data Perangkat Desa
</a>
<a href="#"
    class="flex items-center px-3 py-2 text-sm font-medium rounded-btn text-white/70 hover:bg-primary-light hover:text-white">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
        </path>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
        </path>
    </svg>
    Pengaturan Master
</a>