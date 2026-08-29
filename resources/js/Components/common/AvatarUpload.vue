<script setup lang="ts">
import { ref, computed } from 'vue'
import { Camera, X, User } from 'lucide-vue-next'

interface Props {
    modelValue?: File | null
    currentPhoto?: string | null
    size?: 'sm' | 'md' | 'lg'
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: null,
    currentPhoto: null,
    size: 'lg',
})

const emit = defineEmits<{
    'update:modelValue': [value: File | null]
    'remove': []
}>()

const previewUrl = ref<string | null>(null)
const fileInput = ref<HTMLInputElement | null>(null)

const sizeClasses = computed(() => {
    switch (props.size) {
        case 'sm':
            return 'w-16 h-16'
        case 'md':
            return 'w-24 h-24'
        case 'lg':
        default:
            return 'w-32 h-32'
    }
})

const iconSize = computed(() => {
    switch (props.size) {
        case 'sm':
            return 16
        case 'md':
            return 24
        case 'lg':
        default:
            return 32
    }
})

const displayImage = computed(() => {
    if (previewUrl.value) {
        return previewUrl.value
    }
    if (props.currentPhoto) {
        return `/storage/${props.currentPhoto}`
    }
    return null
})

const handleClick = () => {
    fileInput.value?.click()
}

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement
    const file = target.files?.[0]

    if (file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file')
            return
        }

        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('Image must be less than 2MB')
            return
        }

        // Create preview
        const reader = new FileReader()
        reader.onload = (e) => {
            previewUrl.value = e.target?.result as string
        }
        reader.readAsDataURL(file)

        emit('update:modelValue', file)
    }
}

const removePhoto = () => {
    previewUrl.value = null
    emit('update:modelValue', null)
    emit('remove')
    if (fileInput.value) {
        fileInput.value.value = ''
    }
}
</script>

<template>
    <div class="flex flex-col items-center gap-3">
        <div class="relative">
            <!-- Avatar Circle -->
            <div
                :class="[
                    sizeClasses,
                    'rounded-full overflow-hidden bg-gradient-to-br from-indigo-100 to-purple-200 flex items-center justify-center cursor-pointer ring-1 ring-slate-200 shadow-sm hover:shadow-md transition-shadow',
                ]"
                @click="handleClick"
            >
                <img
                    v-if="displayImage"
                    :src="displayImage"
                    alt="Profile photo"
                    class="w-full h-full object-cover"
                />
                <User
                    v-else
                    :size="iconSize"
                    class="text-indigo-400"
                />
            </div>

            <!-- Camera Button -->
            <button
                type="button"
                class="absolute bottom-0 right-0 p-2 bg-indigo-600 rounded-full text-white shadow-sm ring-2 ring-white hover:bg-indigo-700 transition-colors"
                @click="handleClick"
            >
                <Camera :size="16" />
            </button>

            <!-- Remove Button -->
            <button
                v-if="displayImage"
                type="button"
                class="absolute -top-1 -right-1 p-1.5 bg-rose-500 rounded-full text-white shadow-sm ring-2 ring-white hover:bg-rose-600 transition-colors"
                @click.stop="removePhoto"
            >
                <X :size="12" />
            </button>
        </div>

        <!-- Hidden File Input -->
        <input
            ref="fileInput"
            type="file"
            accept="image/*"
            class="hidden"
            @change="handleFileChange"
        />

        <!-- Upload control -->
        <button
            type="button"
            class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 transition-colors"
            @click="handleClick"
        >
            Upload photo
        </button>

        <!-- Helper Text -->
        <p class="text-sm text-slate-500">
            PNG or JPG, up to 2MB
        </p>
    </div>
</template>
