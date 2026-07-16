<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIDmini') }} - @yield('title', 'Dashboard')</title>

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Chart.js (Optional CDN, can be moved if using npm) -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="font-sans antialiased bg-background text-ink flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-primary text-white flex flex-col transition-all duration-300 flex-shrink-0 relative z-20">
            <div class="h-16 flex items-center px-6 border-b border-primary-light/30">
                <h1 class="text-2xl font-display font-bold tracking-tight">SID<span class="text-primary-soft">mini</span></h1>
            </div>
            
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                @if(auth()->user()->role === 'super_admin')
                    @include('layouts.partials.admin-nav')
                @else
                    @include('layouts.partials.desa-nav')
                @endif
            </nav>

            <div class="p-4 border-t border-primary-light/30">
                <div class="flex items-center px-2 py-2">
                    <div class="w-8 h-8 rounded-full bg-primary-light flex items-center justify-center font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="ml-3 truncate">
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-primary-soft truncate">{{ auth()->user()->role == 'super_admin' ? 'Dinpermasdes' : auth()->user()->desa->nama_desa ?? 'Operator Desa' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 text-sm text-primary-soft hover:text-white hover:bg-primary-light rounded-btn transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col relative z-10 overflow-hidden">
            <!-- Header -->
            <header class="h-16 bg-surface border-b border-border flex items-center justify-between px-8 z-10">
                <h2 class="text-xl font-display font-semibold text-ink">
                    @yield('title', 'Dashboard')
                </h2>
                <div class="flex items-center">
                    <!-- Notifications/Actions can go here -->
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-8">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
