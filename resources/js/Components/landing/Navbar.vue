<script setup lang="ts">
import { ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import { Menu, X } from 'lucide-vue-next'

const page = usePage()
const mobileMenuOpen = ref(false)

const navigation = [
    { name: 'Features', href: '/features' },
    { name: 'Pricing', href: '/pricing' },
    { name: 'About', href: '/about' },
    { name: 'Contact', href: '/contact' },
]

const isActive = (href: string) => {
    return page.url === href
}
</script>

<template>
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <Link href="/" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-pink-500 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">B</span>
                    </div>
                    <span class="text-xl font-bold text-slate-900">BioShop</span>
                </Link>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center gap-8">
                    <Link
                        v-for="item in navigation"
                        :key="item.name"
                        :href="item.href"
                        :class="[
                            'text-sm font-medium transition-colors',
                            isActive(item.href)
                                ? 'text-orange-600'
                                : 'text-slate-600 hover:text-slate-900'
                        ]"
                    >
                        {{ item.name }}
                    </Link>
                </div>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center gap-3">
                    <Link
                        v-if="!$page.props.auth?.user"
                        href="/login"
                        class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors"
                    >
                        Log in
                    </Link>
                    <Link
                        v-if="!$page.props.auth?.user"
                        href="/register"
                    >
                        <Button class="bg-orange-500 hover:bg-orange-600 text-white">
                            Get Started Free
                        </Button>
                    </Link>
                    <Link
                        v-if="$page.props.auth?.user"
                        href="/dashboard"
                    >
                        <Button class="bg-orange-500 hover:bg-orange-600 text-white">
                            Dashboard
                        </Button>
                    </Link>
                </div>

                <!-- Mobile Menu Button -->
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden p-2 text-slate-600 hover:text-slate-900"
                >
                    <Menu v-if="!mobileMenuOpen" class="w-6 h-6" />
                    <X v-else class="w-6 h-6" />
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div
            v-if="mobileMenuOpen"
            class="md:hidden bg-white border-b border-slate-200"
        >
            <div class="px-4 py-4 space-y-3">
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="item.href"
                    @click="mobileMenuOpen = false"
                    :class="[
                        'block py-2 text-base font-medium transition-colors',
                        isActive(item.href)
                            ? 'text-orange-600'
                            : 'text-slate-600 hover:text-slate-900'
                    ]"
                >
                    {{ item.name }}
                </Link>
                <div class="pt-4 border-t border-slate-200 space-y-3">
                    <Link
                        v-if="!$page.props.auth?.user"
                        href="/login"
                        @click="mobileMenuOpen = false"
                        class="block py-2 text-base font-medium text-slate-600"
                    >
                        Log in
                    </Link>
                    <Link
                        v-if="!$page.props.auth?.user"
                        href="/register"
                        @click="mobileMenuOpen = false"
                    >
                        <Button class="w-full bg-orange-500 hover:bg-orange-600 text-white">
                            Get Started Free
                        </Button>
                    </Link>
                    <Link
                        v-if="$page.props.auth?.user"
                        href="/dashboard"
                        @click="mobileMenuOpen = false"
                    >
                        <Button class="w-full bg-orange-500 hover:bg-orange-600 text-white">
                            Dashboard
                        </Button>
                    </Link>
                </div>
            </div>
        </div>
    </nav>
</template>
