@props(['icon', 'title', 'message', 'action' => null])

<style>
    @keyframes floatY {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-8px);
        }

        100% {
            transform: translateY(0px);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-rich-float {
        animation: floatY 4s ease-in-out infinite;
    }

    .animate-fade-up {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .shadow-premium {
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.05), 0 10px 20px -5px rgba(0, 0, 0, 0.02);
    }
</style>

<div class="flex flex-col items-center justify-center py-16 px-4 animate-fade-up">
    <!-- Icon with Glow and Float -->
    <div class="relative mb-6 animate-rich-float group">
        <!-- Ambient background blur -->
        <div
            class="absolute inset-0 bg-primary/20 rounded-full blur-xl scale-125 opacity-70 group-hover:opacity-100 group-hover:bg-primary/30 transition-all duration-700">
        </div>

        <!-- Physical Icon Box -->
        <div
            class="relative bg-gradient-to-br from-white to-gray-50 border border-gray-100/50 w-24 h-24 rounded-3xl shadow-premium flex items-center justify-center transform transition-transform duration-500">
            <div class="absolute inset-0 rounded-3xl border border-white/60 bg-white/40 backdrop-blur-sm"></div>
            <svg class="relative w-10 h-10 text-primary drop-shadow-sm transition-transform duration-500 group-hover:scale-110"
                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                {!! $icon !!}
            </svg>
        </div>
    </div>

    <!-- Text Formatting -->
    <h3 class="text-lg font-display font-bold text-ink mb-1.5">{{ $title }}</h3>
    <p class="text-sm text-muted max-w-sm text-center leading-relaxed">
        {{ $message }}
    </p>

    <!-- Action Slot -->
    @if($action)
        <div class="mt-6">
            {{ $action }}
        </div>
    @endif
</div>