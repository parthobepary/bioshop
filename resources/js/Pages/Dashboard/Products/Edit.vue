<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import ImageUpload from '@/Components/products/ImageUpload.vue'
import { ArrowLeft, Loader2, Save } from 'lucide-vue-next'

interface ProductImage {
    id: number
    url: string
    sort_order: number
}

interface Category {
    id: number
    name: string
    sort_order: number
}

interface Product {
    id: number
    name: string
    description: string | null
    price: number
    compare_price: number | null
    status: 'available' | 'stock_out' | 'pre_order'
    category_id: number | null
    is_active: boolean
    images: ProductImage[]
}

interface Props {
    product: Product
    categories: Category[]
}

const props = defineProps<Props>()

defineOptions({
    layout: DashboardLayout,
})

// Track existing images for deletion
const existingImages = ref<ProductImage[]>([...props.product.images])

const form = useForm({
    name: props.product.name,
    description: props.product.description || '',
    price: props.product.price.toString(),
    compare_price: props.product.compare_price?.toString() || '',
    category_id: props.product.category_id?.toString() || '',
    status: props.product.status,
    images: [] as string[],
    existing_images: props.product.images.map(img => img.id),
})

const handleExistingImagesUpdate = (images: ProductImage[]) => {
    existingImages.value = images
    form.existing_images = images.map(img => img.id)
}

const submit = () => {
    form.put(route('products.update', props.product.id))
}

const statusOptions = [
    { value: 'available', label: 'In Stock', description: 'Product is available for purchase' },
    { value: 'stock_out', label: 'Out of Stock', description: 'Currently unavailable' },
    { value: 'pre_order', label: 'Pre-order', description: 'Available for pre-order' },
]
</script>

<template>
    <Head :title="`Edit ${product.name}`" />

    <div class="mx-auto max-w-3xl space-y-8">
        <!-- Header -->
        <div class="flex items-start gap-3">
            <Link
                :href="route('products.index')"
                class="mt-0.5 flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition-colors hover:bg-slate-50 hover:text-slate-700"
            >
                <ArrowLeft class="h-5 w-5" />
            </Link>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Product</h1>
                <p class="mt-0.5 text-sm text-slate-500">Update product details</p>
            </div>
        </div>

        <form @submit.prevent="submit" class="space-y-8">
            <!-- Images -->
            <div class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Images</h2>
                <p class="mt-0.5 text-sm text-slate-500">Manage product photos</p>
                <div class="mt-5">
                    <ImageUpload
                        v-model="form.images"
                        :existing-images="existingImages"
                        :max-images="5"
                        @update:existing-images="handleExistingImagesUpdate"
                    />
                    <p v-if="form.errors.images" class="mt-1 text-sm text-rose-600">
                        {{ form.errors.images }}
                    </p>
                </div>
            </div>

            <!-- Basic Info -->
            <div class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Basic Information</h2>
                <p class="mt-0.5 text-sm text-slate-500">Enter product details</p>
                <div class="mt-5 space-y-5">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">
                            Product Name <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="Enter product name"
                            maxlength="200"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder:text-slate-400 transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10"
                        />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-rose-600">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">
                            Description
                        </label>
                        <textarea
                            v-model="form.description"
                            rows="4"
                            class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder:text-slate-400 transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10"
                            placeholder="Describe your product..."
                            maxlength="2000"
                        />
                        <div class="flex justify-between">
                            <p v-if="form.errors.description" class="mt-1 text-sm text-rose-600">
                                {{ form.errors.description }}
                            </p>
                            <p class="ml-auto text-xs text-slate-400">
                                {{ form.description.length }}/2000
                            </p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">
                            Category
                        </label>
                        <select
                            v-model="form.category_id"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10"
                        >
                            <option value="">No category</option>
                            <option
                                v-for="category in categories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.category_id" class="mt-1 text-sm text-rose-600">
                            {{ form.errors.category_id }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Pricing</h2>
                <p class="mt-0.5 text-sm text-slate-500">Set product price</p>
                <div class="mt-5 grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">
                            Price (BDT) <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.price"
                            type="number"
                            step="0.01"
                            min="0"
                            placeholder="0.00"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder:text-slate-400 transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10"
                        />
                        <p v-if="form.errors.price" class="mt-1 text-sm text-rose-600">
                            {{ form.errors.price }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">
                            Compare at Price (BDT)
                        </label>
                        <input
                            v-model="form.compare_price"
                            type="number"
                            step="0.01"
                            min="0"
                            placeholder="0.00"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder:text-slate-400 transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10"
                        />
                        <p class="text-xs text-slate-400">
                            Original price to show discount
                        </p>
                        <p v-if="form.errors.compare_price" class="mt-1 text-sm text-rose-600">
                            {{ form.errors.compare_price }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Availability</h2>
                <p class="mt-0.5 text-sm text-slate-500">Set product status</p>
                <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <label
                        v-for="option in statusOptions"
                        :key="option.value"
                        :class="[
                            'relative flex cursor-pointer items-start rounded-xl border p-4 transition-all',
                            form.status === option.value
                                ? 'border-indigo-500 bg-indigo-50 ring-1 ring-indigo-500/20'
                                : 'border-slate-200 hover:border-slate-300'
                        ]"
                    >
                        <input
                            type="radio"
                            :value="option.value"
                            v-model="form.status"
                            class="sr-only"
                        />
                        <div>
                            <span class="block text-sm font-medium text-slate-900">
                                {{ option.label }}
                            </span>
                            <span class="mt-1 block text-xs text-slate-500">
                                {{ option.description }}
                            </span>
                        </div>
                        <div
                            v-if="form.status === option.value"
                            class="absolute right-3 top-3 flex h-4 w-4 items-center justify-center rounded-full bg-gradient-to-r from-indigo-500 to-purple-500"
                        >
                            <div class="h-1.5 w-1.5 rounded-full bg-white" />
                        </div>
                    </label>
                </div>
                <p v-if="form.errors.status" class="mt-2 text-sm text-rose-600">
                    {{ form.errors.status }}
                </p>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3">
                <Link
                    :href="route('products.index')"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50"
                >
                    Cancel
                </Link>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-500/30 disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0 disabled:hover:shadow-sm"
                >
                    <Loader2 v-if="form.processing" class="h-4 w-4 animate-spin" />
                    <Save v-else class="h-4 w-4" />
                    {{ form.processing ? 'Saving...' : 'Update Product' }}
                </button>
            </div>
        </form>
    </div>
</template>
