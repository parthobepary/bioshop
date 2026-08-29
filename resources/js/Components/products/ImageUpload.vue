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
            <label class="block text-sm font-medium text-slate-700">
                Product Images
            </label>
            <span class="text-sm text-slate-500">
                {{ totalImages }}/{{ maxImages }} images
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-5">
            <!-- Existing Images -->
            <div
                v-for="image in localExistingImages"
                :key="`existing-${image.id}`"
                class="group relative aspect-square overflow-hidden rounded-xl bg-slate-100 ring-1 ring-slate-200"
            >
                <img
                    :src="`/storage/${image.url}`"
                    alt="Product image"
                    class="h-full w-full rounded-xl object-cover"
                />
                <button
                    type="button"
                    class="absolute right-2 top-2 flex h-7 w-7 items-center justify-center rounded-full bg-rose-500 text-white opacity-0 shadow-sm transition-opacity hover:bg-rose-600 group-hover:opacity-100"
                    @click="removeExistingImage(image)"
                >
                    <X class="h-4 w-4" />
                </button>
            </div>

            <!-- New Files Preview -->
            <div
                v-for="(file, index) in modelValue"
                :key="`new-${index}`"
                class="group relative aspect-square overflow-hidden rounded-xl bg-slate-100 ring-1 ring-slate-200"
            >
                <img
                    :src="getPreviewUrl(file)"
                    :alt="file.name"
                    class="h-full w-full rounded-xl object-cover"
                />
                <div class="absolute left-2 top-2 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 px-2 py-0.5 text-xs text-white shadow-sm">
                    New
                </div>
                <button
                    type="button"
                    class="absolute right-2 top-2 flex h-7 w-7 items-center justify-center rounded-full bg-rose-500 text-white opacity-0 shadow-sm transition-opacity hover:bg-rose-600 group-hover:opacity-100"
                    @click="removeNewFile(file)"
                >
                    <X class="h-4 w-4" />
                </button>
            </div>

            <!-- Add Button -->
            <button
                v-if="canAddMore"
                type="button"
                class="flex aspect-square flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 p-6 text-center text-slate-400 transition-colors hover:border-indigo-400 hover:bg-indigo-50/40 hover:text-indigo-500"
                @click="openFilePicker"
            >
                <Plus class="h-8 w-8" />
                <span class="text-xs">Add Image</span>
            </button>
        </div>

        <p class="text-xs text-slate-400">
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
