<script setup lang="ts">
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import {
    GripVertical,
    Pencil,
    Trash2,
    ToggleLeft,
    ToggleRight,
    Smartphone,
    Building2,
    CreditCard,
    QrCode,
} from 'lucide-vue-next'

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
    method: PaymentMethod
}

const props = defineProps<Props>()

const emit = defineEmits<{
    edit: [method: PaymentMethod]
}>()

const typeConfig = computed(() => {
    const configs: Record<string, { label: string; color: string; bgColor: string; icon: any }> = {
        bkash: {
            label: 'bKash',
            color: 'text-pink-600',
            bgColor: 'bg-pink-100',
            icon: Smartphone,
        },
        nagad: {
            label: 'Nagad',
            color: 'text-orange-600',
            bgColor: 'bg-orange-100',
            icon: Smartphone,
        },
        rocket: {
            label: 'Rocket',
            color: 'text-purple-600',
            bgColor: 'bg-purple-100',
            icon: Smartphone,
        },
        bank: {
            label: 'Bank',
            color: 'text-blue-600',
            bgColor: 'bg-blue-100',
            icon: Building2,
        },
        other: {
            label: 'Other',
            color: 'text-gray-600',
            bgColor: 'bg-gray-100',
            icon: CreditCard,
        },
    }
    return configs[props.method.type] || configs.other
})

const toggleActive = () => {
    router.post(route('payment-methods.toggle', props.method.id), {}, {
        preserveScroll: true,
    })
}

const deleteMethod = () => {
    if (confirm('Are you sure you want to delete this payment method?')) {
        router.delete(route('payment-methods.destroy', props.method.id), {
            preserveScroll: true,
        })
    }
}
</script>

<template>
    <div
        :class="[
            'group flex items-center gap-4 p-4 bg-white rounded-xl border transition-all duration-200',
            method.is_active
                ? 'border-gray-200 hover:border-primary-300 hover:shadow-md'
                : 'border-gray-200 bg-gray-50 opacity-60'
        ]"
    >
        <!-- Drag Handle -->
        <div class="cursor-grab active:cursor-grabbing text-gray-400 hover:text-gray-600">
            <GripVertical class="w-5 h-5" />
        </div>

        <!-- Icon -->
        <div
            :class="[
                'w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0',
                typeConfig.bgColor,
                typeConfig.color,
            ]"
        >
            <component :is="typeConfig.icon" class="w-6 h-6" />
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2">
                <h3 class="font-medium text-gray-900">
                    {{ typeConfig.label }}
                </h3>
                <span
                    v-if="method.qr_code"
                    class="px-2 py-0.5 bg-gray-100 text-gray-500 text-xs rounded-full flex items-center gap-1"
                >
                    <QrCode class="w-3 h-3" />
                    QR
                </span>
            </div>
            <p class="text-sm text-gray-600 font-mono">
                {{ method.account_number }}
            </p>
            <p class="text-sm text-gray-500">
                {{ method.account_name }}
            </p>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
            <!-- Toggle Active -->
            <Button
                variant="ghost"
                size="sm"
                @click="toggleActive"
                :title="method.is_active ? 'Disable' : 'Enable'"
            >
                <ToggleRight v-if="method.is_active" class="w-5 h-5 text-green-500" />
                <ToggleLeft v-else class="w-5 h-5 text-gray-400" />
            </Button>

            <!-- Edit -->
            <Button
                variant="ghost"
                size="sm"
                @click="emit('edit', method)"
                title="Edit"
            >
                <Pencil class="w-4 h-4 text-gray-400" />
            </Button>

            <!-- Delete -->
            <Button
                variant="ghost"
                size="sm"
                @click="deleteMethod"
                title="Delete"
            >
                <Trash2 class="w-4 h-4 text-red-400" />
            </Button>
        </div>
    </div>
</template>
