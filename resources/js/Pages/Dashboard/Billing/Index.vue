<script setup lang="ts">
import { computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import CurrentPlan from '@/Components/billing/CurrentPlan.vue'
import PaymentHistory from '@/Components/billing/PaymentHistory.vue'
import { AlertCircle, Clock, ArrowRight } from 'lucide-vue-next'

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

interface Payment {
    id: number
    amount: number
    method: string
    transaction_id: string
    status: 'pending' | 'completed' | 'failed' | 'refunded'
    created_at: string
    subscription: Subscription | null
}

interface Usage {
    products: { used: number; limit: number }
    links: { used: number; limit: number }
}

interface Props {
    currentPlan: Plan | null
    subscription: Subscription | null
    payments: Payment[]
    pendingPayment: Payment | null
    usage: Usage
}

const props = defineProps<Props>()

defineOptions({
    layout: DashboardLayout,
})

const page = usePage()
const flash = computed(() => page.props.flash as { success?: string; error?: string })

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-BD', {
        style: 'currency',
        currency: 'BDT',
        minimumFractionDigits: 0,
    }).format(price).replace('BDT', '৳')
}
</script>

<template>
    <Head title="Billing" />

    <div class="mx-auto max-w-5xl space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-slate-900">Billing</h1>
                <p class="mt-0.5 text-sm text-slate-500">Manage your subscription and payment history</p>
            </div>
            <Link
                href="/dashboard/billing/upgrade"
                class="inline-flex items-center justify-center gap-2 self-start accent-bg rounded-xl px-5 py-3 text-sm font-semibold shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-500/30 sm:self-auto"
            >
                View Plans
                <ArrowRight class="h-4 w-4" />
            </Link>
        </div>

        <!-- Flash Messages -->
        <div
            v-if="flash?.success"
            class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700"
        >
            <AlertCircle class="mt-0.5 h-5 w-5 flex-shrink-0" />
            <p class="text-sm">{{ flash.success }}</p>
        </div>
        <div
            v-if="flash?.error"
            class="flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-700"
        >
            <AlertCircle class="mt-0.5 h-5 w-5 flex-shrink-0" />
            <p class="text-sm">{{ flash.error }}</p>
        </div>

        <!-- Pending Payment Alert -->
        <div
            v-if="pendingPayment"
            class="rounded-2xl border border-amber-200 bg-amber-50 p-6 shadow-sm"
        >
            <div class="flex items-start gap-4">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-amber-100">
                    <Clock class="h-5 w-5 text-amber-600" />
                </div>
                <div>
                    <h3 class="font-semibold text-amber-900">Payment Verification Pending</h3>
                    <p class="mt-1 text-sm text-amber-700">
                        Your payment of <strong>{{ formatPrice(pendingPayment.amount) }}</strong>
                        for the <strong>{{ pendingPayment.subscription?.plan?.name }}</strong> plan
                        is being verified. Transaction ID: <strong>{{ pendingPayment.transaction_id }}</strong>
                    </p>
                    <p class="mt-2 text-xs text-amber-600">
                        Verification usually takes 1-24 hours. We'll notify you once your subscription is activated.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Current Plan -->
            <CurrentPlan
                :plan="currentPlan"
                :subscription="subscription"
                :usage="usage"
            />

            <!-- Payment History -->
            <PaymentHistory :payments="payments" />
        </div>

        <!-- Help Section -->
        <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-ink-900 text-white shadow-sm">
                    <AlertCircle class="h-6 w-6" />
                </div>
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Need help with billing?</h3>
                    <p class="mt-1 text-sm text-slate-500">
                        If you have any questions about your subscription or payment, please contact our support team.
                    </p>
                    <div class="mt-3 flex flex-wrap items-center gap-3">
                        <a
                            href="mailto:support@bioshop.com"
                            class="text-sm font-medium accent-text "
                        >
                            Email Support
                        </a>
                        <span class="text-slate-300">|</span>
                        <a
                            href="https://wa.me/8801XXXXXXXXX"
                            target="_blank"
                            class="text-sm font-medium accent-text "
                        >
                            WhatsApp Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
