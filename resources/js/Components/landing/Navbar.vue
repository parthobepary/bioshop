<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { Menu, X } from 'lucide-vue-next'

const page = usePage()

const mobileMenuOpen = ref(false)
const scrolled = ref(false)
const activeSection = ref('top')

/** Every marketing section now lives on `/`, so nav items are anchors. */
const navigation = [
    { name: 'Features', id: 'features' },
    { name: 'How it works', id: 'how' },
    { name: 'Pricing', id: 'pricing' },
    { name: 'About', id: 'about' },
    { name: 'Contact', id: 'contact' },
]

const onHome = computed(() => page.url === '/' || page.url.startsWith('/#'))

/** From any other page the anchor needs the `/` prefix to get back home first. */
const hrefFor = (id: string) => (onHome.value ? `#${id}` : `/#${id}`)

const isActive = (id: string) => onHome.value && activeSection.value === id

let observer: IntersectionObserver | null = null

const onScroll = () => {
    scrolled.value = window.scrollY > 8
}

onMounted(() => {
    onScroll()
    window.addEventListener('scroll', onScroll, { passive: true })

    if (!onHome.value || typeof IntersectionObserver === 'undefined') return

    // Scroll-spy: the section occupying the middle band of the viewport wins.
    observer = new IntersectionObserver(
        (entries) => {
            const visible = entries
                .filter((entry) => entry.isIntersecting)
                .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0]

            if (visible) activeSection.value = visible.target.id
        },
        { rootMargin: '-45% 0px -45% 0px', threshold: 0 },
    )

    document.querySelectorAll('section[id]').forEach((section) => observer?.observe(section))
})

onBeforeUnmount(() => {
    window.removeEventListener('scroll', onScroll)
    observer?.disconnect()
})

const closeMenu = () => {
    mobileMenuOpen.value = false
}
</script>

<template>
    <header
        :class="[
            'fixed inset-x-0 top-0 z-50 border-b transition-colors duration-200',
            scrolled
                ? 'border-line bg-paper/85 backdrop-blur-md'
                : 'border-transparent bg-paper/60 backdrop-blur-sm',
        ]"
    >
        <nav class="shell-wide flex h-14 items-center justify-between gap-6">
            <!-- Wordmark -->
            <Link href="/" class="flex shrink-0 items-center gap-2" @click="closeMenu">
                <span class="flex h-7 w-7 items-center justify-center rounded-md bg-brand-600 text-[13px] font-bold text-white">
                    B
                </span>
                <span class="font-display text-[15px] font-semibold tracking-tight text-ink-900">BioShop</span>
            </Link>

            <!-- Anchors -->
            <div class="hidden items-center gap-1 md:flex">
                <a
                    v-for="item in navigation"
                    :key="item.id"
                    :href="hrefFor(item.id)"
                    :class="[
                        'rounded-md px-3 py-1.5 text-[13px] font-medium transition-colors',
                        isActive(item.id)
                            ? 'bg-brand-50 text-brand-700'
                            : 'text-ink-500 hover:text-ink-900',
                    ]"
                >
                    {{ item.name }}
                </a>
            </div>

            <!-- Account -->
            <div class="hidden items-center gap-2 md:flex">
                <template v-if="!$page.props.auth?.user">
                    <Link
                        href="/login"
                        class="rounded-md px-3 py-1.5 text-[13px] font-medium text-ink-600 transition-colors hover:text-ink-900"
                    >
                        Log in
                    </Link>
                    <Link href="/register" class="btn h-9 bg-brand-600 px-4 text-[13px] text-white hover:bg-brand-700">
                        Get started
                    </Link>
                </template>
                <Link v-else href="/dashboard" class="btn h-9 bg-brand-600 px-4 text-[13px] text-white hover:bg-brand-700">
                    Dashboard
                </Link>
            </div>

            <button
                type="button"
                class="-mr-2 rounded-md p-2 text-ink-600 transition-colors hover:bg-paper-deep hover:text-ink-900 md:hidden"
                :aria-expanded="mobileMenuOpen"
                aria-label="Toggle menu"
                @click="mobileMenuOpen = !mobileMenuOpen"
            >
                <Menu v-if="!mobileMenuOpen" class="h-5 w-5" />
                <X v-else class="h-5 w-5" />
            </button>
        </nav>

        <!-- Mobile sheet -->
        <div v-if="mobileMenuOpen" class="border-t border-line bg-paper md:hidden">
            <div class="shell-wide space-y-1 py-3">
                <a
                    v-for="item in navigation"
                    :key="item.id"
                    :href="hrefFor(item.id)"
                    class="block rounded-md px-3 py-2 text-sm font-medium text-ink-700 hover:bg-paper-deep"
                    @click="closeMenu"
                >
                    {{ item.name }}
                </a>

                <div class="mt-2 flex flex-col gap-2 border-t border-line pt-3">
                    <template v-if="!$page.props.auth?.user">
                        <Link href="/login" class="btn-secondary w-full" @click="closeMenu">Log in</Link>
                        <Link href="/register" class="btn w-full bg-brand-600 text-white hover:bg-brand-700" @click="closeMenu">Get started</Link>
                    </template>
                    <Link v-else href="/dashboard" class="btn w-full bg-brand-600 text-white hover:bg-brand-700" @click="closeMenu">Dashboard</Link>
                </div>
            </div>
        </div>
    </header>
</template>
