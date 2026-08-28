<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import LandingLayout from '@/Layouts/LandingLayout.vue'
import { Button } from '@/Components/ui/button'
import {
    Target,
    Heart,
    Users,
    Rocket,
    Globe,
    Award,
    ArrowRight,
} from 'lucide-vue-next'

defineOptions({
    layout: LandingLayout,
})

const values = [
    {
        icon: Target,
        title: 'Simplicity',
        description: 'We believe selling online should be simple. No complex setups, no technical knowledge required.',
    },
    {
        icon: Heart,
        title: 'Local First',
        description: 'Built specifically for Bangladesh with local payment methods, language, and business practices.',
    },
    {
        icon: Users,
        title: 'Community',
        description: 'We are building a community of sellers who support and learn from each other.',
    },
    {
        icon: Rocket,
        title: 'Innovation',
        description: 'Constantly improving and adding new features to help our sellers succeed.',
    },
]

const milestones = [
    { year: '2024', title: 'BioShop Founded', description: 'Started with a mission to empower Bangladeshi sellers.' },
    { year: '2024', title: 'First 100 Users', description: 'Reached our first milestone of 100 active sellers.' },
    { year: '2024', title: 'Payment Integration', description: 'Added bKash, Nagad, and Rocket integration.' },
    { year: '2025', title: 'WhatsApp AI', description: 'Launching AI-powered WhatsApp assistant for sellers.' },
]

const team = [
    { name: 'Founder', role: 'CEO & Developer', image: null },
    { name: 'Co-Founder', role: 'Product & Design', image: null },
    { name: 'Team Member', role: 'Customer Success', image: null },
]
</script>

<template>
    <Head title="About Us - BioShop">
        <meta name="description" content="Learn about BioShop - the link-in-bio platform built for Bangladeshi sellers and creators." />
    </Head>

    <!-- Hero -->
    <section class="py-20 bg-gradient-to-br from-slate-50 to-orange-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl sm:text-5xl font-bold text-slate-900 mb-6">
                    Empowering Bangladeshi Sellers to
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-pink-500">
                        Succeed Online
                    </span>
                </h1>
                <p class="text-xl text-slate-600">
                    BioShop was born from a simple idea: every small business owner and creator in Bangladesh deserves an easy way to sell online.
                </p>
            </div>
        </div>
    </section>

    <!-- Story -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900 mb-6">Our Story</h2>
                    <div class="space-y-4 text-slate-600">
                        <p>
                            It started with a problem we saw everywhere: talented Bangladeshi sellers struggling to manage orders through Instagram DMs, losing customers due to confusing payment processes, and spending hours answering the same questions.
                        </p>
                        <p>
                            We asked ourselves: why isn't there a simple solution built specifically for Bangladesh? One that understands bKash and Nagad, works perfectly with WhatsApp, and doesn't require any technical skills?
                        </p>
                        <p>
                            That's when BioShop was born. Our mission is to give every seller in Bangladesh—from home bakers to fashion boutiques to handmade crafts makers—the tools they need to succeed online.
                        </p>
                        <p>
                            Today, we're proud to serve thousands of sellers across Bangladesh, helping them grow their businesses and reach more customers than ever before.
                        </p>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -inset-4 bg-gradient-to-r from-orange-500 to-pink-500 rounded-3xl opacity-20 blur-2xl"></div>
                    <div class="relative bg-slate-100 rounded-2xl aspect-square flex items-center justify-center">
                        <Globe class="w-32 h-32 text-slate-300" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Our Values</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">
                    These principles guide everything we do at BioShop.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div
                    v-for="value in values"
                    :key="value.title"
                    class="bg-white rounded-2xl p-6 border border-slate-200 text-center"
                >
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-pink-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <component :is="value.icon" class="w-7 h-7 text-white" />
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-2">{{ value.title }}</h3>
                    <p class="text-slate-600">{{ value.description }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Our Journey</h2>
                <p class="text-slate-600">Key milestones in our story.</p>
            </div>

            <div class="relative">
                <!-- Line -->
                <div class="absolute left-1/2 top-0 bottom-0 w-0.5 bg-orange-200 -translate-x-1/2"></div>

                <div class="space-y-12">
                    <div
                        v-for="(milestone, index) in milestones"
                        :key="milestone.title"
                        :class="['relative flex items-center gap-8', index % 2 === 0 ? 'flex-row' : 'flex-row-reverse']"
                    >
                        <div class="flex-1 text-right" :class="index % 2 === 1 ? 'text-left' : ''">
                            <div class="bg-white rounded-xl p-6 shadow-lg border border-slate-200 inline-block">
                                <span class="text-orange-500 font-semibold">{{ milestone.year }}</span>
                                <h3 class="text-lg font-semibold text-slate-900 mt-1">{{ milestone.title }}</h3>
                                <p class="text-slate-600 mt-1">{{ milestone.description }}</p>
                            </div>
                        </div>
                        <div class="absolute left-1/2 -translate-x-1/2 w-4 h-4 bg-orange-500 rounded-full border-4 border-white shadow"></div>
                        <div class="flex-1"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Our Team</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">
                    A small but passionate team dedicated to helping you succeed.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-3xl mx-auto">
                <div
                    v-for="member in team"
                    :key="member.name"
                    class="bg-white rounded-2xl p-6 text-center border border-slate-200"
                >
                    <div class="w-24 h-24 bg-gradient-to-br from-orange-400 to-pink-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white text-2xl font-bold">{{ member.name.charAt(0) }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">{{ member.name }}</h3>
                    <p class="text-slate-600">{{ member.role }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-20 bg-gradient-to-r from-orange-500 to-pink-500">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-6">
                Join Our Growing Community
            </h2>
            <p class="text-xl text-white/90 mb-8">
                Become one of thousands of successful sellers using BioShop.
            </p>
            <Link href="/register">
                <Button size="lg" class="bg-white text-orange-600 hover:bg-slate-100 px-8">
                    Get Started Free
                    <ArrowRight class="w-5 h-5 ml-2" />
                </Button>
            </Link>
        </div>
    </section>
</template>
