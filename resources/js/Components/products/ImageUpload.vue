<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import draggable from 'vuedraggable'
import { Plus, X, ImageIcon, GripVertical } from 'lucide-vue-next'

interface ExistingImage {
    id: number
    url: string
    sort_order: number
}

interface Props {
    modelValue: File[]
    existingImages?: ExistingImage[]
    maxImages?: number
}

const props = withDefaults(defineProps<Props>(), {
    existingImages: () => [],
    maxImages: 5,
})

const emit = defineEmits<{
    'update:modelValue': [value: File[]]
    'update:existingImages': [value: ExistingImage[]]
}>()

const fileInput = ref<HTMLInputElement | null>(null)

// Local state for existing images (for drag and drop)
const localExistingImages = ref<ExistingImage[]>([...props.existingImages])

// Preview URLs for new files
const previewUrls = ref<Map<File, string>>(new Map())

// Sync existing images from props
watch(() => props.existingImages, (newImages) => {
    localExistingImages.value = [...newImages]
}, { deep: true })

// Total images count
const totalImages = computed(() => {
    return localExistingImages.value.length + props.modelValue.length
})

const canAddMore = computed(() => {
    return totalImages.value < props.maxImages
})

const openFilePicker = () => {
    if (canAddMore.value) {
        fileInput.value?.click()
    }
}

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement
    const files = Array.from(target.files || [])

    const validFiles: File[] = []

    for (const file of files) {
        if (!file.type.startsWith('image/')) {
            alert(`"${file.name}" is not an image file`)
            continue
        }
        if (file.size > 2 * 1024 * 1024) {
            alert(`"${file.name}" is too large (max 2MB)`)
            continue
        }
        if (totalImages.value + validFiles.length >= props.maxImages) {
            alert(`Maximum ${props.maxImages} images allowed`)
            break
        }

        // Create preview URL
        const url = URL.createObjectURL(file)
        previewUrls.value.set(file, url)

        validFiles.push(file)
    }

    emit('update:modelValue', [...props.modelValue, ...validFiles])

    // Reset input
    if (fileInput.value) {
        fileInput.value.value = ''
    }
}

const removeNewFile = (file: File) => {
    // Revoke preview URL
    const url = previewUrls.value.get(file)
    if (url) {
        URL.revokeObjectURL(url)
        previewUrls.value.delete(file)
    }

    const newFiles = props.modelValue.filter(f => f !== file)
    emit('update:modelValue', newFiles)
}

const removeExistingImage = (image: ExistingImage) => {
    localExistingImages.value = localExistingImages.value.filter(img => img.id !== image.id)
    emit('update:existingImages', localExistingImages.value)
}

const getPreviewUrl = (file: File) => {
    return previewUrls.value.get(file) || ''
}

// Cleanup preview URLs on unmount
import { onUnmounted } from 'vue'
onUnmounted(() => {
    previewUrls.value.forEach(url => URL.revokeObjectURL(url))
})
</script>

<template>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <label class="block text-sm font-medium text-gray-700">
                Product Images
            </label>
            <span class="text-sm text-gray-500">
                {{ totalImages }}/{{ maxImages }} images
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
            <!-- Existing Images -->
            <div
                v-for="image in localExistingImages"
                :key="`existing-${image.id}`"
                class="relative aspect-square bg-gray-100 rounded-xl overflow-hidden group"
            >
                <img
                    :src="`/storage/${image.url}`"
                    alt="Product image"
                    class="w-full h-full object-cover"
                />
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <button
                        type="button"
                        class="p-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors"
                        @click="removeExistingImage(image)"
                    >
                        <X class="w-4 h-4" />
                    </button>
                </div>
            </div>

            <!-- New Files Preview -->
            <div
                v-for="(file, index) in modelValue"
                :key="`new-${index}`"
                class="relative aspect-square bg-gray-100 rounded-xl overflow-hidden group"
            >
                <img
                    :src="getPreviewUrl(file)"
                    :alt="file.name"
                    class="w-full h-full object-cover"
                />
                <div class="absolute top-2 left-2 px-2 py-0.5 bg-primary-500 text-white text-xs rounded-full">
                    New
                </div>
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <button
                        type="button"
                        class="p-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors"
                        @click="removeNewFile(file)"
                    >
                        <X class="w-4 h-4" />
                    </button>
                </div>
            </div>

            <!-- Add Button -->
            <button
                v-if="canAddMore"
                type="button"
                class="aspect-square border-2 border-dashed border-gray-300 rounded-xl flex flex-col items-center justify-center gap-2 text-gray-400 hover:border-primary-500 hover:text-primary-500 transition-colors"
                @click="openFilePicker"
            >
                <Plus class="w-8 h-8" />
                <span class="text-xs">Add Image</span>
            </button>
        </div>

        <p class="text-xs text-gray-400">
            Upload up to {{ maxImages }} images. Max 2MB each. First image will be the main image.
        </p>

        <input
            ref="fileInput"
            type="file"
            accept="image/*"
            multiple
            class="hidden"
            @change="handleFileChange"
        />
    </div>
</template>
