<script setup lang="ts">
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import {
    GripVertical,
    Pencil,
    Trash2,
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
    delete: [method: { id: number; label: string }]
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
            color: 'accent-text',
            bgColor: 'accent-tint',
            icon: Smartphone,
        },
        bank: {
            label: 'Bank',
            color: 'accent-text',
            bgColor: 'accent-tint',
            icon: Building2,
        },
        other: {
            label: 'Other',
            color: 'text-slate-600',
            bgColor: 'bg-slate-100',
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
    emit('delete', { id: props.method.id, label: typeConfig.value.label })
}
</script>

<template>
    <div
        :class="[
            'group flex items-center gap-4 rounded-2xl border bg-white p-4 shadow-sm transition-all duration-200',
            method.is_active
                ? 'border-line hover:-translate-y-0.5 hover-accent-border hover:shadow-md hover:shadow-slate-200/60'
                : 'border-line bg-slate-50 opacity-60'
        ]"
    >
        <!-- Drag Handle -->
        <div class="cursor-grab text-slate-400 transition-colors hover:text-slate-600 active:cursor-grabbing">
            <GripVertical class="h-5 w-5" />
        </div>

        <!-- Icon -->
        <div
            :class="[
                'flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl',
                typeConfig.bgColor,
                typeConfig.color,
            ]"
        >
            <component :is="typeConfig.icon" class="h-6 w-6" />
        </div>

        <!-- Content -->
        <div class="min-w-0 flex-1">
            <div class="flex items-center gap-2">
                <h3 class="font-semibold text-slate-900">
                    {{ typeConfig.label }}
                </h3>
                <span
                    v-if="method.qr_code"
                    class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500"
                >
                    <QrCode class="h-3 w-3" />
                    QR
                </span>
            </div>
            <p class="font-mono text-sm text-slate-600">
                {{ method.account_number }}
            </p>
            <p class="text-sm text-slate-500">
                {{ method.account_name }}
            </p>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-1.5 opacity-0 transition-opacity group-hover:opacity-100">
            <!-- Toggle Active -->
            <button
                type="button"
                @click="toggleActive"
                :title="method.is_active ? 'Disable' : 'Enable'"
                :class="[
                    'relative inline-flex h-6 w-11 flex-shrink-0 items-center rounded-full transition-colors focus:outline-none focus:ring-4 accent-focus',
                    method.is_active ? 'accent-bg' : 'bg-slate-200'
                ]"
            >
                <span
                    :class="[
                        'inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform',
                        method.is_active ? 'translate-x-5' : 'translate-x-0.5'
                    ]"
                ></span>
            </button>

            <!-- Edit -->
            <button
                type="button"
                @click="emit('edit', method)"
                title="Edit"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100"
            >
                <Pencil class="h-4 w-4" />
            </button>

            <!-- Delete -->
            <button
                type="button"
                @click="deleteMethod"
                title="Delete"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100 hover:text-rose-600"
            >
                <Trash2 class="h-4 w-4" />
            </button>
        </div>
    </div>
</template>
