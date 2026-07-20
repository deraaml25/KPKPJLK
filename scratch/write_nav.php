<?php

$desaNav = <<<'HTML'
<a href="{{ route('desa.dashboard') }}"
    data-group="dashboard"
    class="nav-item {{ request()->routeIs('desa.dashboard') ? 'is-active' : '' }}">
    <span class="nav-icon"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg></span>
    <span class="nav-text">Dashboard</span>
</a>

<div class="nav-section"><div class="section-spacer"></div><div class="section-label">Hukum &amp; Pembinaan</div></div>

<a href="{{ route('desa.regulasi.index') }}"
    data-group="hukum"
    class="nav-item {{ request()->routeIs('desa.regulasi.*') ? 'is-active' : '' }}">
    <span class="nav-icon"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></span>
    <span class="nav-text">Draft Regulasi</span>
</a>

<a href="{{ route('desa.bimtek.index') }}"
    data-group="hukum"
    class="nav-item {{ request()->routeIs('desa.bimtek.*') ? 'is-active' : '' }}">
    <span class="nav-icon"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></span>
    <span class="nav-text">Pembinaan</span>
</a>

<div class="nav-section"><div class="section-spacer"></div><div class="section-label">Kepegawaian</div></div>

<a href="{{ route('desa.ajuan.index') }}"
    data-group="kepegawaian"
    class="nav-item {{ request()->routeIs('desa.ajuan.*') ? 'is-active' : '' }}">
    <span class="nav-icon"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
    <span class="nav-text">e-Rekomendasi</span>
</a>

<a href="{{ route('desa.pjkades.index') }}"
    data-group="kepegawaian"
    class="nav-item {{ request()->routeIs('desa.pjkades.*') ? 'is-active' : '' }}">
    <span class="nav-icon"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg></span>
    <span class="nav-text">SK Kades</span>
</a>

<div class="nav-section"><div class="section-spacer"></div><div class="section-label">Penataan Wilayah</div></div>

<a href="{{ route('desa.penataan.index') }}"
    data-group="penataan"
    class="nav-item {{ request()->routeIs('desa.penataan.*') ? 'is-active' : '' }}">
    <span class="nav-icon"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
    <span class="nav-text">e-Penataan Desa</span>
</a>

<div class="nav-section"><div class="section-spacer"></div><div class="section-label">Data Master</div></div>

<a href="{{ route('desa.perangkat.index') }}"
    data-group="master"
    class="nav-item {{ request()->routeIs('desa.perangkat.*') ? 'is-active' : '' }}">
    <span class="nav-icon"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg></span>
    <span class="nav-text">Data Perangkat Desa</span>
</a>
HTML;

file_put_contents('c:\\laragon\\www\\sidmini\\resources\\views\\layouts\\partials\\desa-nav.blade.php', $desaNav);

$adminNav = <<<'HTML'
<a href="{{ route('admin.dashboard') }}"
    data-group="dashboard"
    class="nav-item {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
    <span class="nav-icon"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg></span>
    <span class="nav-text">Dashboard</span>
</a>

<div class="nav-section"><div class="section-spacer"></div><div class="section-label">Hukum &amp; Pembinaan</div></div>

<a href="{{ route('admin.regulasi.index') }}"
    data-group="hukum"
    class="nav-item {{ request()->routeIs('admin.regulasi.*') ? 'is-active' : '' }}">
    <span class="nav-icon"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></span>
    <span class="nav-text">Draft Regulasi</span>
</a>

<a href="{{ route('admin.bimtek.index') }}"
    data-group="hukum"
    class="nav-item {{ request()->routeIs('admin.bimtek.*') ? 'is-active' : '' }}">
    <span class="nav-icon"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></span>
    <span class="nav-text">Pembinaan</span>
</a>

<div class="nav-section"><div class="section-spacer"></div><div class="section-label">Kepegawaian</div></div>

<a href="{{ route('admin.ajuan.index') }}"
    data-group="kepegawaian"
    class="nav-item {{ request()->routeIs('admin.ajuan.*') ? 'is-active' : '' }}">
    <span class="nav-icon"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
    <span class="nav-text">e-Rekomendasi</span>
</a>

<a href="{{ route('admin.pjkades.index') }}"
    data-group="kepegawaian"
    class="nav-item {{ request()->routeIs('admin.pjkades.*') ? 'is-active' : '' }}">
    <span class="nav-icon"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg></span>
    <span class="nav-text">SK Kades</span>
</a>

<a href="{{ route('admin.drive.index') }}"
    data-group="kepegawaian"
    class="nav-item {{ request()->routeIs('admin.drive.*') ? 'is-active' : '' }}">
    <span class="nav-icon"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg></span>
    <span class="nav-text">Drive Dokumen</span>
</a>

<a href="{{ route('admin.arsip.index') }}"
    data-group="kepegawaian"
    class="nav-item {{ request()->routeIs('admin.arsip.*') ? 'is-active' : '' }}">
    <span class="nav-icon"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg></span>
    <span class="nav-text">Arsip Rekomendasi</span>
</a>

<div class="nav-section"><div class="section-spacer"></div><div class="section-label">Penataan Wilayah</div></div>

<a href="{{ route('admin.penataan.index') }}"
    data-group="penataan"
    class="nav-item {{ request()->routeIs('admin.penataan.*') ? 'is-active' : '' }}">
    <span class="nav-icon"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
    <span class="nav-text">e-Penataan Desa</span>
</a>

<div class="nav-section"><div class="section-spacer"></div><div class="section-label">Data Master</div></div>

<a href="{{ route('admin.perangkat.index') }}"
    data-group="master"
    class="nav-item {{ request()->routeIs('admin.perangkat.*') ? 'is-active' : '' }}">
    <span class="nav-icon"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg></span>
    <span class="nav-text">Data Perangkat Desa</span>
</a>

<a href="#"
    data-group="master"
    class="nav-item">
    <span class="nav-icon"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></span>
    <span class="nav-text">Pengaturan Master</span>
</a>
HTML;

file_put_contents('c:\\laragon\\www\\sidmini\\resources\\views\\layouts\\partials\\admin-nav.blade.php', $adminNav);

echo 'Done writing nav blades';
