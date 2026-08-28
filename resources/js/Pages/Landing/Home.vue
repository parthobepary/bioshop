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
    Check,
    ArrowRight,
    Star,
} from 'lucide-vue-next'

interface Plan {
    id: number
    name: string
    price: number
    features: string[]
}

interface Props {
    stats: {
        users: number
        profiles: number
        products: number
    }
    plans: Plan[]
}

const props = defineProps<Props>()

defineOptions({
    layout: LandingLayout,
})

const features = [
    {
        icon: LinkIcon,
        title: 'Link-in-Bio',
        description: 'Add unlimited social links, videos, and custom buttons to your page.',
        color: 'from-orange-500 to-pink-500',
    },
    {
        icon: ShoppingBag,
        title: 'Product Showcase',
        description: 'Display your products with beautiful galleries and detailed descriptions.',
        color: 'from-purple-500 to-indigo-500',
    },
    {
        icon: CreditCard,
        title: 'Payment Methods',
        description: 'Accept bKash, Nagad, Rocket, and bank transfers with QR codes.',
        color: 'from-green-500 to-emerald-500',
    },
    {
        icon: MessageCircle,
        title: 'WhatsApp Integration',
        description: 'One-click WhatsApp ordering with pre-filled product messages.',
        color: 'from-green-400 to-green-600',
    },
    {
        icon: BarChart3,
        title: 'Analytics Dashboard',
        description: 'Track page views, link clicks, and product engagement.',
        color: 'from-blue-500 to-cyan-500',
    },
    {
        icon: Palette,
        title: 'Customizable Themes',
        description: 'Choose colors and styles that match your brand identity.',
        color: 'from-pink-500 to-rose-500',
    },
]

const howItWorks = [
    {
        step: 1,
        title: 'Create Your Profile',
        description: 'Sign up and choose a unique username for your shop.',
    },
    {
        step: 2,
        title: 'Add Your Products',
        description: 'Upload product images, set prices, and write descriptions.',
    },
    {
        step: 3,
        title: 'Set Up Payments',
        description: 'Add your bKash, Nagad, or bank account details.',
    },
    {
        step: 4,
        title: 'Share Your Link',
        description: 'Put your link in bio and start receiving orders.',
    },
]

const testimonials = [
    {
        name: 'Sarah Rahman',
        role: 'Fashion Boutique Owner',
        image: null,
        content: 'BioShop transformed my Instagram business. I went from DM chaos to organized orders in just a week!',
        rating: 5,
    },
    {
        name: 'Karim Ahmed',
        role: 'Handmade Crafts Seller',
        content: 'The WhatsApp integration is a game-changer. Customers can order directly without any confusion.',
        image: null,
        rating: 5,
    },
    {
        name: 'Fatima Begum',
        role: 'Home Baker',
        content: 'Finally, a platform that understands Bangladesh! bKash and Nagad integration makes payment so easy.',
        image: null,
        rating: 5,
    },
]

const formatPrice = (price: number) => {
    if (price === 0) return 'Free'
    return `৳${price}/mo`
}
</script>

<template>
    <Head title="BioShop - Link-in-Bio for Bangladesh">
        <meta name="description" content="The all-in-one link-in-bio platform for creators and businesses in Bangladesh. Showcase products, accept payments, and grow your audience." />
    </Head>

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-50 via-orange-50 to-pink-50">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-30">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgb(249 115 22 / 0.15) 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
            <div class="text-center max-w-4xl mx-auto">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-orange-100 text-orange-700 rounded-full text-sm font-medium mb-8">
                    <Zap class="w-4 h-4" />
                    Built for Bangladesh
                </div>

                <!-- Headline -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-900 leading-tight mb-6">
                    Your Products,
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-pink-500">
                        One Beautiful Link
                    </span>
                </h1>

                <!-- Subheadline -->
                <p class="text-xl text-slate-600 mb-10 max-w-2xl mx-auto">
                    Create a stunning link-in-bio page. Showcase your products, accept bKash/Nagad payments, and let customers order via WhatsApp.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12">
                    <Link href="/register">
                        <Button size="lg" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-6 text-lg">
                            Get Started Free
                            <ArrowRight class="w-5 h-5 ml-2" />
                        </Button>
                    </Link>
                    <Link href="/features">
                        <Button size="lg" variant="outline" class="px-8 py-6 text-lg">
                            See Features
                        </Button>
                    </Link>
                </div>

                <!-- Stats -->
                <div class="flex flex-wrap items-center justify-center gap-8 lg:gap-16">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-slate-900">{{ stats.users.toLocaleString() }}+</p>
                        <p class="text-slate-600">Active Sellers</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-slate-900">{{ stats.products.toLocaleString() }}+</p>
                        <p class="text-slate-600">Products Listed</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-slate-900">100%</p>
                        <p class="text-slate-600">Free to Start</p>
                    </div>
                </div>
            </div>

            <!-- Hero Image/Preview -->
            <div class="mt-16 max-w-5xl mx-auto">
                <div class="relative">
                    <div class="absolute -inset-4 bg-gradient-to-r from-orange-500 to-pink-500 rounded-3xl opacity-20 blur-2xl"></div>
                    <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-200">
                        <div class="bg-slate-100 px-4 py-3 flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            <div class="flex-1 text-center text-sm text-slate-500">bioshop.com/yourshop</div>
                        </div>
                        <div class="p-8 bg-gradient-to-br from-slate-50 to-white">
                            <div class="flex flex-col items-center">
                                <div class="w-24 h-24 bg-gradient-to-br from-orange-400 to-pink-400 rounded-full mb-4"></div>
                                <h3 class="text-xl font-bold text-slate-900">Your Shop Name</h3>
                                <p class="text-slate-500 mb-6">Your bio goes here</p>
                                <div class="grid grid-cols-3 gap-4 w-full max-w-md">
                                    <div class="aspect-square bg-slate-200 rounded-xl animate-pulse"></div>
                                    <div class="aspect-square bg-slate-200 rounded-xl animate-pulse"></div>
                                    <div class="aspect-square bg-slate-200 rounded-xl animate-pulse"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 lg:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
                    Everything You Need to Sell Online
                </h2>
                <p class="text-xl text-slate-600 max-w-2xl mx-auto">
                    Powerful features designed specifically for Bangladeshi sellers and creators.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                    v-for="feature in features"
                    :key="feature.title"
                    class="group p-6 bg-white rounded-2xl border border-slate-200 hover:border-orange-200 hover:shadow-xl transition-all duration-300"
                >
                    <div :class="['w-14 h-14 rounded-xl bg-gradient-to-br flex items-center justify-center mb-4', feature.color]">
                        <component :is="feature.icon" class="w-7 h-7 text-white" />
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-2">{{ feature.title }}</h3>
                    <p class="text-slate-600">{{ feature.description }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-20 lg:py-32 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
                    How It Works
                </h2>
                <p class="text-xl text-slate-600 max-w-2xl mx-auto">
                    Get your online shop up and running in minutes.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div
                    v-for="item in howItWorks"
                    :key="item.step"
                    class="relative text-center"
                >
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-pink-500 rounded-2xl flex items-center justify-center text-2xl font-bold text-white mx-auto mb-4">
                        {{ item.step }}
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ item.title }}</h3>
                    <p class="text-slate-600">{{ item.description }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Preview -->
    <section class="py-20 lg:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
                    Simple, Transparent Pricing
                </h2>
                <p class="text-xl text-slate-600 max-w-2xl mx-auto">
                    Start for free, upgrade when you need more.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
                <div
                    v-for="plan in plans"
                    :key="plan.id"
                    :class="[
                        'relative p-6 rounded-2xl border-2 transition-all duration-300',
                        plan.name === 'Pro'
                            ? 'border-orange-500 bg-orange-50 shadow-xl scale-105'
                            : 'border-slate-200 bg-white hover:border-orange-200'
                    ]"
                >
                    <div v-if="plan.name === 'Pro'" class="absolute -top-3 left-1/2 -translate-x-1/2">
                        <span class="px-3 py-1 bg-orange-500 text-white text-xs font-semibold rounded-full">
                            Most Popular
                        </span>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-2">{{ plan.name }}</h3>
                    <p class="text-3xl font-bold text-slate-900 mb-6">
                        {{ formatPrice(plan.price) }}
                    </p>
                    <ul class="space-y-3 mb-6">
                        <li
                            v-for="feature in plan.features.slice(0, 4)"
                            :key="feature"
                            class="flex items-start gap-2 text-sm text-slate-600"
                        >
                            <Check class="w-5 h-5 text-green-500 flex-shrink-0" />
                            {{ feature }}
                        </li>
                    </ul>
                    <Link href="/register">
                        <Button
                            :class="plan.name === 'Pro' ? 'w-full bg-orange-500 hover:bg-orange-600 text-white' : 'w-full'"
                            :variant="plan.name === 'Pro' ? 'default' : 'outline'"
                        >
                            Get Started
                        </Button>
                    </Link>
                </div>
            </div>

            <div class="text-center mt-8">
                <Link href="/pricing" class="text-orange-600 hover:text-orange-700 font-medium">
                    View full pricing details
                    <ArrowRight class="w-4 h-4 inline ml-1" />
                </Link>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20 lg:py-32 bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4">
                    Loved by Sellers Across Bangladesh
                </h2>
                <p class="text-xl text-slate-400 max-w-2xl mx-auto">
                    See what our users have to say about BioShop.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    v-for="testimonial in testimonials"
                    :key="testimonial.name"
                    class="bg-slate-800 rounded-2xl p-6"
                >
                    <div class="flex items-center gap-1 mb-4">
                        <Star
                            v-for="i in testimonial.rating"
                            :key="i"
                            class="w-5 h-5 text-yellow-400 fill-current"
                        />
                    </div>
                    <p class="text-slate-300 mb-6">"{{ testimonial.content }}"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-pink-400 rounded-full flex items-center justify-center text-white font-bold">
                            {{ testimonial.name.charAt(0) }}
                        </div>
                        <div>
                            <p class="font-semibold">{{ testimonial.name }}</p>
                            <p class="text-sm text-slate-400">{{ testimonial.role }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 lg:py-32 bg-gradient-to-r from-orange-500 to-pink-500">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
                Ready to Grow Your Business?
            </h2>
            <p class="text-xl text-white/90 mb-10">
                Join thousands of sellers who are already using BioShop to reach more customers.
            </p>
            <Link href="/register">
                <Button size="lg" class="bg-white text-orange-600 hover:bg-slate-100 px-10 py-6 text-lg font-semibold">
                    Create Your Free Shop
                    <ArrowRight class="w-5 h-5 ml-2" />
                </Button>
            </Link>
        </div>
    </section>
</template>
