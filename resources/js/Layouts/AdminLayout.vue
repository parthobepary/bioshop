<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import {
    ArrowLeft,
    CreditCard,
    LayoutGrid,
    LogOut,
    Menu,
    Receipt,
    Users,
    X,
} from 'lucide-vue-next';

const page = usePage();
const user = computed(() => page.props.auth?.user);

const sidebarOpen = ref(false);

const navigation = [
    { name: 'Overview', href: '/admin', icon: LayoutGrid },
    { name: 'Users', href: '/admin/users', icon: Users },
    { name: 'Subscriptions', href: '/admin/subscriptions', icon: CreditCard },
    { name: 'Payments', href: '/admin/payments', icon: Receipt },
];

const isActive = (href: string) =>
    page.url === href || (href !== '/admin' && page.url.startsWith(href));

const logout = () => {
    router.post('/logout');
};
</script>

<template>
    <div class="min-h-screen bg-paper">
        <!-- Mobile backdrop -->
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 z-40 bg-ink-900/40 lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        <!-- Ink sidebar keeps admin visibly distinct from the seller dashboard -->
        <aside
            :class="[
                'fixed inset-y-0 left-0 z-50 flex w-60 flex-col bg-ink-900 transition-transform duration-200 lg:translate-x-0',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full',
            ]"
        >
            <div class="flex h-14 items-center justify-between border-b border-white/10 px-4">
                <Link href="/admin" class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-md bg-paper text-[13px] font-bold text-ink-900">
                        B
                    </span>
                    <span class="font-display text-[15px] font-semibold tracking-tight text-paper">
                        Admin
                    </span>
                </Link>
                <button
                    type="button"
                    class="rounded-md p-1.5 text-ink-400 hover:bg-white/10 lg:hidden"
                    aria-label="Close menu"
                    @click="sidebarOpen = false"
                >
                    <X class="h-4 w-4" />
                </button>
            </div>

            <nav class="flex-1 space-y-1 p-3">
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="item.href"
                    :class="[
                        'flex items-center gap-2.5 rounded-lg px-3 py-2 text-[13px] font-medium transition-colors',
                        isActive(item.href)
                            ? 'bg-white/10 text-paper'
                            : 'text-ink-400 hover:bg-white/5 hover:text-paper',
                    ]"
                    @click="sidebarOpen = false"
                >
                    <component :is="item.icon" class="h-4 w-4" />
                    {{ item.name }}
                </Link>
            </nav>

            <div class="border-t border-white/10 p-3">
                <Link
                    href="/dashboard"
                    class="flex items-center justify-center gap-2 rounded-lg border border-white/10 px-3 py-2 text-[13px] font-medium text-ink-300 transition-colors hover:bg-white/5 hover:text-paper"
                >
                    <ArrowLeft class="h-3.5 w-3.5" />
                    Back to dashboard
                </Link>
            </div>
        </aside>

        <!-- Main -->
        <div class="lg:pl-60">
            <header class="sticky top-0 z-30 border-b border-line bg-paper/85 backdrop-blur-md">
                <div class="flex h-14 items-center justify-between gap-3 px-4 lg:px-6">
                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            class="rounded-md p-1.5 text-ink-600 hover:bg-paper-deep lg:hidden"
                            aria-label="Open menu"
                            @click="sidebarOpen = true"
                        >
                            <Menu class="h-5 w-5" />
                        </button>
                        <span class="badge bg-ink-900 text-paper">Admin panel</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-ink-900 text-[12px] font-semibold text-paper">
                                {{ user?.name?.charAt(0) || 'A' }}
                            </span>
                            <span class="hidden sm:block">
                                <span class="block text-[13px] font-medium leading-tight text-ink-900">
                                    {{ user?.name || 'Admin' }}
                                </span>
                                <span class="block text-[11px] leading-tight text-ink-400">Super admin</span>
                            </span>
                        </div>

                        <button
                            type="button"
                            class="rounded-md p-1.5 text-ink-500 transition-colors hover:bg-error-50 hover:text-error-600"
                            title="Log out"
                            @click="logout"
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
