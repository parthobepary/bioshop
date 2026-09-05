<script setup lang="ts">
import { ref, computed, watch, onUnmounted } from 'vue'
import { Plus, X } from 'lucide-vue-next'
import { uploadToSpaces } from '@/composables/useUpload'
import { mediaUrl } from '@/lib/media'

interface ExistingImage {
    id: number
    url: string
    sort_order: number
}

interface Props {
    // Stored Spaces paths of newly added images (v-model)
    modelValue: string[]
    existingImages?: ExistingImage[]
    maxImages?: number
}

const props = withDefaults(defineProps<Props>(), {
    existingImages: () => [],
    maxImages: 5,
})

const emit = defineEmits<{
    'update:modelValue': [value: string[]]
    'update:existingImages': [value: ExistingImage[]]
}>()

const fileInput = ref<HTMLInputElement | null>(null)

// Local state for existing images
const localExistingImages = ref<ExistingImage[]>([...props.existingImages])

// In-flight uploads (shown with a spinner until they resolve to a path)
interface Pending {
    key: number
    previewUrl: string
}
const pending = ref<Pending[]>([])
let pendingSeq = 0

watch(() => props.existingImages, (newImages) => {
    localExistingImages.value = [...newImages]
}, { deep: true })

const totalImages = computed(() => {
    return localExistingImages.value.length + props.modelValue.length + pending.value.length
})

const canAddMore = computed(() => totalImages.value < props.maxImages)

const openFilePicker = () => {
    if (canAddMore.value) {
        fileInput.value?.click()
    }
}

const handleFileChange = async (event: Event) => {
    const target = event.target as HTMLInputElement
    const files = Array.from(target.files || [])
    if (fileInput.value) fileInput.value.value = ''

    for (const file of files) {
        if (!file.type.startsWith('image/')) {
            alert(`"${file.name}" is not an image file`)
            continue
        }
        if (file.size > 2 * 1024 * 1024) {
            alert(`"${file.name}" is too large (max 2MB)`)
            continue
        }
        if (totalImages.value >= props.maxImages) {
            alert(`Maximum ${props.maxImages} images allowed`)
            break
        }

        const entry: Pending = { key: ++pendingSeq, previewUrl: URL.createObjectURL(file) }
        pending.value.push(entry)

        try {
            const { path } = await uploadToSpaces(file)
            emit('update:modelValue', [...props.modelValue, path])
        } catch {
            alert(`Failed to upload "${file.name}". Please try again.`)
        } finally {
            URL.revokeObjectURL(entry.previewUrl)
            pending.value = pending.value.filter(p => p.key !== entry.key)
        }
    }
}

const removeNewImage = (path: string) => {
    emit('update:modelValue', props.modelValue.filter(p => p !== path))
}

const removeExistingImage = (image: ExistingImage) => {
    localExistingImages.value = localExistingImages.value.filter(img => img.id !== image.id)
    emit('update:existingImages', localExistingImages.value)
}

onUnmounted(() => {
    pending.value.forEach(p => URL.revokeObjectURL(p.previewUrl))
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
                    :src="mediaUrl(image.url)"
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

            <!-- Newly uploaded (committed to Spaces) -->
            <div
                v-for="(path, index) in modelValue"
                :key="`new-${index}`"
                class="group relative aspect-square overflow-hidden rounded-xl bg-slate-100 ring-1 ring-slate-200"
            >
                <img
                    :src="mediaUrl(path)"
                    alt="Product image"
                    class="h-full w-full rounded-xl object-cover"
                />
                <div class="absolute left-2 top-2 rounded-full bg-ink-900 px-2 py-0.5 text-xs text-white shadow-sm">
                    New
                </div>
                <button
                    type="button"
                    class="absolute right-2 top-2 flex h-7 w-7 items-center justify-center rounded-full bg-rose-500 text-white opacity-0 shadow-sm transition-opacity hover:bg-rose-600 group-hover:opacity-100"
                    @click="removeNewImage(path)"
                >
                    <X class="h-4 w-4" />
                </button>
            </div>

            <!-- In-flight uploads -->
            <div
                v-for="item in pending"
                :key="`pending-${item.key}`"
                class="relative aspect-square overflow-hidden rounded-xl bg-slate-100 ring-1 ring-slate-200"
            >
                <img
                    :src="item.previewUrl"
                    alt="Uploading"
                    class="h-full w-full rounded-xl object-cover opacity-50"
                />
                <div class="absolute inset-0 flex items-center justify-center bg-white/40 backdrop-blur-[1px]">
                    <span class="h-6 w-6 animate-spin rounded-full border-2 accent-border border-t-transparent"></span>
                </div>
            </div>

            <!-- Add Button -->
            <button
                v-if="canAddMore"
                type="button"
                class="flex aspect-square flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-line bg-slate-50 p-6 text-center text-slate-400 transition-colors hover-accent-border hover-accent-tint hover-accent-text"
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
