<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="relative min-h-screen overflow-hidden">
        <!-- Gradient Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-indigo-50 to-purple-50"></div>

        <!-- Animated Blobs -->
        <div class="absolute top-20 left-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-20 left-1/2 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>

        <!-- Content -->
        <div class="relative flex min-h-screen flex-col items-center justify-center px-4 py-12">
            <!-- Logo -->
            <Link href="/" class="mb-8">
                <div class="flex items-center gap-2">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-gradient">BioShop</span>
                </div>
            </Link>

            <!-- Card -->
            <div class="w-full max-w-md">
                <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 border border-white/50 p-8">
                    <slot />
                </div>
            </div>

            <!-- Footer Links -->
            <div class="mt-8 flex items-center gap-6 text-sm text-slate-500">
                <Link href="/" class="hover:text-primary-600 transition-colors">Home</Link>
                <Link href="/pricing" class="hover:text-primary-600 transition-colors">Pricing</Link>
                <Link href="/terms" class="hover:text-primary-600 transition-colors">Terms</Link>
                <Link href="/privacy" class="hover:text-primary-600 transition-colors">Privacy</Link>
            </div>
        </div>
    </div>
</template>
