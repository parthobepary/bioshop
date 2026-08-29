<script setup lang="ts">
import { computed } from 'vue'
import { Check, Crown, Zap, Star, Building2 } from 'lucide-vue-next'

interface Plan {
    id: number
    name: string
    slug: string
    price: number
    billing_period: string
    max_products: number
    max_links: number
    features: string[]
    analytics_enabled: boolean
    custom_domain: boolean
    whatsapp_ai: boolean
    priority_support: boolean
}

interface Props {
    plan: Plan
    currentPlanSlug: string | null
    popular?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    popular: false,
})

const emit = defineEmits<{
    select: [plan: Plan]
}>()

const isCurrentPlan = computed(() => props.currentPlanSlug === props.plan.slug)
const isFree = computed(() => props.plan.price === 0)

const formatPrice = (price: number) => {
    if (price === 0) return 'Free'
    return new Intl.NumberFormat('en-BD', {
        style: 'currency',
        currency: 'BDT',
        minimumFractionDigits: 0,
    }).format(price).replace('BDT', '৳')
}

const getIcon = computed(() => {
    switch (props.plan.slug) {
        case 'free': return Zap
        case 'starter': return Star
        case 'pro': return Crown
        case 'business': return Building2
        default: return Zap
    }
})

const canUpgrade = computed(() => {
    if (!props.currentPlanSlug) return props.plan.price > 0
    const planOrder = ['free', 'starter', 'pro', 'business']
    const currentIndex = planOrder.indexOf(props.currentPlanSlug)
    const thisIndex = planOrder.indexOf(props.plan.slug)
    return thisIndex > currentIndex
})

const canDowngrade = computed(() => {
    if (!props.currentPlanSlug) return false
    const planOrder = ['free', 'starter', 'pro', 'business']
    const currentIndex = planOrder.indexOf(props.currentPlanSlug)
    const thisIndex = planOrder.indexOf(props.plan.slug)
    return thisIndex < currentIndex && thisIndex > 0
})
</script>

<template>
    <div
        :class="[
            'relative rounded-3xl border bg-white p-8 shadow-sm transition-all',
            popular
                ? 'border-2 border-indigo-500 shadow-lg shadow-indigo-500/20'
                : isCurrentPlan
                    ? 'border-emerald-300 bg-emerald-50/40'
                    : 'border-slate-200/70 hover:-translate-y-0.5 hover:shadow-md'
        ]"
    >
        <!-- Popular Badge -->
        <div
            v-if="popular"
            class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-gradient-to-r from-amber-400 to-orange-400 px-4 py-1 text-xs font-bold text-slate-900 shadow"
        >
            MOST POPULAR
        </div>

        <!-- Current Plan Badge -->
        <div
            v-if="isCurrentPlan"
            class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-emerald-500 px-4 py-1 text-xs font-bold text-white shadow"
        >
            Current Plan
        </div>

        <!-- Header -->
        <div class="mb-6 text-center">
            <div
                :class="[
                    'mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl',
                    popular ? 'bg-gradient-to-br from-indigo-500 to-purple-500 text-white shadow-sm' : 'bg-slate-100'
                ]"
            >
                <component
                    :is="getIcon"
                    :class="['h-7 w-7', popular ? 'text-white' : 'text-slate-600']"
                />
            </div>
            <h3 class="text-xl font-bold tracking-tight text-slate-900">{{ plan.name }}</h3>
            <div class="mt-3">
                <span class="text-4xl font-bold text-slate-900">{{ formatPrice(plan.price) }}</span>
                <span v-if="!isFree" class="text-slate-500">/month</span>
            </div>
        </div>

        <!-- Limits -->
        <div class="mb-6 space-y-3">
            <div class="flex items-center justify-between text-sm">
                <span class="text-slate-500">Products</span>
                <span class="font-semibold text-slate-900">
                    {{ plan.max_products === -1 ? 'Unlimited' : plan.max_products }}
                </span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="text-slate-500">Links</span>
                <span class="font-semibold text-slate-900">
                    {{ plan.max_links === -1 ? 'Unlimited' : plan.max_links }}
                </span>
            </div>
        </div>

        <!-- Features -->
        <ul class="mb-6 space-y-3">
            <li
                v-for="feature in plan.features"
                :key="feature"
                class="flex items-start gap-2 text-sm"
            >
                <Check class="mt-0.5 h-4 w-4 flex-shrink-0 text-emerald-500" />
                <span class="text-slate-600">{{ feature }}</span>
            </li>
        </ul>

        <!-- Action Button -->
        <button
            v-if="isCurrentPlan"
            disabled
            class="w-full rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-400"
        >
            Current Plan
        </button>
        <button
            v-else-if="canUpgrade"
            :class="popular
                ? 'w-full rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md'
                : 'w-full rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50'"
            @click="emit('select', plan)"
        >
            Upgrade to {{ plan.name }}
        </button>
        <button
            v-else-if="canDowngrade"
            class="w-full rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50"
            @click="emit('select', plan)"
        >
            Downgrade to {{ plan.name }}
        </button>
        <button
            v-else-if="isFree"
            disabled
            class="w-full rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-400"
        >
            Free Forever
        </button>
    </div>
</template>
