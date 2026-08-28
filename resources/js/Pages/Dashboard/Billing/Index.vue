<script setup lang="ts">
import { computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Card, CardContent } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
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

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Billing</h1>
                <p class="text-gray-500 mt-1">Manage your subscription and payment history</p>
            </div>
            <Link
                href="/dashboard/billing/upgrade"
                class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium transition-colors"
            >
                View Plans
                <ArrowRight class="w-4 h-4" />
            </Link>
        </div>

        <!-- Flash Messages -->
        <div
            v-if="flash?.success"
            class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 flex items-start gap-3"
        >
            <AlertCircle class="w-5 h-5 flex-shrink-0 mt-0.5" />
            <p>{{ flash.success }}</p>
        </div>
        <div
            v-if="flash?.error"
            class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 flex items-start gap-3"
        >
            <AlertCircle class="w-5 h-5 flex-shrink-0 mt-0.5" />
            <p>{{ flash.error }}</p>
        </div>

        <!-- Pending Payment Alert -->
        <Card v-if="pendingPayment" class="border-amber-200 bg-amber-50">
            <CardContent class="p-4">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <Clock class="w-5 h-5 text-amber-600" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-amber-900">Payment Verification Pending</h3>
                        <p class="text-amber-700 text-sm mt-1">
                            Your payment of <strong>{{ formatPrice(pendingPayment.amount) }}</strong>
                            for the <strong>{{ pendingPayment.subscription?.plan?.name }}</strong> plan
                            is being verified. Transaction ID: <strong>{{ pendingPayment.transaction_id }}</strong>
                        </p>
                        <p class="text-amber-600 text-xs mt-2">
                            Verification usually takes 1-24 hours. We'll notify you once your subscription is activated.
                        </p>
                    </div>
                </div>
            </CardContent>
        </Card>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
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
        <Card>
            <CardContent class="p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <AlertCircle class="w-6 h-6 text-primary-600" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Need help with billing?</h3>
                        <p class="text-gray-600 text-sm mt-1">
                            If you have any questions about your subscription or payment, please contact our support team.
                        </p>
                        <div class="flex flex-wrap gap-3 mt-3">
                            <a
                                href="mailto:support@bioshop.com"
                                class="text-sm text-primary-600 hover:text-primary-700 font-medium"
                            >
                                Email Support
                            </a>
                            <span class="text-gray-300">|</span>
                            <a
                                href="https://wa.me/8801XXXXXXXXX"
                                target="_blank"
                                class="text-sm text-primary-600 hover:text-primary-700 font-medium"
                            >
                                WhatsApp Support
                            </a>
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
