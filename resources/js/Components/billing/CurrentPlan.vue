<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
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
    if (limit === -1) return 'bg-emerald-500'
    const percentage = (used / limit) * 100
    if (percentage >= 90) return 'bg-rose-500'
    if (percentage >= 70) return 'bg-amber-500'
    return 'bg-emerald-500'
}
</script>

<template>
    <div class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm">
        <!-- Active Plan Summary Band -->
        <div
            :class="[
                'rounded-2xl p-5',
                isPaid
                    ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white'
                    : 'border border-slate-200 bg-slate-50 text-slate-900',
            ]"
        >
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div
                        :class="[
                            'flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl',
                            isPaid ? 'bg-white/20' : 'bg-white shadow-sm',
                        ]"
                    >
                        <Crown v-if="isPaid" class="h-6 w-6" />
                        <Zap v-else class="h-6 w-6 text-slate-500" />
                    </div>
                    <div>
                        <h2 class="text-xl font-bold tracking-tight">{{ plan?.name || 'Free' }} Plan</h2>
                        <p :class="['text-sm', isPaid ? 'text-indigo-100' : 'text-slate-500']">
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
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md"
                >
                    Upgrade
                    <ArrowRight class="h-4 w-4" />
                </Link>
            </div>

            <!-- Subscription Info -->
            <div v-if="subscription && isPaid" class="mt-4 flex flex-wrap items-center gap-4 text-sm">
                <div class="flex items-center gap-1.5 text-indigo-100">
                    <Calendar class="h-4 w-4 opacity-75" />
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
                    <AlertCircle class="h-4 w-4" />
                    <span>Cancels at period end</span>
                </div>
            </div>
        </div>

        <!-- Usage Stats -->
        <div class="mt-6 space-y-4">
            <h3 class="text-xs font-medium uppercase tracking-wide text-slate-400">Usage</h3>

            <!-- Products Usage -->
            <div>
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-sm text-slate-500">Products</span>
                    <span class="text-sm font-semibold text-slate-900">
                        {{ usage.products.used }}
                        <span class="font-normal text-slate-400">
                            / {{ usage.products.limit === -1 ? 'Unlimited' : usage.products.limit }}
                        </span>
                    </span>
                </div>
                <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                    <div
                        :class="['h-full rounded-full transition-all', getUsageColor(usage.products.used, usage.products.limit)]"
                        :style="{ width: `${getUsagePercentage(usage.products.used, usage.products.limit)}%` }"
                    ></div>
                </div>
            </div>

            <!-- Links Usage -->
            <div>
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-sm text-slate-500">Links</span>
                    <span class="text-sm font-semibold text-slate-900">
                        {{ usage.links.used }}
                        <span class="font-normal text-slate-400">
                            / {{ usage.links.limit === -1 ? 'Unlimited' : usage.links.limit }}
                        </span>
                    </span>
                </div>
                <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                    <div
                        :class="['h-full rounded-full transition-all', getUsageColor(usage.links.used, usage.links.limit)]"
                        :style="{ width: `${getUsagePercentage(usage.links.used, usage.links.limit)}%` }"
                    ></div>
                </div>
            </div>
        </div>

        <!-- Features -->
        <div class="mt-6">
            <h3 class="mb-3 text-xs font-medium uppercase tracking-wide text-slate-400">Included Features</h3>
            <ul class="space-y-2">
                <li
                    v-for="feature in (plan?.features || ['Up to 5 products', 'Up to 5 links', 'Basic analytics'])"
                    :key="feature"
                    class="flex items-center gap-2 text-sm text-slate-600"
                >
                    <Check class="h-4 w-4 flex-shrink-0 text-emerald-500" />
                    {{ feature }}
                </li>
            </ul>
        </div>

        <!-- Actions -->
        <div v-if="isPaid" class="mt-6 flex items-center justify-between border-t border-slate-100 pt-6">
            <Link
                href="/dashboard/billing/upgrade"
                class="text-sm font-semibold text-indigo-600 transition-colors hover:text-indigo-700"
            >
                Change Plan
            </Link>
            <button
                v-if="!subscription?.cancelled_at"
                class="text-sm font-medium text-slate-500 transition-colors hover:text-rose-600"
            >
                Cancel Subscription
            </button>
        </div>
    </div>
</template>
