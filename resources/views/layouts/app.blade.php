<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIDmini') }} - @yield('title', 'Dashboard')</title>

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .scrollbar-none::-webkit-scrollbar { display: none; }
            .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
        </style>

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="font-sans antialiased bg-background text-ink flex h-screen overflow-hidden">

        <!-- ══════════════════════════════════════════════════════ -->
        <!-- UNIFIED SIDEBAR: Blue icon zone (68px) + Red text zone -->
        <!-- ══════════════════════════════════════════════════════ -->
        <aside class="flex-shrink-0 flex flex-col z-30 relative shadow-floating h-screen"
               style="width: 284px; background: linear-gradient(155deg, rgba(177,17,11,0.98) 0%, rgba(147,5,0,0.96) 48%, rgba(115,3,0,0.98) 100%); border-top-right-radius: 32px; border-bottom-right-radius: 32px; backdrop-filter: blur(18px); -webkit-backdrop-filter: blur(18px);">

            <div class="absolute inset-y-0 left-0 bg-[linear-gradient(180deg,#95BBEA_0%,#6fa7e9_100%)]" style="width: 68px;"></div>
            <div class="absolute inset-x-0 top-0 h-24 bg-[radial-gradient(circle_at_top_left,_rgba(255,255,255,0.28),_transparent_65%)] pointer-events-none"></div>
            <div class="absolute inset-x-0 bottom-0 h-24 bg-[linear-gradient(180deg,transparent_0%,rgba(0,0,0,0.16)_100%)] pointer-events-none"></div>

            <div class="relative z-10 flex flex-col h-full">
                <div class="flex h-20 flex-shrink-0 border-b border-white/15 px-3 py-4">
                    <div class="flex items-center justify-center flex-shrink-0" style="width: 68px;">
                        <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center text-white font-display font-bold text-sm shadow-[0_10px_24px_rgba(0,0,0,0.16)] ring-1 ring-white/20 backdrop-blur-sm">
                            SD
                        </div>
                    </div>
                    <div class="flex-1 flex items-center pl-3 pr-2">
                        <div class="min-w-0">
                            <p class="text-white font-display font-bold text-[15px] leading-tight truncate">SIDmini</p>
                            <p class="text-white/70 text-[10px] uppercase tracking-[0.24em] leading-tight mt-1">Sistem Informasi Desa</p>
                        </div>
                    </div>
                </div>

                <nav class="flex-1 overflow-y-auto scrollbar-none px-2 py-3">
                    @if(auth()->user()->role === 'super_admin')
                        @include('layouts.partials.admin-nav')
                    @else
                        @include('layouts.partials.desa-nav')
                    @endif
                </nav>

                <div class="border-t border-white/15 px-2 pb-3 pt-3">
                    <div class="flex items-center rounded-2xl bg-white/12 px-2 py-2 mb-2 ring-1 ring-white/10 shadow-[0_12px_28px_rgba(0,0,0,0.16)] backdrop-blur-sm">
                        <div class="flex items-center justify-center flex-shrink-0" style="width: 68px;">
                            <div class="w-8 h-8 rounded-full bg-[linear-gradient(135deg,#95BBEA_0%,#4d81c9_100%)] flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="flex-1 pl-2 pr-2 min-w-0">
                            <p class="text-white text-xs font-semibold truncate">{{ auth()->user()->name }}</p>
                            <p class="text-white/60 text-[10px] uppercase truncate mt-0.5">
                                {{ auth()->user()->role == 'super_admin' ? 'Administrator' : 'Operator Desa' }}
                            </p>
                            <p class="text-white/60 text-[10px] truncate">
                                {{ auth()->user()->role == 'super_admin' ? 'Dinpermasdes' : (auth()->user()->desa->nama_desa ?? 'Karangendep') }}
                            </p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-item w-full text-left rounded-2xl px-2">
                            <span class="nav-icon text-white/60">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </span>
                            <span class="nav-text text-white/60 font-semibold" style="padding-left: 16px;">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- ════════════════════ -->
        <!-- MAIN CONTENT         -->
        <!-- ════════════════════ -->
        <div class="flex-1 flex flex-col relative z-10 overflow-hidden">
            <main class="flex-1 overflow-y-auto bg-[linear-gradient(135deg,#f8fafc_0%,#f3f7fb_100%)] p-6 lg:p-8">
                <div class="mx-auto flex max-w-7xl flex-col gap-6">
                    <div class="feature-shell rounded-[30px] border border-[#DCE8F8] bg-white/90 px-6 py-5 shadow-[0_18px_45px_-25px_rgba(15,23,42,0.28)] backdrop-blur-xl ring-1 ring-[#E4EBF7]">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="inline-flex items-center gap-2 rounded-full border border-[#DDE9F8] bg-[linear-gradient(90deg,rgba(149,187,234,0.18)_0%,rgba(255,255,255,0.98)_100%)] px-3 py-1 shadow-sm">
                                    <span class="h-2 w-2 rounded-full bg-[#930500]"></span>
                                    <p class="text-[10px] font-semibold uppercase tracking-[0.32em] text-[#930500]">
                                        @yield('page-kicker', 'Menu Utama')
                                    </p>
                                </div>
                                <h2 class="text-xl font-display font-semibold text-slate-900">
                                    @yield('title', 'Dashboard')
                                </h2>
                            </div>
                            <div class="flex items-center gap-3">
                                @hasSection('page-actions')
                                    @yield('page-actions')
                                @else
                                    <div class="flex items-center gap-2 rounded-full border border-[#DDE9F8] bg-[linear-gradient(90deg,rgba(149,187,234,0.16)_0%,rgba(255,255,255,0.96)_100%)] px-3 py-2 shadow-sm">
                                        <span class="text-[10px] font-semibold uppercase tracking-[0.24em] text-[#930500]">Mode</span>
                                        <span class="text-sm font-semibold text-slate-700">{{ auth()->user()->role == 'super_admin' ? 'Admin' : 'Desa' }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>
