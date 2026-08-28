<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/Components/ui/card'
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

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Payment Methods</h1>
                <p class="text-gray-500 mt-1">Manage your payment options for customers</p>
            </div>
            <Button @click="openAddModal">
                <Plus class="w-4 h-4 mr-2" />
                Add Payment Method
            </Button>
        </div>

        <!-- Flash Messages -->
        <div
            v-if="flash?.success"
            class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-700"
        >
            {{ flash.success }}
        </div>
        <div
            v-if="flash?.error"
            class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700"
        >
            {{ flash.error }}
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <Card>
                <CardContent class="p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                            <CreditCard class="w-5 h-5 text-green-600" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Active Methods</p>
                            <p class="text-xl font-bold text-gray-900">{{ activeCount }}</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
            <Card>
                <CardContent class="p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-pink-100 rounded-xl flex items-center justify-center">
                            <Smartphone class="w-5 h-5 text-pink-600" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Mobile Banking</p>
                            <p class="text-xl font-bold text-gray-900">{{ mfsCount }}</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
            <Card>
                <CardContent class="p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <Building2 class="w-5 h-5 text-blue-600" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Bank Accounts</p>
                            <p class="text-xl font-bold text-gray-900">{{ bankCount }}</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Payment Methods List -->
        <Card>
            <CardHeader>
                <CardTitle class="flex items-center gap-2">
                    <CreditCard class="w-5 h-5 text-primary-600" />
                    Your Payment Methods
                </CardTitle>
                <CardDescription>
                    Drag and drop to reorder. Active methods will be shown on your public shop page.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <!-- Empty State -->
                <div
                    v-if="paymentMethods.length === 0"
                    class="text-center py-12"
                >
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <CreditCard class="w-8 h-8 text-gray-400" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No payment methods yet</h3>
                    <p class="text-gray-500 mb-6 max-w-sm mx-auto">
                        Add your bKash, Nagad, or bank details so customers know how to pay you.
                    </p>
                    <Button @click="openAddModal">
                        <Plus class="w-4 h-4 mr-2" />
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
            </CardContent>
        </Card>

        <!-- Tips Card -->
        <Card v-if="paymentMethods.length > 0">
            <CardContent class="p-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <Smartphone class="w-5 h-5 text-primary-600" />
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Tips for payment setup</h3>
                        <ul class="mt-2 text-sm text-gray-500 space-y-1">
                            <li>• Add multiple payment options to give customers flexibility</li>
                            <li>• Upload QR codes for faster mobile banking payments</li>
                            <li>• Add instructions if you have any special requirements</li>
                            <li>• Place your most used payment method at the top</li>
                        </ul>
                    </div>
                </div>
            </CardContent>
        </Card>
    </div>

    <!-- Payment Method Form Modal -->
    <PaymentMethodForm
        v-model:open="showForm"
        :method="editingMethod"
    />
</template>
