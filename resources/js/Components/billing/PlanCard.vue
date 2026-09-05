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
            'relative rounded-2xl border bg-white p-6 transition-colors',
            popular
                ? 'accent-border'
                : isCurrentPlan
                    ? 'border-success-400 bg-success-50/50'
                    : 'border-line hover:border-ink-300'
        ]"
    >
        <!-- Popular Badge -->
        <div
            v-if="popular"
            class="absolute -top-3 left-1/2 -translate-x-1/2 accent-bg rounded-full px-3 py-0.5 text-[10px] font-semibold uppercase tracking-wider"
        >
            MOST POPULAR
        </div>

        <!-- Current Plan Badge -->
        <div
            v-if="isCurrentPlan"
            class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-success-600 px-3 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-white"
        >
            Current Plan
        </div>

        <!-- Header -->
        <div class="mb-6 text-center">
            <div
                :class="[
                    'mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl',
                    popular ? 'accent-bg' : 'bg-paper-deep'
                ]"
            >
                <component
                    :is="getIcon"
                    :class="['h-7 w-7', popular ? '' : 'text-ink-600']"
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
            class="w-full rounded-xl border border-line bg-white px-5 py-3 text-sm font-semibold text-slate-400"
        >
            Current Plan
        </button>
        <button
            v-else-if="canUpgrade"
            :class="popular
                ? 'accent-bg w-full rounded-xl px-5 py-3 text-sm font-semibold'
                : 'w-full rounded-xl border border-line bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50'"
            @click="emit('select', plan)"
        >
            Upgrade to {{ plan.name }}
        </button>
        <button
            v-else-if="canDowngrade"
            class="w-full rounded-xl border border-line bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50"
            @click="emit('select', plan)"
        >
            Downgrade to {{ plan.name }}
        </button>
        <button
            v-else-if="isFree"
            disabled
            class="w-full rounded-xl border border-line bg-white px-5 py-3 text-sm font-semibold text-slate-400"
        >
            Free Forever
        </button>
    </div>
</template>
