<script setup lang="ts">
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { Facebook, Instagram, Twitter, Youtube } from 'lucide-vue-next'

const page = usePage()
const currentYear = new Date().getFullYear()

const onHome = computed(() => page.url === '/' || page.url.startsWith('/#'))
const anchor = (id: string) => (onHome.value ? `#${id}` : `/#${id}`)

const columns = computed(() => [
    {
        title: 'Product',
        links: [
            { name: 'Features', href: anchor('features') },
            { name: 'How it works', href: anchor('how') },
            { name: 'Pricing', href: anchor('pricing') },
            { name: 'Reviews', href: anchor('reviews') },
        ],
    },
    {
        title: 'Company',
        links: [
            { name: 'About', href: anchor('about') },
            { name: 'Contact', href: anchor('contact') },
            { name: 'FAQ', href: anchor('faq') },
        ],
    },
    {
        title: 'Legal',
        links: [
            { name: 'Terms of Service', href: '/terms' },
            { name: 'Privacy Policy', href: '/privacy' },
        ],
    },
])

const socialLinks = [
    { name: 'Facebook', icon: Facebook, href: 'https://facebook.com/bioshop' },
    { name: 'Instagram', icon: Instagram, href: 'https://instagram.com/bioshop' },
    { name: 'Twitter', icon: Twitter, href: 'https://twitter.com/bioshop' },
    { name: 'YouTube', icon: Youtube, href: 'https://youtube.com/bioshop' },
]
</script>

<template>
    <footer class="border-t border-line bg-paper-subtle">
        <div class="shell-wide py-12">
            <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-5 lg:gap-10">
                <!-- Brand -->
                <div class="col-span-2">
                    <Link href="/" class="mb-3 inline-flex items-center gap-2">
                        <span class="flex h-7 w-7 items-center justify-center rounded-md bg-brand-600 text-[13px] font-bold text-white">
                            B
                        </span>
                        <span class="font-display text-[15px] font-semibold tracking-tight text-ink-900">BioShop</span>
                    </Link>
                    <p class="max-w-xs text-[13px] leading-relaxed text-ink-500">
                        One link for your products, payments and orders — built for sellers in Bangladesh.
                    </p>
                    <div class="mt-5 flex items-center gap-2">
                        <a
                            v-for="social in socialLinks"
                            :key="social.name"
                            :href="social.href"
                            target="_blank"
                            rel="noopener noreferrer"
                            :aria-label="social.name"
                            class="flex h-8 w-8 items-center justify-center rounded-md border border-line bg-white text-ink-500 transition-colors hover:border-brand-300 hover:text-brand-600"
                        >
                            <component :is="social.icon" class="h-4 w-4" />
                        </a>
                    </div>
                </div>

                <!-- Link columns -->
                <div v-for="column in columns" :key="column.title">
                    <h3 class="mb-3 text-[11px] font-semibold uppercase tracking-[0.14em] text-ink-400">
                        {{ column.title }}
                    </h3>
                    <ul class="space-y-2">
                        <li v-for="link in column.links" :key="link.name">
                            <component
                                :is="link.href.startsWith('#') ? 'a' : Link"
                                :href="link.href"
                                class="text-[13px] text-ink-600 transition-colors hover:text-ink-900"
                            >
                                {{ link.name }}
                            </component>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-10 flex flex-col items-center justify-between gap-3 border-t border-line pt-6 sm:flex-row">
                <p class="text-[13px] text-ink-500">&copy; {{ currentYear }} BioShop. All rights reserved.</p>
                <div class="flex items-center gap-5 text-[13px] text-ink-500">
                    <a href="mailto:hello@bioshop.com" class="transition-colors hover:text-ink-900">hello@bioshop.com</a>
                    <span>Dhaka, Bangladesh</span>
                </div>
            </div>
        </div>
    </footer>
</template>
