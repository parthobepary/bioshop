<script setup lang="ts">
import { computed, ref } from 'vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import LandingLayout from '@/Layouts/LandingLayout.vue'
import {
    ArrowRight,
    ArrowUpRight,
    BarChart3,
    Check,
    CreditCard,
    Heart,
    Link as LinkIcon,
    Mail,
    MapPin,
    MessageCircle,
    Minus,
    Palette,
    Phone,
    Plus,
    Rocket,
    ShoppingBag,
    Star,
    Target,
    Users,
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

defineProps<Props>()

defineOptions({
    layout: LandingLayout,
})

const page = usePage()
const flashSuccess = computed(() => page.props.flash?.success as string | undefined)

/*
 * Each topic carries its own colour. Hex values live in the data and are bound
 * with :style, so nothing depends on Tailwind generating a dynamic class name.
 */
const BRAND = '#5B3DE5'

const features = [
    {
        icon: LinkIcon,
        color: '#6F5AF0',
        title: 'Link-in-bio',
        description: 'Unlimited social links, videos and custom buttons on one tidy page.',
    },
    {
        icon: ShoppingBag,
        color: '#F59E0B',
        title: 'Product showcase',
        description: 'Galleries, prices, stock and descriptions — no storefront to build.',
    },
    {
        icon: CreditCard,
        color: '#10B981',
        title: 'Local payments',
        description: 'bKash, Nagad, Rocket and bank transfer, with scannable QR codes.',
    },
    {
        icon: MessageCircle,
        color: '#25D366',
        title: 'WhatsApp orders',
        description: 'One tap opens a chat pre-filled with the product the buyer picked.',
    },
    {
        icon: BarChart3,
        color: '#0EA5E9',
        title: 'Analytics',
        description: 'See page views, link clicks and which products get attention.',
    },
    {
        icon: Palette,
        color: '#F43F5E',
        title: 'Your branding',
        description: 'Pick colours and layout so the page looks like your shop, not ours.',
    },
]

const steps = [
    { color: '#6F5AF0', title: 'Claim your name', description: 'Sign up and pick bioshop.com/yourshop.' },
    { color: '#0EA5E9', title: 'Add products', description: 'Upload photos, set prices, write descriptions.' },
    { color: '#10B981', title: 'Connect payments', description: 'Add bKash, Nagad or your bank details.' },
    { color: '#F59E0B', title: 'Share the link', description: 'Drop it in your bio and take orders.' },
]

const testimonials = [
    {
        name: 'Sarah Rahman',
        role: 'Fashion boutique owner',
        color: '#F43F5E',
        content: 'BioShop transformed my Instagram business. I went from DM chaos to organised orders in a week.',
        rating: 5,
    },
    {
        name: 'Karim Ahmed',
        role: 'Handmade crafts seller',
        color: '#0EA5E9',
        content: 'The WhatsApp integration is a game-changer. Customers order directly without any confusion.',
        rating: 5,
    },
    {
        name: 'Fatima Begum',
        role: 'Home baker',
        color: '#10B981',
        content: 'Finally a platform that understands Bangladesh. bKash and Nagad make payment so easy.',
        rating: 5,
    },
]

const values = [
    { icon: Target, color: '#6F5AF0', title: 'Simplicity', description: 'Selling online should not need a developer.' },
    { icon: Heart, color: '#F43F5E', title: 'Local first', description: 'Built around Bangladeshi payments and habits.' },
    { icon: Users, color: '#0EA5E9', title: 'Community', description: 'Sellers learning from other sellers.' },
    { icon: Rocket, color: '#F59E0B', title: 'Momentum', description: 'New features shipped every month.' },
]

const milestones = [
    { year: '2024', color: '#6F5AF0', title: 'BioShop founded', description: 'Started with a mission to empower Bangladeshi sellers.' },
    { year: '2024', color: '#0EA5E9', title: 'First 100 sellers', description: 'Our first hundred shops went live.' },
    { year: '2024', color: '#10B981', title: 'Payments integrated', description: 'bKash, Nagad and Rocket support added.' },
    { year: '2025', color: '#F59E0B', title: 'WhatsApp assistant', description: 'AI-assisted replies for incoming orders.' },
]

const faqs = [
    {
        question: 'Is BioShop really free to start?',
        answer: 'Yes. The Free plan covers a full shop page, links and products. Upgrade only when you outgrow it.',
    },
    {
        question: 'Can I upgrade or cancel anytime?',
        answer: 'Anytime, from the billing page in your dashboard. Changes take effect on your next cycle.',
    },
    {
        question: 'Which payment methods can I accept?',
        answer: 'bKash, Nagad, Rocket and direct bank transfer. Each one can show a QR code on your page.',
    },
    {
        question: 'How fast do you reply to support?',
        answer: 'Within 24 hours on business days. Paid plans also get phone support from 9 AM to 6 PM BST.',
    },
    {
        question: 'Do I need my own domain?',
        answer: 'No. You get bioshop.com/yourname for free, and can connect a custom domain on paid plans.',
    },
]

const contactChannels = [
    { icon: Mail, color: '#6F5AF0', label: 'Email', value: 'hello@bioshop.com', href: 'mailto:hello@bioshop.com' },
    { icon: Phone, color: '#10B981', label: 'Phone', value: '+880 1700-000000', href: 'tel:+8801700000000' },
    { icon: MapPin, color: '#F59E0B', label: 'Office', value: 'Dhaka, Bangladesh', href: null },
]

const paymentBrands = [
    { name: 'bKash', color: '#E2136E' },
    { name: 'Nagad', color: '#EE7623' },
    { name: 'Rocket', color: '#8C3494' },
    { name: 'Bank transfer', color: '#0EA5E9' },
    { name: 'Cash on delivery', color: '#10B981' },
]

const openFaq = ref<number | null>(0)
const toggleFaq = (index: number) => {
    openFaq.value = openFaq.value === index ? null : index
}

const contactForm = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
})

const submitContact = () => {
    contactForm.post('/contact', {
        preserveScroll: true,
        onSuccess: () => contactForm.reset(),
    })
}

/** 10% tint of a hex colour, for icon chips and soft fills. */
const tint = (hex: string, value = 0.12) => {
    const int = parseInt(hex.replace('#', ''), 16)
    return `rgba(${(int >> 16) & 255}, ${(int >> 8) & 255}, ${int & 255}, ${value})`
}

// Plan prices arrive as decimal strings from the API, so coerce before formatting.
const priceOf = (price: number) => Number(price) || 0

const formatPrice = (price: number) => {
    const value = priceOf(price)
    return value === 0 ? 'Free' : `৳${value.toLocaleString('en-US', { maximumFractionDigits: 0 })}`
}
</script>

<template>
    <Head title="BioShop — one link for your products, payments and orders">
        <meta
            name="description"
            content="The all-in-one link-in-bio platform for creators and businesses in Bangladesh. Showcase products, accept bKash and Nagad payments, and take orders on WhatsApp."
        />
    </Head>

    <!-- ============================ Hero ============================ -->
    <section id="top" class="relative overflow-hidden border-b border-line bg-paper">
        <!-- Soft colour wash: three blurred fields, not a gradient sheet -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -left-24 -top-32 h-80 w-80 rounded-full bg-brand-400/25 blur-3xl"></div>
            <div class="absolute -right-20 top-10 h-72 w-72 rounded-full bg-pop-sky/20 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/3 h-64 w-64 rounded-full bg-pop-amber/15 blur-3xl"></div>
        </div>
        <div class="grid-paper pointer-events-none absolute inset-0 opacity-50"></div>

        <div class="shell-wide relative section-lg">
            <div class="grid items-center gap-12 lg:grid-cols-12 lg:gap-10">
                <!-- Copy -->
                <div class="lg:col-span-6">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-medium"
                        :style="{ borderColor: tint(BRAND, 0.25), backgroundColor: tint(BRAND, 0.08), color: BRAND }"
                    >
                        <span class="h-1.5 w-1.5 rounded-full bg-pop-emerald"></span>
                        Built for Bangladesh
                    </span>

                    <h1 class="h-display mt-5">
                        Your products, payments and orders on
                        <span class="whitespace-nowrap text-brand-600">one link.</span>
                    </h1>

                    <p class="lede mt-4 max-w-lg">
                        Create a clean shop page in minutes. Show what you sell, take bKash or Nagad
                        payments, and let customers order straight from WhatsApp.
                    </p>

                    <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                        <Link
                            href="/register"
                            class="btn btn-lg bg-brand-600 text-white shadow-sm transition-colors hover:bg-brand-700"
                        >
                            Get started free
                            <ArrowRight class="h-4 w-4" />
                        </Link>
                        <a href="#features" class="btn-secondary btn-lg">See how it works</a>
                    </div>

                    <!-- Stats -->
                    <dl class="mt-9 grid max-w-md grid-cols-3 gap-px overflow-hidden rounded-xl border border-line bg-line">
                        <div class="bg-white px-4 py-3.5">
                            <dt class="text-[11px] uppercase tracking-[0.12em] text-ink-400">Sellers</dt>
                            <dd class="mt-1 font-display text-xl font-semibold text-brand-600">
                                {{ stats.users.toLocaleString() }}+
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-3.5">
                            <dt class="text-[11px] uppercase tracking-[0.12em] text-ink-400">Products</dt>
                            <dd class="mt-1 font-display text-xl font-semibold text-pop-sky">
                                {{ stats.products.toLocaleString() }}+
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-3.5">
                            <dt class="text-[11px] uppercase tracking-[0.12em] text-ink-400">To start</dt>
                            <dd class="mt-1 font-display text-xl font-semibold text-pop-emerald">Free</dd>
                        </div>
                    </dl>
                </div>

                <!-- Device mockup -->
                <div class="lg:col-span-6">
                    <div class="relative mx-auto max-w-[17.5rem]">
                        <div class="absolute -inset-x-8 -inset-y-6 rounded-[2rem] border border-line bg-white/60 backdrop-blur-sm"></div>

                        <div class="relative overflow-hidden rounded-[1.6rem] border border-ink-200 bg-white shadow-xl">
                            <!-- Browser chrome -->
                            <div class="flex items-center gap-2 border-b border-line bg-paper-subtle px-3 py-2">
                                <span class="h-2 w-2 rounded-full bg-pop-rose/60"></span>
                                <span class="h-2 w-2 rounded-full bg-pop-amber/60"></span>
                                <span class="h-2 w-2 rounded-full bg-pop-emerald/60"></span>
                                <span class="mx-auto rounded bg-white px-2 py-0.5 text-[10px] text-ink-400">
                                    bioshop.com/yourshop
                                </span>
                            </div>

                            <div class="px-4 pb-5 pt-5">
                                <!-- Profile -->
                                <div class="flex flex-col items-center text-center">
                                    <div class="h-12 w-12 rounded-full bg-brand-500"></div>
                                    <p class="mt-2.5 text-sm font-semibold text-ink-900">Your Shop</p>
                                    <p class="text-[11px] text-ink-400">Handmade in Dhaka</p>
                                </div>

                                <!-- Links -->
                                <div class="mt-5 space-y-2">
                                    <div
                                        v-for="(link, i) in [
                                            { color: '#6F5AF0', width: 'w-20' },
                                            { color: '#0EA5E9', width: 'w-28' },
                                        ]"
                                        :key="`link-${i}`"
                                        class="flex items-center gap-2.5 rounded-lg border border-line px-3 py-2.5"
                                    >
                                        <span
                                            class="h-5 w-5 rounded"
                                            :style="{ backgroundColor: tint(link.color, 0.25) }"
                                        ></span>
                                        <span class="h-1.5 rounded-full bg-ink-200" :class="link.width"></span>
                                        <ArrowUpRight class="ml-auto h-3.5 w-3.5 text-ink-300" />
                                    </div>
                                </div>

                                <!-- Products -->
                                <div class="mt-4 grid grid-cols-2 gap-2.5">
                                    <div
                                        v-for="(color, i) in ['#F59E0B', '#F43F5E']"
                                        :key="`product-${i}`"
                                        class="overflow-hidden rounded-lg border border-line"
                                    >
                                        <div class="aspect-square" :style="{ backgroundColor: tint(color, 0.18) }"></div>
                                        <div class="space-y-1 p-2">
                                            <span class="block h-1.5 w-4/5 rounded-full bg-ink-200"></span>
                                            <span
                                                class="block h-1.5 w-1/2 rounded-full"
                                                :style="{ backgroundColor: color }"
                                            ></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Order button -->
                                <div class="mt-4 flex h-9 items-center justify-center gap-2 rounded-lg bg-pop-whatsapp text-[11px] font-medium text-white">
                                    <MessageCircle class="h-3.5 w-3.5" />
                                    Order on WhatsApp
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================== Payments strip ======================== -->
    <section class="border-b border-line bg-paper-subtle">
        <div class="shell-wide flex flex-wrap items-center justify-center gap-x-7 gap-y-3 py-5">
            <span class="text-[11px] uppercase tracking-[0.14em] text-ink-400">Accepts</span>
            <span
                v-for="brandName in paymentBrands"
                :key="brandName.name"
                class="inline-flex items-center gap-1.5 text-[13px] font-medium"
                :style="{ color: brandName.color }"
            >
                <span class="h-1.5 w-1.5 rounded-full" :style="{ backgroundColor: brandName.color }"></span>
                {{ brandName.name }}
            </span>
        </div>
    </section>

    <!-- ========================== Features ========================== -->
    <section id="features" class="section border-b border-line">
        <div class="shell-wide">
            <div class="max-w-xl">
                <span class="eyebrow text-brand-600">Features</span>
                <h2 class="h-section mt-2">Everything you need to sell online</h2>
                <p class="lede mt-3">
                    Six tools that replace a website, a payment page and a spreadsheet of DM orders.
                </p>
            </div>

            <div class="mt-9 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="feature in features"
                    :key="feature.title"
                    class="tinted-card rounded-xl border border-line bg-white p-5"
                    :style="{ '--tint': feature.color }"
                >
                    <span
                        class="flex h-10 w-10 items-center justify-center rounded-lg text-white"
                        :style="{ backgroundColor: feature.color }"
                    >
                        <component :is="feature.icon" class="h-5 w-5" />
                    </span>
                    <h3 class="h-card mt-4">{{ feature.title }}</h3>
                    <p class="mt-1.5 text-[13px] leading-relaxed text-ink-500">{{ feature.description }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================== How it works ======================== -->
    <section id="how" class="section border-b border-line bg-paper-subtle">
        <div class="shell-wide">
            <div class="max-w-xl">
                <span class="eyebrow text-pop-sky">How it works</span>
                <h2 class="h-section mt-2">Live in four steps</h2>
                <p class="lede mt-3">No code, no hosting, no plugins. Most shops are online the same day.</p>
            </div>

            <ol class="mt-9 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <li
                    v-for="(step, index) in steps"
                    :key="step.title"
                    class="relative overflow-hidden rounded-xl border border-line bg-white p-5"
                >
                    <span class="absolute inset-x-0 top-0 h-1" :style="{ backgroundColor: step.color }"></span>
                    <span
                        class="flex h-8 w-8 items-center justify-center rounded-full text-[13px] font-semibold text-white"
                        :style="{ backgroundColor: step.color }"
                    >
                        {{ index + 1 }}
                    </span>
                    <h3 class="h-card mt-3">{{ step.title }}</h3>
                    <p class="mt-1.5 text-[13px] leading-relaxed text-ink-500">{{ step.description }}</p>
                </li>
            </ol>
        </div>
    </section>

    <!-- =========================== Pricing =========================== -->
    <section id="pricing" class="section border-b border-line">
        <div class="shell-wide">
            <div class="max-w-xl">
                <span class="eyebrow text-pop-emerald">Pricing</span>
                <h2 class="h-section mt-2">Start free, upgrade when it pays for itself</h2>
                <p class="lede mt-3">Every plan includes your shop page, links and WhatsApp ordering.</p>
            </div>

            <div class="mt-9 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="plan in plans"
                    :key="plan.id"
                    :class="[
                        'relative flex flex-col rounded-xl border p-5',
                        plan.name === 'Pro'
                            ? 'border-brand-600 bg-brand-50/40 ring-1 ring-brand-600'
                            : 'border-line bg-white',
                    ]"
                >
                    <span
                        v-if="plan.name === 'Pro'"
                        class="absolute -top-2.5 left-5 rounded-full bg-brand-600 px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-white"
                    >
                        Popular
                    </span>

                    <h3 class="h-card">{{ plan.name }}</h3>
                    <p class="mt-2 flex items-baseline gap-1">
                        <span
                            class="font-display text-2xl font-semibold tracking-tight"
                            :class="plan.name === 'Pro' ? 'text-brand-700' : 'text-ink-900'"
                        >
                            {{ formatPrice(plan.price) }}
                        </span>
                        <span v-if="priceOf(plan.price) > 0" class="text-[13px] text-ink-400">/month</span>
                    </p>

                    <ul class="mt-4 space-y-2 border-t border-line pt-4">
                        <li
                            v-for="feature in plan.features.slice(0, 5)"
                            :key="feature"
                            class="flex gap-2 text-[13px] leading-relaxed text-ink-600"
                        >
                            <Check class="mt-0.5 h-3.5 w-3.5 shrink-0 text-pop-emerald" />
                            <span>{{ feature }}</span>
                        </li>
                    </ul>

                    <Link
                        href="/register"
                        :class="[
                            'mt-5 w-full',
                            plan.name === 'Pro'
                                ? 'btn bg-brand-600 text-white hover:bg-brand-700'
                                : 'btn-secondary',
                        ]"
                    >
                        Choose {{ plan.name }}
                    </Link>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================== Testimonials ========================== -->
    <section id="reviews" class="section border-b border-line bg-paper-subtle">
        <div class="shell-wide">
            <div class="max-w-xl">
                <span class="eyebrow text-pop-rose">Reviews</span>
                <h2 class="h-section mt-2">Sellers who stopped losing orders in the DMs</h2>
            </div>

            <div class="mt-9 grid grid-cols-1 gap-5 md:grid-cols-3">
                <figure
                    v-for="testimonial in testimonials"
                    :key="testimonial.name"
                    class="flex flex-col overflow-hidden rounded-xl border border-line bg-white p-5"
                >
                    <div class="flex gap-0.5">
                        <Star
                            v-for="i in testimonial.rating"
                            :key="i"
                            class="h-3.5 w-3.5 fill-pop-amber text-pop-amber"
                        />
                    </div>
                    <blockquote class="mt-3 flex-1 text-[14px] leading-relaxed text-ink-700">
                        “{{ testimonial.content }}”
                    </blockquote>
                    <figcaption class="mt-5 flex items-center gap-3 border-t border-line pt-4">
                        <span
                            class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-semibold text-white"
                            :style="{ backgroundColor: testimonial.color }"
                        >
                            {{ testimonial.name.charAt(0) }}
                        </span>
                        <span>
                            <span class="block text-[13px] font-medium text-ink-900">{{ testimonial.name }}</span>
                            <span class="block text-[12px] text-ink-400">{{ testimonial.role }}</span>
                        </span>
                    </figcaption>
                </figure>
            </div>
        </div>
    </section>

    <!-- ============================ About ============================ -->
    <section id="about" class="section border-b border-line">
        <div class="shell-wide">
            <div class="grid gap-10 lg:grid-cols-12">
                <div class="lg:col-span-5">
                    <span class="eyebrow text-brand-600">About</span>
                    <h2 class="h-section mt-2">We build for the seller with one phone</h2>
                    <p class="lede mt-3">
                        BioShop started because thousands of Bangladeshi sellers run real businesses out
                        of an Instagram bio and a WhatsApp inbox. They deserve better tools than a
                        screenshot of a bKash number.
                    </p>

                    <div class="mt-6 grid grid-cols-2 gap-x-6 gap-y-5">
                        <div v-for="value in values" :key="value.title">
                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-lg"
                                :style="{ backgroundColor: tint(value.color), color: value.color }"
                            >
                                <component :is="value.icon" class="h-4 w-4" />
                            </span>
                            <h3 class="mt-2.5 text-[13px] font-semibold text-ink-900">{{ value.title }}</h3>
                            <p class="mt-1 text-[12px] leading-relaxed text-ink-500">{{ value.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="lg:col-span-6 lg:col-start-7">
                    <ol class="relative border-l border-line pl-6">
                        <li v-for="milestone in milestones" :key="milestone.title" class="pb-7 last:pb-0">
                            <span
                                class="absolute -left-[4.5px] mt-1.5 h-2 w-2 rounded-full"
                                :style="{ backgroundColor: milestone.color }"
                            ></span>
                            <span class="font-mono text-[11px]" :style="{ color: milestone.color }">
                                {{ milestone.year }}
                            </span>
                            <h3 class="mt-0.5 text-[14px] font-semibold text-ink-900">{{ milestone.title }}</h3>
                            <p class="mt-1 text-[13px] leading-relaxed text-ink-500">{{ milestone.description }}</p>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================= FAQ ============================= -->
    <section id="faq" class="section border-b border-line bg-paper-subtle">
        <div class="shell-wide">
            <div class="grid gap-8 lg:grid-cols-12">
                <div class="lg:col-span-4">
                    <span class="eyebrow text-pop-amber">FAQ</span>
                    <h2 class="h-section mt-2">Questions, answered</h2>
                    <p class="lede mt-3">
                        Still unsure?
                        <a href="#contact" class="font-medium text-brand-600 underline-offset-4 hover:underline">
                            Send us a message
                        </a>.
                    </p>
                </div>

                <div class="lg:col-span-7 lg:col-start-6">
                    <div class="divide-y divide-line overflow-hidden rounded-xl border border-line bg-white">
                        <div v-for="(faq, index) in faqs" :key="faq.question">
                            <button
                                type="button"
                                class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left"
                                :aria-expanded="openFaq === index"
                                @click="toggleFaq(index)"
                            >
                                <span
                                    class="text-[14px] font-medium"
                                    :class="openFaq === index ? 'text-brand-700' : 'text-ink-900'"
                                >
                                    {{ faq.question }}
                                </span>
                                <component
                                    :is="openFaq === index ? Minus : Plus"
                                    class="h-4 w-4 shrink-0"
                                    :class="openFaq === index ? 'text-brand-600' : 'text-ink-400'"
                                />
                            </button>
                            <p v-if="openFaq === index" class="px-5 pb-4 text-[13px] leading-relaxed text-ink-500">
                                {{ faq.answer }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================== Contact =========================== -->
    <section id="contact" class="section border-b border-line">
        <div class="shell-wide">
            <div class="grid gap-10 lg:grid-cols-12">
                <div class="lg:col-span-5">
                    <span class="eyebrow text-pop-sky">Contact</span>
                    <h2 class="h-section mt-2">Talk to a human</h2>
                    <p class="lede mt-3">
                        Questions about plans, payments or migrating your shop? We reply within one
                        business day.
                    </p>

                    <dl class="mt-7 space-y-4">
                        <div v-for="channel in contactChannels" :key="channel.label" class="flex items-start gap-3">
                            <span
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg"
                                :style="{ backgroundColor: tint(channel.color), color: channel.color }"
                            >
                                <component :is="channel.icon" class="h-4 w-4" />
                            </span>
                            <div>
                                <dt class="text-[11px] uppercase tracking-[0.12em] text-ink-400">
                                    {{ channel.label }}
                                </dt>
                                <dd class="text-[14px] text-ink-900">
                                    <a v-if="channel.href" :href="channel.href" class="hover:underline">
                                        {{ channel.value }}
                                    </a>
                                    <span v-else>{{ channel.value }}</span>
                                </dd>
                            </div>
                        </div>
                    </dl>
                </div>

                <div class="lg:col-span-6 lg:col-start-7">
                    <form class="card p-6" @submit.prevent="submitContact">
                        <p
                            v-if="flashSuccess"
                            class="mb-5 rounded-lg border border-success-100 bg-success-50 px-4 py-3 text-[13px] text-success-600"
                        >
                            {{ flashSuccess }}
                        </p>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="contact-name" class="field-label">Name</label>
                                <input
                                    id="contact-name"
                                    v-model="contactForm.name"
                                    type="text"
                                    required
                                    class="field"
                                    placeholder="Your name"
                                />
                                <p v-if="contactForm.errors.name" class="field-error">{{ contactForm.errors.name }}</p>
                            </div>
                            <div>
                                <label for="contact-email" class="field-label">Email</label>
                                <input
                                    id="contact-email"
                                    v-model="contactForm.email"
                                    type="email"
                                    required
                                    class="field"
                                    placeholder="you@example.com"
                                />
                                <p v-if="contactForm.errors.email" class="field-error">{{ contactForm.errors.email }}</p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="contact-subject" class="field-label">Subject</label>
                            <input
                                id="contact-subject"
                                v-model="contactForm.subject"
                                type="text"
                                required
                                class="field"
                                placeholder="What is this about?"
                            />
                            <p v-if="contactForm.errors.subject" class="field-error">{{ contactForm.errors.subject }}</p>
                        </div>

                        <div class="mt-4">
                            <label for="contact-message" class="field-label">Message</label>
                            <textarea
                                id="contact-message"
                                v-model="contactForm.message"
                                rows="4"
                                required
                                class="field resize-none"
                                placeholder="Tell us what you need"
                            ></textarea>
                            <p v-if="contactForm.errors.message" class="field-error">{{ contactForm.errors.message }}</p>
                        </div>

                        <button
                            type="submit"
                            class="btn mt-5 w-full bg-brand-600 text-white hover:bg-brand-700"
                            :disabled="contactForm.processing"
                        >
                            {{ contactForm.processing ? 'Sending…' : 'Send message' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================= CTA ============================= -->
    <section class="relative overflow-hidden bg-brand-600">
        <div class="pointer-events-none absolute -right-16 -top-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 left-10 h-64 w-64 rounded-full bg-pop-sky/25 blur-3xl"></div>

        <div class="shell-wide relative flex flex-col items-center gap-6 py-10 text-center sm:flex-row sm:justify-between sm:text-left">
            <div>
                <h2 class="font-display text-xl font-semibold tracking-tight text-white sm:text-2xl">
                    Ready to take your first order?
                </h2>
                <p class="mt-1.5 text-[14px] text-brand-100">
                    Free forever to start. No card, no setup fee.
                </p>
            </div>
            <Link href="/register" class="btn btn-lg shrink-0 bg-white text-brand-700 hover:bg-brand-50">
                Create your shop
                <ArrowRight class="h-4 w-4" />
            </Link>
        </div>
    </section>
</template>
