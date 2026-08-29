<script setup lang="ts">
import { computed, ref, watch } from 'vue';

const props = withDefaults(
    defineProps<{
        modelValue?: string | null;
        accept?: string;
        existingUrl?: string | null;
        existingName?: string | null;
        placeholder?: string;
        maxFileSize?: number;
    }>(),
    {
        modelValue: null,
        accept: '',
        existingUrl: null,
        existingName: null,
        placeholder: 'Drop file here or click to browse',
        maxFileSize: 5_000_000,
    },
);

const emit = defineEmits<{
    'update:modelValue': [value: string | null];
}>();

const fileInput = ref<HTMLInputElement | null>(null);
const isDragging = ref(false);
const isUploading = ref(false);
const progress = ref(0);
const errors = ref<string[]>([]);
const localPreviewUrl = ref<string | null>(null);
const uploadedFileInfo = ref<{
    name: string;
    size: number;
    type: string;
} | null>(null);
const isRemoved = ref(false);

const isImageAccept = computed(() => props.accept.includes('image'));

const accepts = computed(() =>
    (props.accept || '*/*')
        .split(',')
        .map((a) => a.trim())
        .filter(Boolean),
);

const displayName = computed(() => {
    if (uploadedFileInfo.value) return uploadedFileInfo.value.name;
    if (props.existingName) return props.existingName;
    return null;
});

const displaySize = computed(() => {
    if (!uploadedFileInfo.value) return null;
    const bytes = uploadedFileInfo.value.size;
    if (bytes < 1024) return `${bytes} B`;
    if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
});

const showImagePreview = computed(() => {
    if (localPreviewUrl.value) return true;
    if (
        isImageAccept.value &&
        props.existingUrl &&
        !props.modelValue &&
        !isRemoved.value
    )
        return true;
    return false;
});

const imagePreviewSrc = computed(() => {
    if (localPreviewUrl.value) return localPreviewUrl.value;
    if (
        isImageAccept.value &&
        props.existingUrl &&
        !props.modelValue &&
        !isRemoved.value
    )
        return props.existingUrl;
    return null;
});

const hasUpload = computed(
    () => !!props.modelValue && !!uploadedFileInfo.value,
);
const hasExisting = computed(
    () =>
        !props.modelValue &&
        !isRemoved.value &&
        (props.existingUrl || props.existingName),
);

watch(
    () => props.modelValue,
    (val) => {
        if (!val) {
            if (fileInput.value) fileInput.value.value = '';
            progress.value = 0;
            if (!isRemoved.value) {
                revokePreview();
                uploadedFileInfo.value = null;
            }
        }
    },
);

function revokePreview(): void {
    if (localPreviewUrl.value) {
        URL.revokeObjectURL(localPreviewUrl.value);
        localPreviewUrl.value = null;
    }
}

function randomString(length: number): string {
    const chars =
        'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    for (let i = 0; i < length; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return result;
}

function formatMaxSize(): string {
    return `${(props.maxFileSize / 1024 / 1024).toFixed(1)}MB`;
}

function isAcceptedFile(file: File): boolean {
    if (accepts.value.includes('*/*')) return true;
    return accepts.value.some((part) => {
        const trimmed = part.trim();
        if (trimmed.startsWith('.')) {
            return file.name.toLowerCase().endsWith(trimmed.toLowerCase());
        }
        if (trimmed.endsWith('/*')) {
            return file.type.startsWith(trimmed.replace('/*', '/'));
        }
        return file.type === trimmed;
    });
}

function openFilePicker(): void {
    if (isUploading.value) return;
    fileInput.value?.click();
}

function handleFileChange(e: Event): void {
    const target = e.target as HTMLInputElement;
    const file = target.files?.[0] ?? null;
    if (file) {
        processFile(file);
    }
}

function handleDrop(e: DragEvent): void {
    isDragging.value = false;
    if (isUploading.value) return;
    const file = e.dataTransfer?.files?.[0] ?? null;
    if (file) {
        processFile(file);
    }
}

function handleDragOver(e: DragEvent): void {
    e.preventDefault();
    isDragging.value = true;
}

function handleDragLeave(): void {
    isDragging.value = false;
}

async function processFile(file: File): Promise<void> {
    errors.value = [];

    if (file.size > props.maxFileSize) {
        errors.value.push(
            `${file.name} exceeds max size of ${formatMaxSize()}`,
        );
        return;
    }

    if (!isAcceptedFile(file)) {
        errors.value.push(`${file.name} is not an accepted file type`);
        return;
    }

    // Set preview and file info immediately
    revokePreview();
    isRemoved.value = false;
    uploadedFileInfo.value = {
        name: file.name,
        size: file.size,
        type: file.type,
    };

    if (file.type.startsWith('image/')) {
        localPreviewUrl.value = URL.createObjectURL(file);
    }

    // Upload directly to Spaces via a presigned URL
    isUploading.value = true;
    progress.value = 0;

    try {
        const ext = file.name.split('.').pop()?.toLowerCase() ?? 'bin';
        const filename = `${randomString(32)}.${ext}`;

        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content');

        const response = await fetch(
            `/upload/presigned-url?name=${encodeURIComponent(filename)}`,
            {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
                },
            },
        );

        if (!response.ok) {
            throw new Error('Failed to get upload URL');
        }

        const data = (await response.json()) as {
            url: string;
            path: string;
            publicUrl?: string;
        };

        const uploaded = await uploadToS3(data.url, file);

        if (!uploaded) {
            throw new Error('Upload failed');
        }

        emit('update:modelValue', data.path);
    } catch {
        errors.value.push('Upload failed. Please try again.');
        revokePreview();
        uploadedFileInfo.value = null;
        emit('update:modelValue', null);
    } finally {
        isUploading.value = false;
        progress.value = 0;
    }
}

function uploadToS3(url: string, file: File): Promise<boolean> {
    return new Promise((resolve) => {
        const xhr = new XMLHttpRequest();

        xhr.upload.addEventListener('progress', (event) => {
            if (event.lengthComputable) {
                progress.value = Math.round((event.loaded / event.total) * 100);
            }
        });

        xhr.onloadend = () => {
            resolve(xhr.status === 200);
        };

        xhr.open('PUT', url, true);
        xhr.setRequestHeader('Content-Type', 'application/octet-stream');
        // Must match the ACL signed into the presigned URL.
        xhr.setRequestHeader('x-amz-acl', 'public-read');
        xhr.send(file);
    });
}

function removeFile(): void {
    isRemoved.value = true;
    revokePreview();
    uploadedFileInfo.value = null;
    errors.value = [];
    if (fileInput.value) fileInput.value.value = '';
    emit('update:modelValue', null);
}

function getFileExtension(name: string): string {
    const parts = name.split('.');
    return parts.length > 1 ? parts.pop()!.toUpperCase() : 'FILE';
}
</script>

<template>
    <div>
        <input
            ref="fileInput"
            type="file"
            :accept="accept"
            class="hidden"
            @change="handleFileChange"
        />

        <div
            class="relative cursor-pointer overflow-hidden rounded-xl border border-dashed transition-colors"
            :class="[
                isDragging
                    ? 'border-indigo-400 bg-indigo-50/50'
                    : 'border-slate-300 bg-slate-50 hover:border-indigo-300 hover:bg-indigo-50/30',
            ]"
            @click="openFilePicker"
            @drop.prevent="handleDrop"
            @dragover.prevent="handleDragOver"
            @dragleave="handleDragLeave"
        >
            <!-- Image preview (new upload or existing) -->
            <div v-if="showImagePreview" class="p-3">
                <div class="flex items-start gap-4">
                    <div class="relative shrink-0">
                        <img
                            :src="imagePreviewSrc!"
                            :alt="displayName ?? 'Preview'"
                            class="h-28 w-28 rounded-xl border border-slate-200 object-cover"
                        />
                        <span
                            v-if="!hasUpload && hasExisting"
                            class="absolute -left-1.5 -top-1.5 rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-medium text-slate-500"
                        >
                            Current
                        </span>
                    </div>
                    <div class="flex min-w-0 flex-1 flex-col gap-1.5 py-1">
                        <p
                            v-if="displayName"
                            class="truncate text-sm font-medium text-slate-900"
                        >
                            {{ displayName }}
                        </p>
                        <p v-if="displaySize" class="text-xs text-slate-500">
                            {{ displaySize }}
                        </p>
                        <p class="text-xs text-slate-500">
                            {{
                                hasUpload
                                    ? 'Click or drop to replace'
                                    : 'Click or drop to replace current image'
                            }}
                        </p>
                        <button
                            type="button"
                            class="mt-1 inline-flex w-fit items-center gap-1 rounded-lg bg-rose-50 px-2 py-1 text-xs font-medium text-rose-600 transition-colors hover:bg-rose-100"
                            @click.stop="removeFile"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="12"
                                height="12"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                            Remove
                        </button>
                    </div>
                </div>
            </div>

            <!-- Non-image file uploaded -->
            <div v-else-if="hasUpload && uploadedFileInfo" class="p-3">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-100"
                    >
                        <span class="text-xs font-bold text-slate-500">
                            {{ getFileExtension(uploadedFileInfo.name) }}
                        </span>
                    </div>
                    <div class="flex min-w-0 flex-1 flex-col gap-0.5">
                        <p class="truncate text-sm font-medium text-slate-900">
                            {{ uploadedFileInfo.name }}
                        </p>
                        <p class="text-xs text-slate-500">
                            {{ displaySize }} &middot; Click or drop to replace
                        </p>
                    </div>
                    <button
                        type="button"
                        class="shrink-0 rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-600"
                        title="Remove file"
                        @click.stop="removeFile"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Existing non-image file -->
            <div v-else-if="hasExisting" class="p-3">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-100"
                    >
                        <span class="text-xs font-bold text-slate-500">
                            {{
                                existingName
                                    ? getFileExtension(existingName)
                                    : 'FILE'
                            }}
                        </span>
                    </div>
                    <div class="flex min-w-0 flex-1 flex-col gap-0.5">
                        <p class="truncate text-sm font-medium text-slate-900">
                            {{ existingName ?? 'Current file' }}
                        </p>
                        <p class="text-xs text-slate-500">
                            Click or drop to replace current file
                        </p>
                    </div>
                    <button
                        type="button"
                        class="shrink-0 rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-600"
                        title="Remove file"
                        @click.stop="removeFile"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Empty state -->
            <div v-else class="flex flex-col items-center gap-2 px-4 py-8">
                <div class="rounded-full bg-slate-100 p-3">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="text-slate-400"
                    >
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="17 8 12 3 7 8" />
                        <line x1="12" x2="12" y1="3" y2="15" />
                    </svg>
                </div>
                <div class="text-center">
                    <p class="text-sm font-medium text-slate-700">
                        {{ placeholder }}
                    </p>
                    <p
                        v-if="accept && accept !== '*/*'"
                        class="mt-0.5 text-xs text-slate-400"
                    >
                        Accepted: {{ accept }}
                    </p>
                    <p v-if="maxFileSize" class="mt-0.5 text-xs text-slate-400">
                        Max size: {{ formatMaxSize() }}
                    </p>
                </div>
            </div>

            <!-- Progress bar -->
            <div v-if="progress > 0" class="px-3 pb-3">
                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-200">
                    <div
                        class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 transition-all duration-300"
                        :style="{ width: `${progress}%` }"
                    />
                </div>
                <p class="mt-1 text-center text-xs text-slate-500">
                    {{ progress }}%
                </p>
            </div>

            <!-- Errors -->
            <div v-if="errors.length" class="px-3 pb-3">
                <ul class="space-y-1 text-xs text-rose-600">
                    <li v-for="(err, idx) in errors" :key="idx">
                        {{ err }}
                    </li>
                </ul>
            </div>

            <!-- Upload overlay -->
            <div
                v-if="isUploading"
                class="absolute inset-0 flex items-center justify-center rounded-xl bg-white/60 backdrop-blur-[1px]"
            >
                <span class="animate-pulse text-sm text-slate-500">
                    Uploading...
                </span>
            </div>
        </div>
    </div>
</template>
