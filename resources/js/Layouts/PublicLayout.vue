<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <div class="relative min-h-screen bg-paper text-ink-800">
        <!-- A single soft wash in the shop's own colour, nothing more. -->
        <div class="pointer-events-none fixed inset-x-0 top-0 -z-10 h-64 theme-gradient-fade"></div>

        <main class="relative z-10 mx-auto max-w-lg px-4 pb-24 pt-8 sm:pt-10">
            <slot />
        </main>

        <footer class="fixed inset-x-0 bottom-0 z-30 flex justify-center pb-4">
            <Link
                href="/"
                class="inline-flex items-center gap-2 rounded-full border border-line bg-white/90 px-3.5 py-1.5 text-[12px] text-ink-500 backdrop-blur transition-colors hover:border-ink-300 hover:text-ink-800"
            >
                <span class="flex h-4 w-4 items-center justify-center rounded bg-ink-900 text-[9px] font-bold text-paper">
                    B
                </span>
                Built with <span class="font-medium text-ink-800">BioShop</span>
            </Link>
        </footer>
    </div>
</template>

<style>
/*
 * Per-shop accent. PublicPageController overrides --theme-color from the
 * seller's profile; everything below derives from that single value.
 */
:root {
    --theme-color: #1a1a19;
    --theme-color-light: rgba(26, 26, 25, 0.08);
    --theme-color-dark: #0f0f0e;
    --theme-color-soft: rgba(26, 26, 25, 0.05);
    --theme-color-ring: rgba(26, 26, 25, 0.18);
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
/* Kept as a name for older markup, but flat now — no gradients in this system. */
.theme-gradient {
    background-color: var(--theme-color);
}
.theme-ring {
    box-shadow: 0 0 0 4px var(--theme-color-soft);
}
.theme-gradient-fade {
    background-image: linear-gradient(to bottom, var(--theme-color-light), transparent);
}
</style>
