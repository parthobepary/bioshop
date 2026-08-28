<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import LandingLayout from '@/Layouts/LandingLayout.vue'
import { Button } from '@/Components/ui/button'
import {
    Link as LinkIcon,
    ShoppingBag,
    CreditCard,
    MessageCircle,
    BarChart3,
    Palette,
    Smartphone,
    Zap,
    Shield,
    Image,
    QrCode,
    Globe,
    Users,
    Clock,
    Layers,
    Share2,
    ArrowRight,
    Check,
} from 'lucide-vue-next'

defineOptions({
    layout: LandingLayout,
})

const mainFeatures = [
    {
        icon: LinkIcon,
        title: 'Link-in-Bio Builder',
        description: 'Create a beautiful link-in-bio page with unlimited social links, custom buttons, and embedded content. Perfect for Instagram, TikTok, and Facebook.',
        benefits: [
            'Unlimited custom links',
            'Social media icons',
            'Video embeds',
            'Drag-and-drop reordering',
        ],
        color: 'from-orange-500 to-pink-500',
    },
    {
        icon: ShoppingBag,
        title: 'Product Showcase',
        description: 'Display your products in a stunning gallery format. Add multiple images, descriptions, prices, and stock status for each product.',
        benefits: [
            'Multiple product images',
            'Price with discounts',
            'Stock status badges',
            'Product categories',
        ],
        color: 'from-purple-500 to-indigo-500',
    },
    {
        icon: CreditCard,
        title: 'Payment Integration',
        description: 'Accept payments via bKash, Nagad, Rocket, and bank transfers. Display your payment details and QR codes for easy transactions.',
        benefits: [
            'bKash integration',
            'Nagad integration',
            'Bank transfer details',
            'QR code display',
        ],
        color: 'from-green-500 to-emerald-500',
    },
    {
        icon: MessageCircle,
        title: 'WhatsApp Ordering',
        description: 'Let customers order directly via WhatsApp with pre-filled messages containing product details and their inquiries.',
        benefits: [
            'One-click WhatsApp button',
            'Pre-filled messages',
            'Product info included',
            'Easy customer contact',
        ],
        color: 'from-green-400 to-green-600',
    },
    {
        icon: BarChart3,
        title: 'Analytics Dashboard',
        description: 'Track your page performance with detailed analytics. See page views, link clicks, product views, and more.',
        benefits: [
            'Page view tracking',
            'Link click analytics',
            'Product engagement',
            'Daily/weekly reports',
        ],
        color: 'from-blue-500 to-cyan-500',
    },
    {
        icon: Palette,
        title: 'Custom Themes',
        description: 'Personalize your shop with custom colors that match your brand. Choose from our color palette or enter your own hex codes.',
        benefits: [
            'Custom theme colors',
            'Brand consistency',
            'Dark/light modes',
            'Preview before save',
        ],
        color: 'from-pink-500 to-rose-500',
    },
]

const additionalFeatures = [
    {
        icon: Smartphone,
        title: 'Mobile Optimized',
        description: 'Your shop looks perfect on all devices, especially mobile phones where most of your customers browse.',
    },
    {
        icon: Zap,
        title: 'Lightning Fast',
        description: 'Optimized for speed to ensure your customers never wait. Fast loading means more conversions.',
    },
    {
        icon: Shield,
        title: 'Secure & Reliable',
        description: 'Your data is protected with industry-standard security. We ensure 99.9% uptime.',
    },
    {
        icon: Image,
        title: 'Image Optimization',
        description: 'Automatic image compression and optimization for fast loading without quality loss.',
    },
    {
        icon: QrCode,
        title: 'QR Code Support',
        description: 'Upload and display QR codes for payment methods. Customers can scan and pay easily.',
    },
    {
        icon: Globe,
        title: 'SEO Friendly',
        description: 'Optimized for search engines so customers can find your shop on Google.',
    },
    {
        icon: Users,
        title: 'Multi-Category',
        description: 'Organize products into categories for easy browsing and better customer experience.',
    },
    {
        icon: Clock,
        title: 'Real-time Updates',
        description: 'Changes to your shop appear instantly. No waiting for deployments or refreshes.',
    },
    {
        icon: Layers,
        title: 'Product Variants',
        description: 'Coming soon: Support for product sizes, colors, and other variants.',
    },
    {
        icon: Share2,
        title: 'Easy Sharing',
        description: 'Share your shop link anywhere. Works on all social media platforms.',
    },
]
</script>

<template>
    <Head title="Features - BioShop">
        <meta name="description" content="Explore all the powerful features of BioShop. Link-in-bio, product showcase, payment integration, WhatsApp ordering, and more." />
    </Head>

    <!-- Hero -->
    <section class="py-20 bg-gradient-to-br from-slate-50 to-purple-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold text-slate-900 mb-6">
                Powerful Features for
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-pink-500">
                    Modern Sellers
                </span>
            </h1>
            <p class="text-xl text-slate-600 max-w-2xl mx-auto mb-10">
                Everything you need to create a stunning online presence and start selling. Built specifically for Bangladesh.
            </p>
            <Link href="/register">
                <Button size="lg" class="bg-orange-500 hover:bg-orange-600 text-white px-8">
                    Get Started Free
                    <ArrowRight class="w-5 h-5 ml-2" />
                </Button>
            </Link>
        </div>
    </section>

    <!-- Main Features -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-24">
                <div
                    v-for="(feature, index) in mainFeatures"
                    :key="feature.title"
                    :class="['grid grid-cols-1 lg:grid-cols-2 gap-12 items-center', index % 2 === 1 ? 'lg:flex-row-reverse' : '']"
                >
                    <!-- Content -->
                    <div :class="index % 2 === 1 ? 'lg:order-2' : ''">
                        <div :class="['w-16 h-16 rounded-2xl bg-gradient-to-br flex items-center justify-center mb-6', feature.color]">
                            <component :is="feature.icon" class="w-8 h-8 text-white" />
                        </div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-4">{{ feature.title }}</h2>
                        <p class="text-lg text-slate-600 mb-6">{{ feature.description }}</p>
                        <ul class="space-y-3">
                            <li
                                v-for="benefit in feature.benefits"
                                :key="benefit"
                                class="flex items-center gap-3"
                            >
                                <Check class="w-5 h-5 text-green-500" />
                                <span class="text-slate-700">{{ benefit }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Visual -->
                    <div :class="index % 2 === 1 ? 'lg:order-1' : ''">
                        <div class="relative">
                            <div :class="['absolute -inset-4 rounded-3xl opacity-20 blur-2xl bg-gradient-to-br', feature.color]"></div>
                            <div class="relative bg-slate-100 rounded-2xl aspect-video flex items-center justify-center">
                                <component :is="feature.icon" class="w-24 h-24 text-slate-300" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Features Grid -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">And Much More</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">
                    BioShop is packed with features to help you succeed online.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <div
                    v-for="feature in additionalFeatures"
                    :key="feature.title"
                    class="bg-white rounded-xl p-6 border border-slate-200 hover:border-orange-200 hover:shadow-lg transition-all duration-300"
                >
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mb-4">
                        <component :is="feature.icon" class="w-6 h-6 text-orange-600" />
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-2">{{ feature.title }}</h3>
                    <p class="text-sm text-slate-600">{{ feature.description }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Bangladesh Specific -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-3xl p-8 lg:p-12 text-white">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <div>
                        <h2 class="text-3xl font-bold mb-4">Built for Bangladesh</h2>
                        <p class="text-green-100 mb-6">
                            BioShop is designed specifically for Bangladeshi sellers. We understand local payment methods, customer behavior, and business needs.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3">
                                <Check class="w-5 h-5" />
                                <span>bKash, Nagad & Rocket integration</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <Check class="w-5 h-5" />
                                <span>WhatsApp ordering (most popular in BD)</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <Check class="w-5 h-5" />
                                <span>Prices in BDT (৳)</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <Check class="w-5 h-5" />
                                <span>Local support team</span>
                            </li>
                        </ul>
                    </div>
                    <div class="text-center">
                        <div class="w-48 h-48 bg-white/10 rounded-full flex items-center justify-center mx-auto">
                            <span class="text-6xl">🇧🇩</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-20 bg-gradient-to-r from-orange-500 to-pink-500">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-6">
                Ready to Experience All These Features?
            </h2>
            <p class="text-xl text-white/90 mb-8">
                Create your free shop today and see the difference.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <Link href="/register">
                    <Button size="lg" class="bg-white text-orange-600 hover:bg-slate-100 px-8">
                        Get Started Free
                        <ArrowRight class="w-5 h-5 ml-2" />
                    </Button>
                </Link>
                <Link href="/pricing">
                    <Button size="lg" variant="outline" class="border-white text-white hover:bg-white/10 px-8">
                        View Pricing
                    </Button>
                </Link>
            </div>
        </div>
    </section>
</template>
