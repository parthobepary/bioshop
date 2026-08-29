<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <div class="relative min-h-screen overflow-hidden bg-slate-50 text-slate-800">
        <!-- Organic ambient background -->
        <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
            <!-- Theme-tinted wash from the top -->
            <div class="absolute inset-x-0 top-0 h-[420px] bg-gradient-to-b theme-gradient-fade"></div>

            <!-- Floating organic blobs -->
            <div class="blob blob--theme animate-blob-slow absolute -top-24 -left-24 h-80 w-80"></div>
            <div class="blob blob--fuchsia animate-blob-slow animation-delay-3000 absolute top-40 -right-28 h-72 w-72"></div>
            <div class="blob blob--cyan animate-blob-slow animation-delay-6000 absolute bottom-24 -left-20 h-72 w-72"></div>
            <div class="blob blob--amber animate-blob-slow animation-delay-9000 absolute -bottom-16 right-0 h-64 w-64"></div>

            <!-- Subtle grain/vignette -->
            <div class="absolute inset-0 bg-[radial-gradient(120%_120%_at_50%_0%,transparent_50%,rgba(15,23,42,0.04)_100%)]"></div>
        </div>

        <!-- Main Content -->
        <main class="relative z-10 mx-auto max-w-xl px-4 pb-28 pt-8 sm:pt-12">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="fixed inset-x-0 bottom-0 z-30 flex justify-center pb-4">
            <Link
                href="/"
                class="group inline-flex items-center gap-2 rounded-full border border-white/60 bg-white/70 px-4 py-2 text-sm text-slate-500 shadow-lg shadow-slate-900/5 backdrop-blur-xl transition-all hover:-translate-y-0.5 hover:text-slate-700 hover:shadow-xl"
            >
                <span class="flex h-5 w-5 items-center justify-center rounded-md bg-gradient-to-br from-indigo-500 to-purple-500 text-[11px] font-bold text-white">
                    B
                </span>
                <span>Made with <span class="text-rose-500">♥</span> on <span class="font-semibold text-slate-700">BioShop</span></span>
            </Link>
        </footer>
    </div>
</template>

<style>
:root {
    --theme-color: #6366f1;
    --theme-color-light: rgba(99, 102, 241, 0.1);
    --theme-color-dark: rgb(79, 81, 193);
    --theme-color-soft: rgba(99, 102, 241, 0.06);
    --theme-color-ring: rgba(99, 102, 241, 0.25);
}

.theme-bg {
    background-color: var(--theme-color);
}
.theme-bg-light {
    background-color: var(--theme-color-light);
}
.theme-text {
    color: var(--theme-color);
}
.theme-border {
    border-color: var(--theme-color);
}
.theme-gradient {
    background-image: linear-gradient(135deg, var(--theme-color), var(--theme-color-dark));
}
.theme-ring {
    box-shadow: 0 0 0 4px var(--theme-color-soft);
}
.theme-gradient-fade {
    background-image: linear-gradient(to bottom, var(--theme-color-light), transparent);
}

/* Organic blobs */
.blob {
    border-radius: 42% 58% 63% 37% / 41% 44% 56% 59%;
    filter: blur(60px);
    opacity: 0.5;
}
.blob--theme { background: var(--theme-color); opacity: 0.35; }
.blob--fuchsia { background: #e879f9; }
.blob--cyan { background: #22d3ee; }
.blob--amber { background: #fbbf24; opacity: 0.4; }

@keyframes blob-float {
    0%, 100% { transform: translate(0, 0) scale(1) rotate(0deg); }
    33% { transform: translate(24px, -32px) scale(1.08) rotate(8deg); }
    66% { transform: translate(-18px, 20px) scale(0.94) rotate(-6deg); }
}
.animate-blob-slow {
    animation: blob-float 16s ease-in-out infinite;
}
.animation-delay-3000 { animation-delay: 3s; }
.animation-delay-6000 { animation-delay: 6s; }
.animation-delay-9000 { animation-delay: 9s; }

@media (prefers-reduced-motion: reduce) {
    .animate-blob-slow { animation: none; }
}
</style>
