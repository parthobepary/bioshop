<script setup lang="ts">
import { computed } from 'vue'
import { Button } from '@/Components/ui/button'
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
            'relative rounded-2xl border-2 p-6 transition-all',
            popular
                ? 'border-primary-500 shadow-lg shadow-primary-500/20'
                : isCurrentPlan
                    ? 'border-green-500 bg-green-50/50'
                    : 'border-slate-200 hover:border-slate-300'
        ]"
    >
        <!-- Popular Badge -->
        <div
            v-if="popular"
            class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 bg-primary-500 text-white text-xs font-bold rounded-full"
        >
            Most Popular
        </div>

        <!-- Current Plan Badge -->
        <div
            v-if="isCurrentPlan"
            class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full"
        >
            Current Plan
        </div>

        <!-- Header -->
        <div class="text-center mb-6">
            <div
                :class="[
                    'w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4',
                    popular ? 'bg-primary-100' : 'bg-slate-100'
                ]"
            >
                <component
                    :is="getIcon"
                    :class="['w-7 h-7', popular ? 'text-primary-600' : 'text-slate-600']"
                />
            </div>
            <h3 class="text-xl font-bold text-slate-900">{{ plan.name }}</h3>
            <div class="mt-3">
                <span class="text-3xl font-bold text-slate-900">{{ formatPrice(plan.price) }}</span>
                <span v-if="!isFree" class="text-slate-500">/month</span>
            </div>
        </div>

        <!-- Limits -->
        <div class="space-y-3 mb-6">
            <div class="flex items-center justify-between text-sm">
                <span class="text-slate-600">Products</span>
                <span class="font-semibold text-slate-900">
                    {{ plan.max_products === -1 ? 'Unlimited' : plan.max_products }}
                </span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="text-slate-600">Links</span>
                <span class="font-semibold text-slate-900">
                    {{ plan.max_links === -1 ? 'Unlimited' : plan.max_links }}
                </span>
            </div>
        </div>

        <!-- Features -->
        <ul class="space-y-3 mb-6">
            <li
                v-for="feature in plan.features"
                :key="feature"
                class="flex items-start gap-2 text-sm"
            >
                <Check class="w-4 h-4 text-green-500 flex-shrink-0 mt-0.5" />
                <span class="text-slate-600">{{ feature }}</span>
            </li>
        </ul>

        <!-- Action Button -->
        <Button
            v-if="isCurrentPlan"
            variant="outline"
            class="w-full"
            disabled
        >
            Current Plan
        </Button>
        <Button
            v-else-if="canUpgrade"
            :class="popular ? 'w-full bg-primary-600 hover:bg-primary-700' : 'w-full'"
            @click="emit('select', plan)"
        >
            Upgrade to {{ plan.name }}
        </Button>
        <Button
            v-else-if="canDowngrade"
            variant="outline"
            class="w-full"
            @click="emit('select', plan)"
        >
            Downgrade to {{ plan.name }}
        </Button>
        <Button
            v-else-if="isFree"
            variant="outline"
            class="w-full"
            disabled
        >
            Free Forever
        </Button>
    </div>
</template>
