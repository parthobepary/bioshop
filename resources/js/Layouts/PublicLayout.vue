<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-50">
        <!-- Main Content -->
        <main class="max-w-2xl mx-auto px-4 py-8 pb-24">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-xl border-t border-slate-100 py-3">
            <div class="max-w-2xl mx-auto px-4">
                <div class="flex items-center justify-center gap-2 text-sm text-slate-500">
                    <span>Powered by</span>
                    <Link href="/" class="font-semibold text-slate-700 hover:text-primary-600 transition-colors">
                        BioShop
                    </Link>
                </div>
            </div>
        </footer>
    </div>
</template>

<style>
:root {
    --theme-color: #6366f1;
    --theme-color-light: rgba(99, 102, 241, 0.1);
    --theme-color-dark: rgb(79, 81, 193);
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
</style>
