<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { ArrowLeft, Check } from 'lucide-vue-next'

interface Props {
    /** Heading shown above the form on the right. */
    heading: string
    /** Supporting line under the heading. */
    description?: string
    /** Headline of the left information panel. */
    panelTitle: string
    /** Short paragraph under the panel headline. */
    panelText?: string
    /** Bulleted selling points in the panel. */
    points?: string[]
    /** Optional quote at the foot of the panel. */
    quote?: { text: string; name: string; role: string } | null
}

withDefaults(defineProps<Props>(), {
    description: '',
    panelText: '',
    points: () => [],
    quote: null,
})
</script>

<template>
    <div class="min-h-screen bg-paper lg:grid lg:grid-cols-2">
        <!-- ---------------------------- Info panel ---------------------------- -->
        <aside class="relative hidden flex-col justify-between overflow-hidden bg-brand-600 p-10 lg:flex xl:p-14">
            <!-- Same soft colour fields as the landing hero, so the funnel matches -->
            <div class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-16 h-72 w-72 rounded-full bg-pop-sky/25 blur-3xl"></div>
            <div class="grid-paper pointer-events-none absolute inset-0 opacity-[0.07]"></div>

            <Link href="/" class="relative flex items-center gap-2">
                <span class="flex h-7 w-7 items-center justify-center rounded-md bg-white text-[13px] font-bold text-brand-700">
                    B
                </span>
                <span class="font-display text-[15px] font-semibold tracking-tight text-white">BioShop</span>
            </Link>

            <div class="relative max-w-md">
                <h2 class="font-display text-3xl font-semibold leading-[1.15] tracking-[-0.02em] text-white">
                    {{ panelTitle }}
                </h2>
                <p v-if="panelText" class="mt-4 text-[15px] leading-relaxed text-brand-100">
                    {{ panelText }}
                </p>

                <ul v-if="points.length" class="mt-8 space-y-3">
                    <li v-for="point in points" :key="point" class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-full bg-white/20">
                            <Check class="h-2.5 w-2.5 text-white" />
                        </span>
                        <span class="text-[14px] leading-relaxed text-brand-50">{{ point }}</span>
                    </li>
                </ul>
            </div>

            <figure v-if="quote" class="relative max-w-md border-t border-white/20 pt-6">
                <blockquote class="text-[14px] leading-relaxed text-brand-50">“{{ quote.text }}”</blockquote>
                <figcaption class="mt-3 text-[13px] text-brand-200">
                    <span class="font-medium text-white">{{ quote.name }}</span> · {{ quote.role }}
                </figcaption>
            </figure>
            <div v-else class="relative"></div>
        </aside>

        <!-- ------------------------------ Form ------------------------------ -->
        <main class="flex min-h-screen flex-col px-5 py-8 sm:px-8 lg:min-h-0 lg:px-10 lg:py-10">
            <div class="flex items-center justify-between lg:justify-end">
                <Link href="/" class="flex items-center gap-2 lg:hidden">
                    <span class="flex h-7 w-7 items-center justify-center rounded-md bg-brand-600 text-[13px] font-bold text-white">
                        B
                    </span>
                    <span class="font-display text-[15px] font-semibold tracking-tight text-ink-900">BioShop</span>
                </Link>

                <Link
                    href="/"
                    class="inline-flex items-center gap-1.5 text-[13px] text-ink-500 transition-colors hover:text-ink-900"
                >
                    <ArrowLeft class="h-3.5 w-3.5" />
                    Back to site
                </Link>
            </div>

            <div class="flex flex-1 items-center justify-center py-10">
                <div class="w-full max-w-sm">
                    <h1 class="font-display text-2xl font-semibold tracking-[-0.02em] text-ink-900">
                        {{ heading }}
                    </h1>
                    <p v-if="description" class="mt-1.5 text-[14px] text-ink-500">{{ description }}</p>

                    <div class="mt-7">
                        <slot />
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-[12px] text-ink-400">
                <Link href="/terms" class="transition-colors hover:text-ink-700">Terms</Link>
                <Link href="/privacy" class="transition-colors hover:text-ink-700">Privacy</Link>
                <a href="mailto:hello@bioshop.com" class="transition-colors hover:text-ink-700">Support</a>
            </div>
        </main>
    </div>
</template>
