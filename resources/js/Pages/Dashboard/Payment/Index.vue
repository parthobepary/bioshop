<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Button } from '@/Components/ui/button'
import PaymentMethodCard from '@/Components/payment/PaymentMethodCard.vue'
import PaymentMethodForm from '@/Components/payment/PaymentMethodForm.vue'
import draggable from 'vuedraggable'
import { Plus, CreditCard, Smartphone, Building2 } from 'lucide-vue-next'

interface PaymentMethod {
    id: number
    type: 'bkash' | 'nagad' | 'rocket' | 'bank' | 'other'
    account_name: string
    account_number: string
    qr_code: string | null
    instructions: string | null
    is_active: boolean
    sort_order: number
}

interface Props {
    paymentMethods: PaymentMethod[]
}

const props = defineProps<Props>()

defineOptions({
    layout: DashboardLayout,
})

const page = usePage()
const flash = computed(() => page.props.flash as { success?: string; error?: string })

// Local methods for dragging
const localMethods = ref<PaymentMethod[]>([...props.paymentMethods])

// Modal state
const showForm = ref(false)
const editingMethod = ref<PaymentMethod | null>(null)

// Sync with props
watch(() => props.paymentMethods, (newMethods) => {
    localMethods.value = [...newMethods]
}, { deep: true })

const openAddModal = () => {
    editingMethod.value = null
    showForm.value = true
}

const openEditModal = (method: PaymentMethod) => {
    editingMethod.value = method
    showForm.value = true
}

const onDragEnd = () => {
    const updatedMethods = localMethods.value.map((method, index) => ({
        id: method.id,
        sort_order: index + 1,
    }))

    router.post(route('payment-methods.reorder'), {
        methods: updatedMethods,
    }, {
        preserveScroll: true,
    })
}

// Stats
const activeCount = computed(() => localMethods.value.filter(m => m.is_active).length)
const mfsCount = computed(() => localMethods.value.filter(m => ['bkash', 'nagad', 'rocket'].includes(m.type)).length)
const bankCount = computed(() => localMethods.value.filter(m => m.type === 'bank').length)
</script>

<template>
    <Head title="Payment Methods" />

    <div class="mx-auto max-w-4xl space-y-8">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Payment Methods</h1>
                <p class="mt-0.5 text-sm text-slate-500">Manage your payment options for customers</p>
            </div>
            <Button
                @click="openAddModal"
                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-500/30"
            >
                <Plus class="h-4 w-4" />
                Add Payment Method
            </Button>
        </div>

        <!-- Flash Messages -->
        <div
            v-if="flash?.success"
            class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700"
        >
            {{ flash.success }}
        </div>
        <div
            v-if="flash?.error"
            class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm font-medium text-rose-700"
        >
            {{ flash.error }}
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 text-white shadow-sm">
                        <CreditCard class="h-5 w-5" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-slate-500">Active Methods</p>
                        <p class="text-2xl font-bold tracking-tight text-slate-900">{{ activeCount }}</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-pink-500 to-rose-500 text-white shadow-sm">
                        <Smartphone class="h-5 w-5" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-slate-500">Mobile Banking</p>
                        <p class="text-2xl font-bold tracking-tight text-slate-900">{{ mfsCount }}</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 text-white shadow-sm">
                        <Building2 class="h-5 w-5" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-slate-500">Bank Accounts</p>
                        <p class="text-2xl font-bold tracking-tight text-slate-900">{{ bankCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods List -->
        <div class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h3 class="flex items-center gap-2 text-base font-semibold text-slate-900">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                        <CreditCard class="h-4 w-4" />
                    </span>
                    Your Payment Methods
                </h3>
                <p class="mt-1.5 text-sm text-slate-500">
                    Drag and drop to reorder. Active methods will be shown on your public shop page.
                </p>
            </div>

            <!-- Empty State -->
            <div
                v-if="paymentMethods.length === 0"
                class="py-12 text-center"
            >
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100">
                    <CreditCard class="h-8 w-8 text-slate-400" />
                </div>
                <h3 class="mb-2 text-lg font-semibold text-slate-900">No payment methods yet</h3>
                <p class="mx-auto mb-6 max-w-sm text-sm text-slate-500">
                    Add your bKash, Nagad, or bank details so customers know how to pay you.
                </p>
                <Button
                    @click="openAddModal"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-500/30"
                >
                    <Plus class="h-4 w-4" />
                    Add Your First Payment Method
                </Button>
            </div>

            <!-- Methods List -->
            <draggable
                v-else
                v-model="localMethods"
                item-key="id"
                handle=".cursor-grab"
                ghost-class="opacity-50"
                animation="200"
                class="space-y-3"
                @end="onDragEnd"
            >
                <template #item="{ element }">
                    <PaymentMethodCard
                        :method="element"
                        @edit="openEditModal"
                    />
                </template>
            </draggable>
        </div>

        <!-- Tips Card -->
        <div
            v-if="paymentMethods.length > 0"
            class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm"
        >
            <div class="flex items-start gap-4">
                <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 text-white shadow-sm">
                    <Smartphone class="h-5 w-5" />
                </div>
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Tips for payment setup</h3>
                    <ul class="mt-2 space-y-1 text-sm text-slate-500">
                        <li>• Add multiple payment options to give customers flexibility</li>
                        <li>• Upload QR codes for faster mobile banking payments</li>
                        <li>• Add instructions if you have any special requirements</li>
                        <li>• Place your most used payment method at the top</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Method Form Modal -->
    <PaymentMethodForm
        v-model:open="showForm"
        :method="editingMethod"
    />
</template>
