<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { Facebook, Instagram, Twitter, Youtube, Mail, Phone, MapPin } from 'lucide-vue-next'

const currentYear = new Date().getFullYear()

const footerLinks = {
    product: [
        { name: 'Features', href: '/features' },
        { name: 'Pricing', href: '/pricing' },
        { name: 'Templates', href: '/features#templates' },
        { name: 'Integrations', href: '/features#integrations' },
    ],
    company: [
        { name: 'About', href: '/about' },
        { name: 'Contact', href: '/contact' },
        { name: 'Blog', href: '/blog' },
        { name: 'Careers', href: '/careers' },
    ],
    legal: [
        { name: 'Terms of Service', href: '/terms' },
        { name: 'Privacy Policy', href: '/privacy' },
        { name: 'Cookie Policy', href: '/privacy#cookies' },
        { name: 'Refund Policy', href: '/terms#refunds' },
    ],
}

const socialLinks = [
    { name: 'Facebook', icon: Facebook, href: 'https://facebook.com/bioshop' },
    { name: 'Instagram', icon: Instagram, href: 'https://instagram.com/bioshop' },
    { name: 'Twitter', icon: Twitter, href: 'https://twitter.com/bioshop' },
    { name: 'YouTube', icon: Youtube, href: 'https://youtube.com/bioshop' },
]
</script>

<template>
    <footer class="border-t border-slate-200 bg-slate-50 text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-12">
                <!-- Brand -->
                <div class="lg:col-span-2">
                    <Link href="/" class="flex items-center gap-2 mb-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 shadow-sm">
                            <span class="text-lg font-bold text-white">B</span>
                        </div>
                        <span class="text-2xl font-bold text-slate-900">BioShop</span>
                    </Link>
                    <p class="mb-6 max-w-sm text-slate-500">
                        The all-in-one link-in-bio platform for creators and businesses in Bangladesh. Showcase your products, accept payments, and grow your audience.
                    </p>
                    <div class="flex items-center gap-3">
                        <a
                            v-for="social in socialLinks"
                            :key="social.name"
                            :href="social.href"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="group flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white transition-all hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50"
                        >
                            <component :is="social.icon" class="h-5 w-5 text-slate-500 transition-colors group-hover:text-indigo-600" />
                        </a>
                    </div>
                </div>

                <!-- Product -->
                <div>
                    <h3 class="font-semibold text-slate-900 mb-4">Product</h3>
                    <ul class="space-y-3">
                        <li v-for="link in footerLinks.product" :key="link.name">
                            <Link
                                :href="link.href"
                                class="hover:text-slate-900 transition-colors"
                            >
                                {{ link.name }}
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h3 class="font-semibold text-slate-900 mb-4">Company</h3>
                    <ul class="space-y-3">
                        <li v-for="link in footerLinks.company" :key="link.name">
                            <Link
                                :href="link.href"
                                class="hover:text-slate-900 transition-colors"
                            >
                                {{ link.name }}
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h3 class="font-semibold text-slate-900 mb-4">Legal</h3>
                    <ul class="space-y-3">
                        <li v-for="link in footerLinks.legal" :key="link.name">
                            <Link
                                :href="link.href"
                                class="hover:text-slate-900 transition-colors"
                            >
                                {{ link.name }}
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="mt-12 pt-8 border-t border-slate-200">
                <div class="flex flex-wrap gap-6 mb-8">
                    <a href="mailto:hello@bioshop.com" class="flex items-center gap-2 hover:text-slate-900 transition-colors">
                        <Mail class="w-5 h-5" />
                        hello@bioshop.com
                    </a>
                    <a href="tel:+8801700000000" class="flex items-center gap-2 hover:text-slate-900 transition-colors">
                        <Phone class="w-5 h-5" />
                        +880 1700-000000
                    </a>
                    <span class="flex items-center gap-2">
                        <MapPin class="w-5 h-5" />
                        Dhaka, Bangladesh
                    </span>
                </div>

                <!-- Copyright -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-sm">
                        &copy; {{ currentYear }} BioShop. All rights reserved.
                    </p>
                    <p class="text-sm">
                        Made with <span class="text-red-500">♥</span> in Bangladesh
                    </p>
                </div>
            </div>
        </div>
    </footer>
</template>
