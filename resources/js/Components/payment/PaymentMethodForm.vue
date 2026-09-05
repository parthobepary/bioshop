<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
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
import {
    Loader2,
    Smartphone,
    Building2,
    CreditCard,
    Upload,
    X,
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
    open: boolean
    method?: PaymentMethod | null
}

const props = defineProps<Props>()

const emit = defineEmits<{
    'update:open': [value: boolean]
}>()

const isEdit = computed(() => !!props.method)

const form = useForm({
    type: 'bkash' as 'bkash' | 'nagad' | 'rocket' | 'bank' | 'other',
    account_name: '',
    account_number: '',
    qr_code: null as File | null,
    instructions: '',
    remove_qr: false,
})

const qrPreview = ref<string | null>(null)
const fileInput = ref<HTMLInputElement | null>(null)

const paymentTypes = [
    { value: 'bkash', label: 'bKash', icon: Smartphone, color: 'pink' },
    { value: 'nagad', label: 'Nagad', icon: Smartphone, color: 'orange' },
    { value: 'rocket', label: 'Rocket', icon: Smartphone, color: 'purple' },
    { value: 'bank', label: 'Bank', icon: Building2, color: 'blue' },
    { value: 'other', label: 'Other', icon: CreditCard, color: 'gray' },
]

// Watch for method changes (when editing)
watch(() => props.method, (newMethod) => {
    if (newMethod) {
        form.type = newMethod.type
        form.account_name = newMethod.account_name
        form.account_number = newMethod.account_number
        form.instructions = newMethod.instructions || ''
        form.qr_code = null
        form.remove_qr = false
        qrPreview.value = newMethod.qr_code ? `/storage/${newMethod.qr_code}` : null
    } else {
        form.reset()
        qrPreview.value = null
    }
}, { immediate: true })

// Watch for dialog open state
watch(() => props.open, (isOpen) => {
    if (!isOpen) {
        form.reset()
        form.clearErrors()
        qrPreview.value = null
    }
})

const closeDialog = () => {
    emit('update:open', false)
}

const handleQrUpload = (event: Event) => {
    const target = event.target as HTMLInputElement
    const file = target.files?.[0]

    if (file) {
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file')
            return
        }
        if (file.size > 1024 * 1024) {
            alert('Image must be less than 1MB')
            return
        }

        form.qr_code = file
        form.remove_qr = false

        const reader = new FileReader()
        reader.onload = (e) => {
            qrPreview.value = e.target?.result as string
        }
        reader.readAsDataURL(file)
    }
}

const removeQr = () => {
    form.qr_code = null
    form.remove_qr = true
    qrPreview.value = null
    if (fileInput.value) {
        fileInput.value.value = ''
    }
}

const submit = () => {
    if (isEdit.value && props.method) {
        form.transform((data) => ({
            ...data,
            _method: 'PUT',
        })).post(route('payment-methods.update', props.method.id), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => closeDialog(),
        })
    } else {
        form.post(route('payment-methods.store'), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => closeDialog(),
        })
    }
}

const getTypeColor = (color: string) => {
    const colors: Record<string, string> = {
        pink: 'border-pink-500 bg-pink-50 text-pink-600',
        orange: 'border-orange-500 bg-orange-50 text-orange-600',
        purple: 'accent-border accent-tint accent-text',
        blue: 'accent-border accent-tint accent-text',
        gray: 'border-slate-500 bg-slate-50 text-slate-600',
    }
    return colors[color] || colors.gray
}
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="max-w-lg">
            <DialogHeader>
                <DialogTitle>{{ isEdit ? 'Edit Payment Method' : 'Add Payment Method' }}</DialogTitle>
                <DialogDescription>
                    {{ isEdit ? 'Update payment method details.' : 'Add a new payment method for your customers.' }}
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Payment Type Selection -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-slate-700">
                        Payment Type <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-5 gap-2">
                        <button
                            v-for="type in paymentTypes"
                            :key="type.value"
                            type="button"
                            :class="[
                                'flex flex-col items-center gap-1.5 rounded-xl border-2 p-3 transition-all',
                                form.type === type.value
                                    ? getTypeColor(type.color)
                                    : 'border-line text-slate-500 hover:border-slate-300 hover:bg-slate-50'
                            ]"
                            @click="form.type = type.value as any"
                        >
                            <component :is="type.icon" class="h-5 w-5" />
                            <span class="text-xs font-medium">{{ type.label }}</span>
                        </button>
                    </div>
                    <p v-if="form.errors.type" class="mt-1 text-sm text-rose-600">
                        {{ form.errors.type }}
                    </p>
                </div>

                <!-- Account Name -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-slate-700">
                        Account Name <span class="text-rose-500">*</span>
                    </label>
                    <Input
                        v-model="form.account_name"
                        type="text"
                        placeholder="Enter account holder name"
                        maxlength="100"
                        class="w-full rounded-xl border border-line bg-slate-50 px-4 py-2.5 text-slate-900 placeholder:text-slate-400 transition accent-focus focus:bg-white focus:ring-4 accent-focus"
                    />
                    <p v-if="form.errors.account_name" class="mt-1 text-sm text-rose-600">
                        {{ form.errors.account_name }}
                    </p>
                </div>

                <!-- Account Number -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-slate-700">
                        {{ form.type === 'bank' ? 'Account Number' : 'Phone Number' }} <span class="text-rose-500">*</span>
                    </label>
                    <Input
                        v-model="form.account_number"
                        type="text"
                        :placeholder="form.type === 'bank' ? 'Enter account number' : '01XXXXXXXXX'"
                        maxlength="100"
                        class="w-full rounded-xl border border-line bg-slate-50 px-4 py-2.5 text-slate-900 placeholder:text-slate-400 transition accent-focus focus:bg-white focus:ring-4 accent-focus"
                    />
                    <p v-if="form.errors.account_number" class="mt-1 text-sm text-rose-600">
                        {{ form.errors.account_number }}
                    </p>
                </div>

                <!-- QR Code Upload -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-slate-700">
                        QR Code (Optional)
                    </label>
                    <div class="flex items-start gap-4">
                        <!-- Preview -->
                        <div
                            v-if="qrPreview"
                            class="relative h-24 w-24 overflow-hidden rounded-xl border border-line bg-slate-100"
                        >
                            <img
                                :src="qrPreview"
                                alt="QR Code"
                                class="h-full w-full object-contain"
                            />
                            <button
                                type="button"
                                class="absolute -right-1 -top-1 rounded-full bg-rose-500 p-1 text-white shadow-sm transition-colors hover:bg-rose-600"
                                @click="removeQr"
                            >
                                <X class="h-3 w-3" />
                            </button>
                        </div>

                        <!-- Upload Button -->
                        <button
                            v-else
                            type="button"
                            class="flex h-24 w-24 flex-col items-center justify-center gap-1 rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 text-slate-400 transition-colors hover-accent-border hover-accent-text"
                            @click="fileInput?.click()"
                        >
                            <QrCode class="h-6 w-6" />
                            <span class="text-xs">Upload</span>
                        </button>

                        <div class="flex-1 text-sm text-slate-500">
                            <p>Upload a QR code for easy payments.</p>
                            <p class="mt-1 text-xs text-slate-400">Max 1MB, PNG or JPG</p>
                        </div>
                    </div>
                    <input
                        ref="fileInput"
                        type="file"
                        accept="image/*"
                        class="hidden"
                        @change="handleQrUpload"
                    />
                    <p v-if="form.errors.qr_code" class="mt-1 text-sm text-rose-600">
                        {{ form.errors.qr_code }}
                    </p>
                </div>

                <!-- Instructions -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-slate-700">
                        Payment Instructions (Optional)
                    </label>
                    <textarea
                        v-model="form.instructions"
                        rows="3"
                        class="w-full resize-none rounded-xl border border-line bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition accent-focus focus:bg-white focus:ring-4 accent-focus"
                        placeholder="Add any special instructions for customers..."
                        maxlength="500"
                    />
                    <div class="flex justify-between">
                        <p v-if="form.errors.instructions" class="text-sm text-rose-600">
                            {{ form.errors.instructions }}
                        </p>
                        <p class="ml-auto text-xs text-slate-400">
                            {{ form.instructions.length }}/500
                        </p>
                    </div>
                </div>

                <DialogFooter class="pt-4">
                    <Button
                        type="button"
                        variant="outline"
                        @click="closeDialog"
                        class="rounded-xl border border-line bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    >
                        Cancel
                    </Button>
                    <Button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 accent-bg rounded-xl px-5 py-2.5 text-sm font-semibold shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-500/30"
                    >
                        <Loader2 v-if="form.processing" class="h-4 w-4 animate-spin" />
                        {{ isEdit ? 'Update' : 'Add' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
