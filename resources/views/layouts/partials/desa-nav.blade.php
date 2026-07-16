<a href="{{ route('desa.dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('desa.dashboard') ? 'bg-primary-light text-white' : 'text-primary-soft hover:bg-primary-light hover:text-white' }}">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
    Dashboard
</a>
<a href="{{ route('desa.ajuan.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('desa.ajuan.*') ? 'bg-primary-light text-white' : 'text-primary-soft hover:bg-primary-light hover:text-white' }}">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
    Ajuan Saya
</a>
<a href="{{ route('desa.arsip.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-btn {{ request()->routeIs('desa.arsip.*') ? 'bg-primary-light text-white' : 'text-primary-soft hover:bg-primary-light hover:text-white' }}">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
    Arsip Rekomendasi
</a>
<div class="pt-4 pb-2">
    <p class="px-3 text-xs font-semibold text-primary-soft uppercase tracking-wider">Data Master</p>
</div>
<a href="#" class="flex items-center px-3 py-2 text-sm font-medium rounded-btn text-primary-soft hover:bg-primary-light hover:text-white">
    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
    Data Perangkat Desa
</a>
