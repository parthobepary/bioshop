<script setup lang="ts">
import { ref } from 'vue'
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/Components/ui/dialog'
import {
    CreditCard,
    Smartphone,
    Building2,
    Copy,
    Check,
    QrCode,
    ChevronRight,
    X,
} from 'lucide-vue-next'

interface PaymentMethod {
    id: number
    type: 'bkash' | 'nagad' | 'rocket' | 'bank' | 'other'
    account_name: string
    account_number: string
    qr_code: string | null
    instructions: string | null
    is_active: boolean
}

interface Props {
    paymentMethods: PaymentMethod[]
}

defineProps<Props>()

const showModal = ref(false)
const selectedMethod = ref<PaymentMethod | null>(null)
const copied = ref(false)

const typeConfig: Record<string, { label: string; icon: any; bgColor: string; textColor: string }> = {
    bkash: { label: 'bKash', icon: Smartphone, bgColor: 'bg-pink-100', textColor: 'text-pink-600' },
    nagad: { label: 'Nagad', icon: Smartphone, bgColor: 'bg-orange-100', textColor: 'text-orange-600' },
    rocket: { label: 'Rocket', icon: Smartphone, bgColor: 'bg-purple-100', textColor: 'text-purple-600' },
    bank: { label: 'Bank Transfer', icon: Building2, bgColor: 'bg-blue-100', textColor: 'text-blue-600' },
    other: { label: 'Other', icon: CreditCard, bgColor: 'bg-slate-100', textColor: 'text-slate-600' },
}

const getTypeConfig = (type: string) => {
    return typeConfig[type] || typeConfig.other
}

const openMethod = (method: PaymentMethod) => {
    selectedMethod.value = method
    showModal.value = true
    copied.value = false
}

const closeModal = () => {
    showModal.value = false
}

const copyNumber = async () => {
    if (!selectedMethod.value) return

    try {
        await navigator.clipboard.writeText(selectedMethod.value.account_number)
        copied.value = true
        setTimeout(() => {
            copied.value = false
        }, 2000)
    } catch (error) {
        // Fallback for older browsers
        const textArea = document.createElement('textarea')
        textArea.value = selectedMethod.value.account_number
        document.body.appendChild(textArea)
        textArea.select()
        document.execCommand('copy')
        document.body.removeChild(textArea)
        copied.value = true
        setTimeout(() => {
            copied.value = false
        }, 2000)
    }
}
</script>

<template>
    <div v-if="paymentMethods.length > 0" class="mb-8">
        <!-- Section Header -->
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center theme-bg-light">
                <CreditCard class="w-4 h-4 theme-text" />
            </div>
            <h2 class="text-lg font-bold text-slate-800">Payment Methods</h2>
        </div>

        <!-- Payment Methods List -->
        <div class="space-y-2">
            <button
                v-for="method in paymentMethods"
                :key="method.id"
                class="w-full flex items-center gap-3 p-4 bg-white rounded-xl border border-slate-100 shadow-sm hover:shadow-md hover:border-slate-200 transition-all text-left"
                @click="openMethod(method)"
            >
                <!-- Icon -->
                <div
                    :class="[
                        'w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0',
                        getTypeConfig(method.type).bgColor
                    ]"
                >
                    <component
                        :is="getTypeConfig(method.type).icon"
                        :class="['w-5 h-5', getTypeConfig(method.type).textColor]"
                    />
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-slate-800">
                        {{ getTypeConfig(method.type).label }}
                    </p>
                    <p class="text-sm text-slate-500 truncate">
                        {{ method.account_name }}
                    </p>
                </div>

                <!-- Arrow -->
                <ChevronRight class="w-5 h-5 text-slate-400" />
            </button>
        </div>
    </div>

    <!-- Payment Detail Modal -->
    <Dialog :open="showModal" @update:open="showModal = $event">
        <DialogContent class="max-w-sm p-0 overflow-hidden">
            <div v-if="selectedMethod">
                <!-- Header -->
                <div
                    :class="[
                        'p-6 text-center',
                        getTypeConfig(selectedMethod.type).bgColor
                    ]"
                >
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-white/80 flex items-center justify-center mb-3">
                        <component
                            :is="getTypeConfig(selectedMethod.type).icon"
                            :class="['w-8 h-8', getTypeConfig(selectedMethod.type).textColor]"
                        />
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">
                        {{ getTypeConfig(selectedMethod.type).label }}
                    </h3>
                    <p class="text-slate-600 mt-1">{{ selectedMethod.account_name }}</p>

                    <!-- Close Button -->
                    <button
                        class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/80 flex items-center justify-center hover:bg-white transition-colors"
                        @click="closeModal"
                    >
                        <X class="w-4 h-4 text-slate-600" />
                    </button>
                </div>

                <!-- Content -->
                <div class="p-6 space-y-4">
                    <!-- Account Number -->
                    <div>
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                            {{ selectedMethod.type === 'bank' ? 'Account Number' : 'Phone Number' }}
                        </label>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="flex-1 px-4 py-3 bg-slate-50 rounded-xl font-mono text-lg text-slate-800">
                                {{ selectedMethod.account_number }}
                            </div>
                            <button
                                class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition-colors"
                                :title="copied ? 'Copied!' : 'Copy'"
                                @click="copyNumber"
                            >
                                <Check v-if="copied" class="w-5 h-5 text-green-600" />
                                <Copy v-else class="w-5 h-5 text-slate-600" />
                            </button>
                        </div>
                    </div>

                    <!-- QR Code -->
                    <div v-if="selectedMethod.qr_code" class="text-center">
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                            Scan QR Code
                        </label>
                        <div class="mt-2 p-4 bg-white rounded-2xl border border-slate-200 inline-block">
                            <img
                                :src="`/storage/${selectedMethod.qr_code}`"
                                alt="QR Code"
                                class="w-40 h-40 object-contain mx-auto"
                            />
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div v-if="selectedMethod.instructions">
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                            Instructions
                        </label>
                        <p class="mt-1 text-slate-600 text-sm whitespace-pre-line">
                            {{ selectedMethod.instructions }}
                        </p>
                    </div>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
