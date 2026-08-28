<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
    DialogFooter,
} from '@/Components/ui/dialog'
import PlanCard from '@/Components/billing/PlanCard.vue'
import { ArrowLeft, Smartphone, Building2, Loader2, AlertCircle } from 'lucide-vue-next'

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
    plans: Plan[]
    currentPlan: Plan | null
}

const props = defineProps<Props>()

defineOptions({
    layout: DashboardLayout,
})

const showPaymentModal = ref(false)
const selectedPlan = ref<Plan | null>(null)

const paymentMethods = [
    { value: 'bkash', label: 'bKash', number: '01XXXXXXXXX', color: 'bg-pink-100 text-pink-600' },
    { value: 'nagad', label: 'Nagad', number: '01XXXXXXXXX', color: 'bg-orange-100 text-orange-600' },
    { value: 'rocket', label: 'Rocket', number: '01XXXXXXXXX', color: 'bg-purple-100 text-purple-600' },
    { value: 'bank', label: 'Bank Transfer', number: 'See below', color: 'bg-blue-100 text-blue-600' },
]

const form = useForm({
    plan_id: 0,
    payment_method: 'bkash' as string,
    transaction_id: '',
    phone_number: '',
})

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-BD', {
        style: 'currency',
        currency: 'BDT',
        minimumFractionDigits: 0,
    }).format(price).replace('BDT', '৳')
}

const openPaymentModal = (plan: Plan) => {
    selectedPlan.value = plan
    form.plan_id = plan.id
    form.reset('payment_method', 'transaction_id', 'phone_number')
    form.payment_method = 'bkash'
    showPaymentModal.value = true
}

const submitPayment = () => {
    form.post(route('billing.subscribe'), {
        onSuccess: () => {
            showPaymentModal.value = false
        },
    })
}

const selectedPaymentMethod = computed(() => {
    return paymentMethods.find(m => m.value === form.payment_method)
})
</script>

<template>
    <Head title="Upgrade Plan" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <Link
                href="/dashboard/billing"
                class="p-2 hover:bg-gray-100 rounded-lg transition-colors"
            >
                <ArrowLeft class="w-5 h-5 text-gray-500" />
            </Link>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Choose Your Plan</h1>
                <p class="text-gray-500 mt-1">Select the plan that fits your business needs</p>
            </div>
        </div>

        <!-- Plans Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <PlanCard
                v-for="plan in plans"
                :key="plan.id"
                :plan="plan"
                :current-plan-slug="currentPlan?.slug || null"
                :popular="plan.slug === 'pro'"
                @select="openPaymentModal"
            />
        </div>

        <!-- Features Comparison -->
        <Card>
            <CardHeader>
                <CardTitle>All Plans Include</CardTitle>
            </CardHeader>
            <CardContent>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-4 bg-gray-50 rounded-xl text-center">
                        <p class="font-medium text-gray-900">Mobile Responsive</p>
                        <p class="text-sm text-gray-500 mt-1">Beautiful on all devices</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl text-center">
                        <p class="font-medium text-gray-900">WhatsApp Ordering</p>
                        <p class="text-sm text-gray-500 mt-1">Direct customer contact</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl text-center">
                        <p class="font-medium text-gray-900">Payment Methods</p>
                        <p class="text-sm text-gray-500 mt-1">bKash, Nagad, Bank</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl text-center">
                        <p class="font-medium text-gray-900">SSL Security</p>
                        <p class="text-sm text-gray-500 mt-1">Secure connections</p>
                    </div>
                </div>
            </CardContent>
        </Card>

        <!-- FAQ -->
        <Card>
            <CardHeader>
                <CardTitle>Frequently Asked Questions</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
                <div>
                    <h4 class="font-medium text-gray-900">How do I pay?</h4>
                    <p class="text-gray-600 text-sm mt-1">
                        We accept bKash, Nagad, Rocket, and bank transfers. After selecting a plan, send the payment and enter your transaction ID.
                    </p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">How long does verification take?</h4>
                    <p class="text-gray-600 text-sm mt-1">
                        Payment verification usually takes 1-24 hours. You'll be notified once your subscription is activated.
                    </p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Can I cancel anytime?</h4>
                    <p class="text-gray-600 text-sm mt-1">
                        Yes, you can cancel your subscription at any time. You'll continue to have access until the end of your billing period.
                    </p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">What happens to my data if I downgrade?</h4>
                    <p class="text-gray-600 text-sm mt-1">
                        Your data is safe. If you exceed the limits of your new plan, you won't be able to add new items, but existing ones will remain.
                    </p>
                </div>
            </CardContent>
        </Card>
    </div>

    <!-- Payment Modal -->
    <Dialog :open="showPaymentModal" @update:open="showPaymentModal = $event">
        <DialogContent class="max-w-lg">
            <DialogHeader>
                <DialogTitle>Complete Your Payment</DialogTitle>
                <DialogDescription>
                    Upgrade to {{ selectedPlan?.name }} plan for {{ formatPrice(selectedPlan?.price || 0) }}/month
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submitPayment" class="space-y-6">
                <!-- Payment Method Selection -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Payment Method <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <button
                            v-for="method in paymentMethods"
                            :key="method.value"
                            type="button"
                            :class="[
                                'flex items-center gap-3 p-3 rounded-xl border-2 transition-all text-left',
                                form.payment_method === method.value
                                    ? 'border-primary-500 bg-primary-50'
                                    : 'border-gray-200 hover:border-gray-300'
                            ]"
                            @click="form.payment_method = method.value"
                        >
                            <div :class="['w-10 h-10 rounded-lg flex items-center justify-center', method.color]">
                                <Smartphone v-if="method.value !== 'bank'" class="w-5 h-5" />
                                <Building2 v-else class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ method.label }}</p>
                                <p class="text-xs text-gray-500">{{ method.number }}</p>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Payment Instructions -->
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <AlertCircle class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" />
                        <div class="text-sm">
                            <p class="font-medium text-amber-800">Payment Instructions:</p>
                            <ol class="mt-2 text-amber-700 space-y-1 list-decimal list-inside">
                                <li>Send {{ formatPrice(selectedPlan?.price || 0) }} to our {{ selectedPaymentMethod?.label }} number</li>
                                <li>Note down your Transaction ID</li>
                                <li>Enter the Transaction ID below</li>
                            </ol>
                            <p class="mt-2 font-medium text-amber-800">
                                {{ selectedPaymentMethod?.label }} Number: {{ selectedPaymentMethod?.number }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Transaction ID -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Transaction ID <span class="text-red-500">*</span>
                    </label>
                    <Input
                        v-model="form.transaction_id"
                        type="text"
                        placeholder="Enter your transaction ID"
                        required
                    />
                    <p v-if="form.errors.transaction_id" class="text-sm text-red-600">
                        {{ form.errors.transaction_id }}
                    </p>
                </div>

                <!-- Phone Number (optional) -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Your Phone Number (optional)
                    </label>
                    <Input
                        v-model="form.phone_number"
                        type="text"
                        placeholder="01XXXXXXXXX"
                    />
                    <p class="text-xs text-gray-500">
                        The phone number you used for payment (helps with verification)
                    </p>
                </div>

                <!-- Error Messages -->
                <div v-if="(form.errors as any).payment" class="p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                    {{ (form.errors as any).payment }}
                </div>

                <DialogFooter>
                    <Button
                        type="button"
                        variant="outline"
                        @click="showPaymentModal = false"
                    >
                        Cancel
                    </Button>
                    <Button
                        type="submit"
                        :disabled="form.processing || !form.transaction_id"
                    >
                        <Loader2 v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                        Submit Payment
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
