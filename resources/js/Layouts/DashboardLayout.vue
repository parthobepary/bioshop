<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { shopVars } from '@/lib/color';
import {
    ArrowUpRight,
    BarChart3,
    CreditCard,
    ExternalLink,
    FolderTree,
    LayoutGrid,
    Link as LinkIcon,
    LogOut,
    Menu,
    MessageCircle,
    Package,
    Receipt,
    Settings,
    X,
} from 'lucide-vue-next';

const logoutForm = useForm({});

const page = usePage();
const user = computed(() => page.props.auth?.user);
const shopUrl = computed(() =>
    page.props.auth?.username ? `/${page.props.auth.username}` : null,
);

/**
 * The seller's shop colour, published to the whole dashboard as CSS variables.
 * Pages and components use the `accent-*` classes rather than hard-coded hues.
 */
const themeVars = computed(() => shopVars(page.props.auth?.theme_color));

const sidebarOpen = ref(false);

const navigation = [
    { name: 'Overview', href: '/dashboard', icon: LayoutGrid, exact: true },
    { name: 'Links', href: '/dashboard/links', icon: LinkIcon },
    { name: 'Products', href: '/dashboard/products', icon: Package },
    { name: 'Categories', href: '/dashboard/categories', icon: FolderTree },
    { name: 'Payment', href: '/dashboard/payment', icon: CreditCard },
    { name: 'WhatsApp', href: '/dashboard/whatsapp', icon: MessageCircle },
    { name: 'Analytics', href: '/dashboard/analytics', icon: BarChart3 },
];

const account = [
    { name: 'Shop settings', href: '/settings/profile', icon: Settings },
    { name: 'Billing', href: '/dashboard/billing', icon: Receipt },
];

/** `/dashboard` would otherwise light up for every child route. */
const isActive = (href: string, exact = false) =>
    exact ? page.url === href : page.url.startsWith(href);
</script>

<template>
    <div :style="themeVars" class="min-h-screen bg-paper">
        <!-- Mobile backdrop -->
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 z-40 bg-ink-900/30 lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        <!-- ---------------------------- Sidebar ---------------------------- -->
        <aside
            :class="[
                'fixed inset-y-0 left-0 z-50 flex w-60 flex-col border-r border-line bg-white transition-transform duration-200 lg:translate-x-0',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full',
            ]"
        >
            <div class="flex h-14 items-center justify-between border-b border-line px-4">
                <Link href="/" class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-md bg-ink-900 text-[13px] font-bold text-paper">
                        B
                    </span>
                    <span class="font-display text-[15px] font-semibold tracking-tight text-ink-900">BioShop</span>
                </Link>
                <button
                    type="button"
                    class="rounded-md p-1.5 text-ink-500 hover:bg-paper-deep lg:hidden"
                    aria-label="Close menu"
                    @click="sidebarOpen = false"
                >
                    <X class="h-4 w-4" />
                </button>
            </div>

            <nav class="scroll-slim flex-1 overflow-y-auto p-3">
                <p class="px-3 pb-2 text-[10px] font-semibold uppercase tracking-[0.14em] text-ink-400">
                    Shop
                </p>
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="item.href"
                    :class="[
                        'flex items-center gap-2.5 rounded-lg px-3 py-2 text-[13px] font-medium transition-colors',
                        isActive(item.href, item.exact)
                            ? 'accent-bg'
                            : 'hover-accent-tint text-ink-600 hover:text-ink-900',
                    ]"
                    @click="sidebarOpen = false"
                >
                    <component :is="item.icon" class="h-4 w-4" />
                    {{ item.name }}
                </Link>

                <p class="px-3 pb-2 pt-5 text-[10px] font-semibold uppercase tracking-[0.14em] text-ink-400">
                    Account
                </p>
                <Link
                    v-for="item in account"
                    :key="item.name"
                    :href="item.href"
                    :class="[
                        'flex items-center gap-2.5 rounded-lg px-3 py-2 text-[13px] font-medium transition-colors',
                        isActive(item.href)
                            ? 'accent-bg'
                            : 'hover-accent-tint text-ink-600 hover:text-ink-900',
                    ]"
                    @click="sidebarOpen = false"
                >
                    <component :is="item.icon" class="h-4 w-4" />
                    {{ item.name }}
                </Link>
            </nav>

            <!-- Upgrade nudge -->
            <div class="border-t border-line p-3">
                <div class="panel p-3.5">
                    <p class="text-[11px] uppercase tracking-[0.12em] text-ink-400">Current plan</p>
                    <p class="mt-0.5 text-[14px] font-semibold text-ink-900">Free</p>
                    <Link href="/dashboard/billing" class="btn accent-bg mt-3 h-8 w-full text-[12px]">
                        Upgrade
                        <ArrowUpRight class="h-3.5 w-3.5" />
                    </Link>
                </div>
            </div>
        </aside>

        <!-- ------------------------------ Main ------------------------------ -->
        <div class="lg:pl-60">
            <header class="sticky top-0 z-30 border-b border-line bg-paper/85 backdrop-blur-md">
                <div class="flex h-14 items-center justify-between gap-3 px-4 lg:px-6">
                    <button
                        type="button"
                        class="rounded-md p-1.5 text-ink-600 hover:bg-paper-deep lg:hidden"
                        aria-label="Open menu"
                        @click="sidebarOpen = true"
                    >
                        <Menu class="h-5 w-5" />
                    </button>

                    <a
                        v-if="shopUrl"
                        :href="shopUrl"
                        target="_blank"
                        rel="noopener"
                        class="hidden items-center gap-2 rounded-lg border border-line bg-white px-3 py-1.5 text-[13px] text-ink-600 transition-colors hover:border-ink-300 hover:text-ink-900 lg:inline-flex"
                    >
                        <span class="text-ink-400">bioshop.com</span>
                        <span class="accent-text font-medium">{{ shopUrl }}</span>
                        <ExternalLink class="h-3.5 w-3.5 text-ink-400" />
                    </a>
                    <Link
                        v-else
                        href="/settings/profile"
                        class="hidden items-center gap-2 rounded-lg border border-line bg-white px-3 py-1.5 text-[13px] text-ink-600 transition-colors hover:border-ink-300 hover:text-ink-900 lg:inline-flex"
                    >
                        Claim your shop link
                        <ArrowUpRight class="h-3.5 w-3.5 text-ink-400" />
                    </Link>

                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="accent-bg flex h-8 w-8 items-center justify-center rounded-full text-[12px] font-semibold">
                                {{ user?.name?.charAt(0) || 'U' }}
                            </span>
                            <span class="hidden sm:block">
                                <span class="block text-[13px] font-medium leading-tight text-ink-900">
                                    {{ user?.name }}
                                </span>
                                <span class="block text-[11px] leading-tight text-ink-400">{{ user?.email }}</span>
                            </span>
                        </div>

                        <button
                            type="button"
                            class="rounded-md p-1.5 text-ink-500 transition-colors hover:bg-error-50 hover:text-error-600"
                            title="Log out"
                            @click="logoutForm.post('/logout')"
                        >
                            <LogOut class="h-4 w-4" />
                        </button>
                    </div>
                </div>
            </header>

            <main class="px-4 py-6 lg:px-6 lg:py-8">
                <slot />
            </main>
        </div>
    </div>
</template>
