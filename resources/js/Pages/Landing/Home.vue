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
    Check,
    ArrowRight,
    Star,
    Sparkles,
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
    },
    {
        icon: ShoppingBag,
        title: 'Product Showcase',
        description: 'Display your products with beautiful galleries and detailed descriptions.',
    },
    {
        icon: CreditCard,
        title: 'Payment Methods',
        description: 'Accept bKash, Nagad, Rocket, and bank transfers with QR codes.',
    },
    {
        icon: MessageCircle,
        title: 'WhatsApp Integration',
        description: 'One-click WhatsApp ordering with pre-filled product messages.',
    },
    {
        icon: BarChart3,
        title: 'Analytics Dashboard',
        description: 'Track page views, link clicks, and product engagement.',
    },
    {
        icon: Palette,
        title: 'Customizable Themes',
        description: 'Choose colors and styles that match your brand identity.',
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
    <section class="relative overflow-hidden bg-gradient-to-b from-indigo-50/70 via-white to-white">
        <!-- Subtle dot grid -->
        <div class="pointer-events-none absolute inset-0 opacity-60" style="background-image: radial-gradient(circle at 1px 1px, rgb(99 102 241 / 0.12) 1px, transparent 0); background-size: 40px 40px;"></div>
        <!-- Soft glow -->
        <div class="pointer-events-none absolute -top-24 left-1/2 h-72 w-[36rem] -translate-x-1/2 rounded-full bg-indigo-300/30 blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
            <div class="mx-auto max-w-4xl text-center">
                <!-- Badge -->
                <div class="mb-8 inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-indigo-50 px-4 py-1.5 text-sm font-medium text-indigo-700">
                    <Sparkles class="h-4 w-4" />
                    Built for Bangladesh
                </div>

                <!-- Headline -->
                <h1 class="text-4xl font-extrabold leading-[1.1] tracking-tight text-slate-900 sm:text-5xl lg:text-6xl">
                    Your Products,
                    <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        One Beautiful Link
                    </span>
                </h1>

                <!-- Subheadline -->
                <p class="mx-auto mb-10 mt-6 max-w-2xl text-lg text-slate-600 sm:text-xl">
                    Create a stunning link-in-bio page. Showcase your products, accept bKash/Nagad payments, and let customers order via WhatsApp.
                </p>

                <!-- CTA Buttons -->
                <div class="mb-14 flex flex-col items-center justify-center gap-4 sm:flex-row">
                    <Link href="/register">
                        <Button size="lg" class="rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 px-8 py-6 text-base font-semibold text-white shadow-lg shadow-indigo-500/25 transition-all hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-500/30">
                            Get Started Free
                            <ArrowRight class="ml-2 h-5 w-5" />
                        </Button>
                    </Link>
                    <Link href="/features">
                        <Button size="lg" variant="outline" class="rounded-xl border-slate-200 bg-white px-8 py-6 text-base font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                            See Features
                        </Button>
                    </Link>
                </div>

                <!-- Stats -->
                <div class="flex flex-wrap items-center justify-center gap-x-10 gap-y-6 sm:gap-x-16">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-slate-900">{{ stats.users.toLocaleString() }}+</p>
                        <p class="mt-1 text-sm text-slate-500">Active Sellers</p>
                    </div>
                    <div class="hidden h-10 w-px bg-slate-200 sm:block"></div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-slate-900">{{ stats.products.toLocaleString() }}+</p>
                        <p class="mt-1 text-sm text-slate-500">Products Listed</p>
                    </div>
                    <div class="hidden h-10 w-px bg-slate-200 sm:block"></div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-slate-900">100%</p>
                        <p class="mt-1 text-sm text-slate-500">Free to Start</p>
                    </div>
                </div>
            </div>

            <!-- Hero Preview -->
            <div class="mx-auto mt-16 max-w-5xl">
                <div class="relative">
                    <div class="absolute -inset-4 rounded-3xl bg-gradient-to-r from-indigo-500 to-purple-500 opacity-10 blur-2xl"></div>
                    <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/10">
                        <div class="flex items-center gap-2 border-b border-slate-100 bg-slate-50 px-4 py-3">
                            <div class="h-3 w-3 rounded-full bg-slate-300"></div>
                            <div class="h-3 w-3 rounded-full bg-slate-300"></div>
                            <div class="h-3 w-3 rounded-full bg-slate-300"></div>
                            <div class="flex-1 text-center text-sm text-slate-400">bioshop.test/yourshop</div>
                        </div>
                        <div class="bg-gradient-to-br from-indigo-50/60 to-white p-8">
                            <div class="flex flex-col items-center">
                                <div class="relative mb-4">
                                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-indigo-400 to-purple-400 shadow-lg ring-4 ring-white"></div>
                                    <div class="absolute bottom-0.5 right-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-white shadow">
                                        <span class="h-3 w-3 rounded-full bg-emerald-400"></span>
                                    </div>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900">Your Shop Name</h3>
                                <p class="mb-5 text-sm text-slate-500">Your bio goes here</p>

                                <!-- Link pills -->
                                <div class="mb-5 w-full max-w-sm space-y-2.5">
                                    <div
                                        v-for="i in 2"
                                        :key="`pill-${i}`"
                                        class="flex items-center gap-3 rounded-xl border border-slate-100 bg-white p-2.5 shadow-sm"
                                    >
                                        <div class="h-7 w-7 flex-shrink-0 rounded-lg bg-gradient-to-br from-indigo-400 to-purple-400"></div>
                                        <div class="h-2.5 rounded-full bg-slate-200" :class="i === 1 ? 'w-2/5' : 'w-3/5'"></div>
                                        <div class="ml-auto h-4 w-4 flex-shrink-0 rounded-full bg-slate-100"></div>
                                    </div>
                                </div>

                                <!-- Product cards -->
                                <div class="grid w-full max-w-sm grid-cols-3 gap-3">
                                    <div
                                        v-for="i in 3"
                                        :key="`prod-${i}`"
                                        class="overflow-hidden rounded-xl border border-slate-100 bg-white shadow-sm"
                                    >
                                        <div class="aspect-square bg-gradient-to-br from-indigo-100 to-purple-100"></div>
                                        <div class="space-y-1.5 p-2">
                                            <div class="h-2 w-3/4 rounded-full bg-slate-200"></div>
                                            <div class="h-2 w-1/2 rounded-full bg-indigo-300"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="bg-white py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-16 text-center">
                <span class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Features</span>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                    Everything You Need to Sell Online
                </h2>
                <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-600">
                    Powerful features designed specifically for Bangladeshi sellers and creators.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="feature in features"
                    :key="feature.title"
                    class="group rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-indigo-200 hover:shadow-xl hover:shadow-slate-200/60"
                >
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 ring-1 ring-indigo-100 transition-colors group-hover:bg-gradient-to-br group-hover:from-indigo-500 group-hover:to-purple-500 group-hover:text-white group-hover:ring-transparent">
                        <component :is="feature.icon" class="h-6 w-6" />
                    </div>
                    <h3 class="mb-2 text-lg font-semibold text-slate-900">{{ feature.title }}</h3>
                    <p class="text-slate-600">{{ feature.description }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="bg-slate-50 py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-16 text-center">
                <span class="text-sm font-semibold uppercase tracking-wider text-indigo-600">How it works</span>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                    Up and Running in Minutes
                </h2>
                <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-600">
                    Get your online shop live in four simple steps.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="item in howItWorks"
                    :key="item.step"
                    class="relative rounded-2xl border border-slate-200/70 bg-white p-6 text-center shadow-sm"
                >
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-500 text-xl font-bold text-white shadow-sm">
                        {{ item.step }}
                    </div>
                    <h3 class="mb-2 text-lg font-semibold text-slate-900">{{ item.title }}</h3>
                    <p class="text-sm text-slate-600">{{ item.description }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Preview -->
    <section class="bg-white py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-16 text-center">
                <span class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Pricing</span>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                    Simple, Transparent Pricing
                </h2>
                <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-600">
                    Start for free, upgrade when you need more.
                </p>
            </div>

            <div class="mx-auto grid max-w-6xl grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="plan in plans"
                    :key="plan.id"
                    :class="[
                        'relative rounded-3xl border p-6 transition-all duration-300',
                        plan.name === 'Pro'
                            ? 'border-2 border-indigo-500 bg-white shadow-xl shadow-indigo-500/10 lg:scale-105'
                            : 'border-slate-200/70 bg-white shadow-sm hover:-translate-y-1 hover:shadow-lg'
                    ]"
                >
                    <div v-if="plan.name === 'Pro'" class="absolute -top-3 left-1/2 -translate-x-1/2">
                        <span class="rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 px-3 py-1 text-xs font-semibold text-white shadow-sm">
                            Most Popular
                        </span>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold text-slate-900">{{ plan.name }}</h3>
                    <p class="mb-6 text-3xl font-bold tracking-tight text-slate-900">
                        {{ formatPrice(plan.price) }}
                    </p>
                    <ul class="mb-6 space-y-3">
                        <li
                            v-for="feature in plan.features.slice(0, 4)"
                            :key="feature"
                            class="flex items-start gap-2 text-sm text-slate-600"
                        >
                            <Check class="h-5 w-5 flex-shrink-0 text-indigo-500" />
                            {{ feature }}
                        </li>
                    </ul>
                    <Link href="/register">
                        <Button
                            :class="plan.name === 'Pro'
                                ? 'w-full rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md'
                                : 'w-full rounded-xl border-slate-200 font-semibold text-slate-700 hover:bg-slate-50'"
                            :variant="plan.name === 'Pro' ? 'default' : 'outline'"
                        >
                            Get Started
                        </Button>
                    </Link>
                </div>
            </div>

            <div class="mt-10 text-center">
                <Link href="/pricing" class="inline-flex items-center gap-1 font-semibold text-indigo-600 hover:text-indigo-700">
                    View full pricing details
                    <ArrowRight class="h-4 w-4" />
                </Link>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="bg-slate-50 py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-16 text-center">
                <span class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Testimonials</span>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                    Loved by Sellers Across Bangladesh
                </h2>
                <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-600">
                    See what our users have to say about BioShop.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div
                    v-for="testimonial in testimonials"
                    :key="testimonial.name"
                    class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm"
                >
                    <div class="mb-4 flex items-center gap-1">
                        <Star
                            v-for="i in testimonial.rating"
                            :key="i"
                            class="h-5 w-5 fill-current text-indigo-500"
                        />
                    </div>
                    <p class="mb-6 leading-relaxed text-slate-700">"{{ testimonial.content }}"</p>
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 font-bold text-white">
                            {{ testimonial.name.charAt(0) }}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">{{ testimonial.name }}</p>
                            <p class="text-sm text-slate-500">{{ testimonial.role }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-white py-20 lg:py-28">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-500 to-purple-600 px-6 py-16 text-center shadow-xl shadow-indigo-500/20 sm:px-12">
                <!-- decorative glow -->
                <div class="pointer-events-none absolute -right-10 -top-16 h-52 w-52 rounded-full bg-white/10 blur-2xl"></div>
                <div class="pointer-events-none absolute -bottom-16 left-10 h-52 w-52 rounded-full bg-fuchsia-400/20 blur-2xl"></div>

                <h2 class="relative text-3xl font-bold tracking-tight text-white sm:text-4xl">
                    Ready to Grow Your Business?
                </h2>
                <p class="relative mx-auto mt-4 max-w-2xl text-lg text-indigo-100">
                    Join thousands of sellers who are already using BioShop to reach more customers.
                </p>
                <div class="relative mt-10 flex justify-center">
                    <Link href="/register">
                        <Button size="lg" class="rounded-xl bg-white px-10 py-6 text-base font-semibold text-indigo-600 shadow-lg transition-all hover:-translate-y-0.5 hover:bg-slate-50">
                            Create Your Free Shop
                            <ArrowRight class="ml-2 h-5 w-5" />
                        </Button>
                    </Link>
                </div>
            </div>
        </div>
    </section>
</template>
