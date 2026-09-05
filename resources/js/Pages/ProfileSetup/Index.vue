<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Head, useForm, router } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import AvatarUpload from '@/Components/common/AvatarUpload.vue'
import ColorPicker from '@/Components/common/ColorPicker.vue'
import { shopVars } from '@/lib/color'
import { Check, ChevronRight, ChevronLeft, Loader2, Store, Sparkles } from 'lucide-vue-next'
import axios from 'axios'
import { useDebounceFn } from '@vueuse/core'

interface Props {
    user: {
        id: number
        name: string
        email: string
    }
}

const props = defineProps<Props>()

const currentStep = ref(1)
const totalSteps = 3

const form = useForm({
    username: '',
    name: props.user.name || '',
    bio: '',
    whatsapp: '',
    theme_color: '#6366f1',
    photo: null as string | null,
})

// Username validation
const usernameStatus = ref<'idle' | 'checking' | 'available' | 'taken' | 'invalid'>('idle')
const usernameMessage = ref('')

const checkUsername = useDebounceFn(async (username: string) => {
    if (!username || username.length < 3) {
        usernameStatus.value = 'invalid'
        usernameMessage.value = 'Username must be at least 3 characters'
        return
    }

    if (!/^[a-z0-9_]+$/.test(username)) {
        usernameStatus.value = 'invalid'
        usernameMessage.value = 'Only lowercase letters, numbers, and underscores'
        return
    }

    usernameStatus.value = 'checking'
    usernameMessage.value = 'Checking availability...'

    try {
        const response = await axios.post(route('profile.setup.check-username'), {
            username: username,
        })

        if (response.data.available) {
            usernameStatus.value = 'available'
            usernameMessage.value = response.data.message
        } else {
            usernameStatus.value = 'taken'
            usernameMessage.value = response.data.message
        }
    } catch (error) {
        usernameStatus.value = 'invalid'
        usernameMessage.value = 'Error checking username'
    }
}, 500)

watch(() => form.username, (newVal) => {
    form.username = newVal.toLowerCase().replace(/[^a-z0-9_]/g, '')
    if (form.username) {
        checkUsername(form.username)
    } else {
        usernameStatus.value = 'idle'
        usernameMessage.value = ''
    }
})

const canProceedStep1 = computed(() => {
    return form.username.length >= 3 && usernameStatus.value === 'available'
})

const canProceedStep2 = computed(() => {
    return form.name.length >= 2 && /^01[3-9]\d{8}$/.test(form.whatsapp)
})

const canProceedStep3 = computed(() => {
    return /^#[0-9A-Fa-f]{6}$/.test(form.theme_color)
})

const nextStep = () => {
    if (currentStep.value < totalSteps) {
        currentStep.value++
    }
}

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--
    }
}

const submit = () => {
    form.post(route('profile.setup.store'), {
        forceFormData: true,
    })
}

const handlePhotoChange = (path: string | null) => {
    form.photo = path
}
</script>

<template>
    <Head title="Setup Your Shop" />

    <!-- Accents follow the colour picked in step 3, same as the settings panel -->
    <div :style="shopVars(form.theme_color)" class="relative min-h-screen overflow-hidden">
        <!-- Gradient Background -->
        <div class="absolute inset-0 bg-paper-subtle"></div>

        <!-- Animated Blobs -->
        <div class="absolute top-20 left-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-40 right-10 w-72 h-72 accent-tint rounded-full blur-3xl animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-20 left-1/2 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>

        <!-- Content -->
        <div class="relative flex min-h-screen flex-col items-center justify-center px-4 py-12">
            <!-- Header -->
            <div class="mb-6 text-center">
                <div class="flex items-center justify-center gap-2 mb-4">
                    <div class="w-14 h-14 bg-ink-900 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/30">
                        <Store class="w-8 h-8 text-white" />
                    </div>
                </div>
                <h1 class="text-2xl font-semibold text-gray-900 mb-2">Setup Your Shop</h1>
                <p class="text-gray-600">Let's create your online presence in just a few steps</p>
            </div>

            <!-- Progress Steps -->
            <div class="flex items-center gap-2 mb-6">
                <template v-for="step in totalSteps" :key="step">
                    <div
                        :class="[
                            'w-10 h-10 rounded-full flex items-center justify-center font-semibold transition-all duration-300',
                            step < currentStep ? 'bg-green-500 text-white' :
                            step === currentStep ? 'accent-bg' :
                            'bg-gray-200 text-gray-500'
                        ]"
                    >
                        <Check v-if="step < currentStep" :size="20" />
                        <span v-else>{{ step }}</span>
                    </div>
                    <div
                        v-if="step < totalSteps"
                        :class="[
                            'w-12 h-1 rounded-full transition-all duration-300',
                            step < currentStep ? 'bg-green-500' : 'bg-gray-200'
                        ]"
                    />
                </template>
            </div>

            <!-- Card -->
            <div class="w-full max-w-lg">
                <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl shadow-slate-200/50 border border-white/50 p-8">
                    <form @submit.prevent="submit">
                        <!-- Step 1: Username -->
                        <div v-show="currentStep === 1" class="space-y-6">
                            <div class="text-center mb-6">
                                <h2 class="text-xl font-semibold text-gray-900">Choose Your Username</h2>
                                <p class="text-sm text-gray-500 mt-1">This will be your shop URL</p>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Username
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                        bioshop.com/
                                    </span>
                                    <Input
                                        v-model="form.username"
                                        type="text"
                                        placeholder="yourshop"
                                        class="pl-28"
                                        maxlength="30"
                                    />
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2">
                                        <Loader2 v-if="usernameStatus === 'checking'" class="w-5 h-5 animate-spin text-gray-400" />
                                        <Check v-else-if="usernameStatus === 'available'" class="w-5 h-5 text-green-500" />
                                    </div>
                                </div>
                                <p
                                    v-if="usernameMessage"
                                    :class="[
                                        'text-sm',
                                        usernameStatus === 'available' ? 'text-green-600' :
                                        usernameStatus === 'checking' ? 'text-gray-500' :
                                        'text-red-600'
                                    ]"
                                >
                                    {{ usernameMessage }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    Only lowercase letters, numbers, and underscores. 3-30 characters.
                                </p>
                            </div>

                            <div class="flex justify-end pt-4">
                                <Button
                                    type="button"
                                    :disabled="!canProceedStep1"
                                    @click="nextStep"
                                >
                                    Continue
                                    <ChevronRight class="w-4 h-4 ml-1" />
                                </Button>
                            </div>
                        </div>

                        <!-- Step 2: Profile Info -->
                        <div v-show="currentStep === 2" class="space-y-6">
                            <div class="text-center mb-6">
                                <h2 class="text-xl font-semibold text-gray-900">Profile Information</h2>
                                <p class="text-sm text-gray-500 mt-1">Tell customers about your shop</p>
                            </div>

                            <div class="flex justify-center">
                                <AvatarUpload
                                    :model-value="form.photo"
                                    @update:model-value="handlePhotoChange"
                                />
                            </div>

                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Shop Name <span class="text-red-500">*</span>
                                    </label>
                                    <Input
                                        v-model="form.name"
                                        type="text"
                                        placeholder="My Awesome Shop"
                                        maxlength="100"
                                    />
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Bio
                                    </label>
                                    <textarea
                                        v-model="form.bio"
                                        rows="3"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 accent-focus transition-all resize-none"
                                        placeholder="Tell visitors about your shop..."
                                        maxlength="500"
                                    />
                                    <p class="text-xs text-gray-400 text-right">
                                        {{ form.bio.length }}/500
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        WhatsApp Number <span class="text-red-500">*</span>
                                    </label>
                                    <Input
                                        v-model="form.whatsapp"
                                        type="tel"
                                        placeholder="01712345678"
                                        maxlength="11"
                                    />
                                    <p class="text-xs text-gray-400">
                                        Bangladesh number format (e.g., 01712345678)
                                    </p>
                                </div>
                            </div>

                            <div class="flex justify-between pt-4">
                                <Button
                                    type="button"
                                    variant="outline"
                                    @click="prevStep"
                                >
                                    <ChevronLeft class="w-4 h-4 mr-1" />
                                    Back
                                </Button>
                                <Button
                                    type="button"
                                    :disabled="!canProceedStep2"
                                    @click="nextStep"
                                >
                                    Continue
                                    <ChevronRight class="w-4 h-4 ml-1" />
                                </Button>
                            </div>
                        </div>

                        <!-- Step 3: Theme Color -->
                        <div v-show="currentStep === 3" class="space-y-6">
                            <div class="text-center mb-6">
                                <h2 class="text-xl font-semibold text-gray-900">Choose Your Theme</h2>
                                <p class="text-sm text-gray-500 mt-1">Pick a color that represents your brand</p>
                            </div>

                            <ColorPicker
                                v-model="form.theme_color"
                                label=""
                            />

                            <div class="flex justify-between pt-4">
                                <Button
                                    type="button"
                                    variant="outline"
                                    @click="prevStep"
                                >
                                    <ChevronLeft class="w-4 h-4 mr-1" />
                                    Back
                                </Button>
                                <Button
                                    type="submit"
                                    :disabled="form.processing || !canProceedStep3"
                                    class="min-w-32"
                                >
                                    <Loader2 v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                                    <Sparkles v-else class="w-4 h-4 mr-2" />
                                    {{ form.processing ? 'Creating...' : 'Create Shop' }}
                                </Button>
                            </div>
                        </div>

                        <!-- Error Display -->
                        <div v-if="Object.keys(form.errors).length > 0" class="mt-4 p-4 bg-red-50 rounded-xl">
                            <p class="text-sm text-red-600 font-medium">Please fix the following errors:</p>
                            <ul class="mt-2 text-sm text-red-600 list-disc list-inside">
                                <li v-for="(error, key) in form.errors" :key="key">{{ error }}</li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Footer -->
            <p class="mt-8 text-sm text-gray-500">
                Welcome, {{ user.name }}! You're almost ready to start selling.
            </p>
        </div>
    </div>
</template>
