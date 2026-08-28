<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import LandingLayout from '@/Layouts/LandingLayout.vue'
import { Button } from '@/Components/ui/button'
import { Check, X, HelpCircle, ArrowRight } from 'lucide-vue-next'

interface Plan {
    id: number
    name: string
    price: number
    features: string[]
    max_products: number
    max_links: number
    analytics_enabled: boolean
    custom_domain: boolean
    priority_support: boolean
}

interface Props {
    plans: Plan[]
}

const props = defineProps<Props>()

defineOptions({
    layout: LandingLayout,
})

const formatPrice = (price: number) => {
    if (price === 0) return 'Free'
    return `৳${price}`
}

const comparisonFeatures = [
    { name: 'Products', key: 'products' },
    { name: 'Links', key: 'links' },
    { name: 'Analytics', key: 'analytics' },
    { name: 'Custom Theme', key: 'theme' },
    { name: 'Payment Methods', key: 'payments' },
    { name: 'WhatsApp', key: 'whatsapp' },
    { name: 'Categories', key: 'categories' },
    { name: 'QR Code', key: 'qr' },
    { name: 'Priority Support', key: 'support' },
    { name: 'Custom Domain', key: 'domain' },
    { name: 'Remove Branding', key: 'branding' },
    { name: 'API Access', key: 'api' },
]

const getFeatureValue = (plan: Plan, key: string): string | boolean => {
    switch (key) {
        case 'products':
            return plan.max_products === -1 ? 'Unlimited' : String(plan.max_products)
        case 'links':
            return plan.max_links === -1 ? 'Unlimited' : String(plan.max_links)
        case 'analytics':
            return plan.analytics_enabled
        case 'theme': return true
        case 'payments': return true
        case 'whatsapp': return true
        case 'categories': return plan.name !== 'Free'
        case 'qr': return true
        case 'support': return plan.priority_support
        case 'domain': return plan.custom_domain
        case 'branding': return plan.name === 'Pro' || plan.name === 'Business'
        case 'api': return plan.name === 'Business'
        default: return false
    }
}

const faqs = [
    { question: 'Can I upgrade anytime?', answer: 'Yes!' },
    { question: 'What payment methods?', answer: 'bKash, Nagad, Rocket, bank.' },
]
</script>

<template>
    <Head title="Pricing - BioShop" />
    <section class="py-20 bg-gradient-to-br from-slate-50 to-orange-50">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-6">Simple, Transparent Pricing</h1>
        </div>
    </section>
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div v-for="plan in plans" :key="plan.id" class="p-8 rounded-2xl border-2">
                    <h3 class="text-2xl font-bold mb-2">{{ plan.name }}</h3>
                    <div class="text-4xl font-bold">{{ formatPrice(plan.price) }}</div>
                    <ul class="mt-4 space-y-2">
                        <li v-for="f in plan.features" :key="f" class="flex gap-2">
                            <Check class="w-5 h-5 text-green-500" /><span>{{ f }}</span>
                        </li>
                    </ul>
                    <Link href="/register"><Button class="w-full mt-4">Get Started</Button></Link>
                </div>
            </div>
        </div>
    </section>
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Compare Plans</h2>
            <table class="w-full bg-white rounded-xl">
                <thead><tr><th class="p-4 text-left">Feature</th><th v-for="p in plans" :key="p.id" class="p-4">{{ p.name }}</th></tr></thead>
                <tbody>
                    <tr v-for="f in comparisonFeatures" :key="f.key">
                        <td class="p-4">{{ f.name }}</td>
                        <td v-for="p in plans" :key="p.id" class="p-4 text-center">
                            <Check v-if="getFeatureValue(p, f.key) === true" class="w-5 h-5 text-green-500 mx-auto" />
                            <X v-else-if="getFeatureValue(p, f.key) === false" class="w-5 h-5 text-slate-300 mx-auto" />
                            <span v-else>{{ getFeatureValue(p, f.key) }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>
