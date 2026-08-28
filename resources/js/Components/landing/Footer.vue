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
    <footer class="bg-slate-900 text-slate-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-12">
                <!-- Brand -->
                <div class="lg:col-span-2">
                    <Link href="/" class="flex items-center gap-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-pink-500 rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-lg">B</span>
                        </div>
                        <span class="text-2xl font-bold text-white">BioShop</span>
                    </Link>
                    <p class="text-slate-400 mb-6 max-w-sm">
                        The all-in-one link-in-bio platform for creators and businesses in Bangladesh. Showcase your products, accept payments, and grow your audience.
                    </p>
                    <div class="flex items-center gap-4">
                        <a
                            v-for="social in socialLinks"
                            :key="social.name"
                            :href="social.href"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="w-10 h-10 bg-slate-800 hover:bg-orange-500 rounded-lg flex items-center justify-center transition-colors"
                        >
                            <component :is="social.icon" class="w-5 h-5 text-slate-400 hover:text-white" />
                        </a>
                    </div>
                </div>

                <!-- Product -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Product</h3>
                    <ul class="space-y-3">
                        <li v-for="link in footerLinks.product" :key="link.name">
                            <Link
                                :href="link.href"
                                class="hover:text-white transition-colors"
                            >
                                {{ link.name }}
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Company</h3>
                    <ul class="space-y-3">
                        <li v-for="link in footerLinks.company" :key="link.name">
                            <Link
                                :href="link.href"
                                class="hover:text-white transition-colors"
                            >
                                {{ link.name }}
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Legal</h3>
                    <ul class="space-y-3">
                        <li v-for="link in footerLinks.legal" :key="link.name">
                            <Link
                                :href="link.href"
                                class="hover:text-white transition-colors"
                            >
                                {{ link.name }}
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="mt-12 pt-8 border-t border-slate-800">
                <div class="flex flex-wrap gap-6 mb-8">
                    <a href="mailto:hello@bioshop.com" class="flex items-center gap-2 hover:text-white transition-colors">
                        <Mail class="w-5 h-5" />
                        hello@bioshop.com
                    </a>
                    <a href="tel:+8801700000000" class="flex items-center gap-2 hover:text-white transition-colors">
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
