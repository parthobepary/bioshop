<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card'
import { Crown, Zap, Check, ArrowRight, Calendar, AlertCircle } from 'lucide-vue-next'

interface Plan {
    id: number
    name: string
    slug: string
    price: number
    max_products: number
    max_links: number
    features: string[]
}

interface Subscription {
    id: number
    status: string
    starts_at: string | null
    ends_at: string | null
    cancelled_at: string | null
    plan: Plan
}

interface Usage {
    products: { used: number; limit: number }
    links: { used: number; limit: number }
}

interface Props {
    plan: Plan | null
    subscription: Subscription | null
    usage: Usage
}

const props = defineProps<Props>()

const isPaid = computed(() => props.plan && props.plan.price > 0)
const isFree = computed(() => !props.plan || props.plan.price === 0)

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-BD', {
        style: 'currency',
        currency: 'BDT',
        minimumFractionDigits: 0,
    }).format(price).replace('BDT', '৳')
}

const formatDate = (dateString: string | null) => {
    if (!dateString) return null
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    })
}

const getUsagePercentage = (used: number, limit: number) => {
    if (limit === -1) return 0
    return Math.min(100, (used / limit) * 100)
}

const getUsageColor = (used: number, limit: number) => {
    if (limit === -1) return 'bg-green-500'
    const percentage = (used / limit) * 100
    if (percentage >= 90) return 'bg-red-500'
    if (percentage >= 70) return 'bg-amber-500'
    return 'bg-green-500'
}
</script>

<template>
    <Card class="overflow-hidden">
        <!-- Plan Header -->
        <div
            :class="[
                'p-6',
                isPaid
                    ? 'bg-gradient-to-r from-primary-500 to-purple-600 text-white'
                    : 'bg-gradient-to-r from-slate-100 to-slate-200 text-slate-900'
            ]"
        >
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        :class="[
                            'w-12 h-12 rounded-xl flex items-center justify-center',
                            isPaid ? 'bg-white/20' : 'bg-slate-300'
                        ]"
                    >
                        <Crown v-if="isPaid" class="w-6 h-6" />
                        <Zap v-else class="w-6 h-6 text-slate-600" />
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">{{ plan?.name || 'Free' }} Plan</h2>
                        <p :class="isPaid ? 'text-primary-100' : 'text-slate-500'">
                            <template v-if="isPaid">
                                {{ formatPrice(plan?.price || 0) }}/month
                            </template>
                            <template v-else>
                                No subscription
                            </template>
                        </p>
                    </div>
                </div>

                <Link
                    v-if="isFree"
                    href="/dashboard/billing/upgrade"
                    class="flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium transition-colors"
                >
                    Upgrade
                    <ArrowRight class="w-4 h-4" />
                </Link>
            </div>

            <!-- Subscription Info -->
            <div v-if="subscription && isPaid" class="mt-4 flex items-center gap-4 text-sm">
                <div class="flex items-center gap-1.5">
                    <Calendar class="w-4 h-4 opacity-75" />
                    <span v-if="subscription.ends_at">
                        Renews {{ formatDate(subscription.ends_at) }}
                    </span>
                    <span v-else>
                        Active subscription
                    </span>
                </div>
                <div
                    v-if="subscription.cancelled_at"
                    class="flex items-center gap-1.5 text-amber-200"
                >
                    <AlertCircle class="w-4 h-4" />
                    <span>Cancels at period end</span>
                </div>
            </div>
        </div>

        <CardContent class="p-6">
            <!-- Usage Stats -->
            <div class="space-y-4 mb-6">
                <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wide">Usage</h3>

                <!-- Products Usage -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-slate-600">Products</span>
                        <span class="text-sm font-medium text-slate-900">
                            {{ usage.products.used }}
                            <span class="text-slate-400">
                                / {{ usage.products.limit === -1 ? 'Unlimited' : usage.products.limit }}
                            </span>
                        </span>
                    </div>
                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div
                            :class="['h-full rounded-full transition-all', getUsageColor(usage.products.used, usage.products.limit)]"
                            :style="{ width: `${getUsagePercentage(usage.products.used, usage.products.limit)}%` }"
                        ></div>
                    </div>
                </div>

                <!-- Links Usage -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-slate-600">Links</span>
                        <span class="text-sm font-medium text-slate-900">
                            {{ usage.links.used }}
                            <span class="text-slate-400">
                                / {{ usage.links.limit === -1 ? 'Unlimited' : usage.links.limit }}
                            </span>
                        </span>
                    </div>
                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div
                            :class="['h-full rounded-full transition-all', getUsageColor(usage.links.used, usage.links.limit)]"
                            :style="{ width: `${getUsagePercentage(usage.links.used, usage.links.limit)}%` }"
                        ></div>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div>
                <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wide mb-3">Included Features</h3>
                <ul class="space-y-2">
                    <li
                        v-for="feature in (plan?.features || ['Up to 5 products', 'Up to 5 links', 'Basic analytics'])"
                        :key="feature"
                        class="flex items-center gap-2 text-sm text-slate-600"
                    >
                        <Check class="w-4 h-4 text-green-500 flex-shrink-0" />
                        {{ feature }}
                    </li>
                </ul>
            </div>

            <!-- Actions -->
            <div v-if="isPaid" class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                <Link
                    href="/dashboard/billing/upgrade"
                    class="text-sm text-primary-600 hover:text-primary-700 font-medium"
                >
                    Change Plan
                </Link>
                <button
                    v-if="!subscription?.cancelled_at"
                    class="text-sm text-slate-500 hover:text-red-600 transition-colors"
                >
                    Cancel Subscription
                </button>
            </div>
        </CardContent>
    </Card>
</template>
