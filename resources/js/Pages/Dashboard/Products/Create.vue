<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import ImageUpload from '@/Components/products/ImageUpload.vue'
import { ArrowLeft, Loader2, Save } from 'lucide-vue-next'

interface Category {
    id: number
    name: string
    sort_order: number
}

interface Props {
    categories: Category[]
}

const props = defineProps<Props>()

defineOptions({
    layout: DashboardLayout,
})

const form = useForm({
    name: '',
    description: '',
    price: '',
    compare_price: '',
    category_id: '',
    status: 'available',
    images: [] as File[],
})

const submit = () => {
    form.post(route('products.store'), {
        forceFormData: true,
    })
}

const statusOptions = [
    { value: 'available', label: 'In Stock', description: 'Product is available for purchase' },
    { value: 'stock_out', label: 'Out of Stock', description: 'Currently unavailable' },
    { value: 'pre_order', label: 'Pre-order', description: 'Available for pre-order' },
]
</script>

<template>
    <Head title="Add Product" />

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <Link
                :href="route('products.index')"
                class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
            >
                <ArrowLeft class="w-5 h-5" />
            </Link>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add Product</h1>
                <p class="text-gray-500 mt-1">Create a new product for your shop</p>
            </div>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <!-- Images -->
            <Card>
                <CardHeader>
                    <CardTitle>Images</CardTitle>
                    <CardDescription>Add photos of your product</CardDescription>
                </CardHeader>
                <CardContent>
                    <ImageUpload
                        v-model="form.images"
                        :max-images="5"
                    />
                    <p v-if="form.errors.images" class="mt-2 text-sm text-red-600">
                        {{ form.errors.images }}
                    </p>
                </CardContent>
            </Card>

            <!-- Basic Info -->
            <Card>
                <CardHeader>
                    <CardTitle>Basic Information</CardTitle>
                    <CardDescription>Enter product details</CardDescription>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Product Name <span class="text-red-500">*</span>
                        </label>
                        <Input
                            v-model="form.name"
                            type="text"
                            placeholder="Enter product name"
                            maxlength="200"
                        />
                        <p v-if="form.errors.name" class="text-sm text-red-600">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea
                            v-model="form.description"
                            rows="4"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all resize-none"
                            placeholder="Describe your product..."
                            maxlength="2000"
                        />
                        <div class="flex justify-between">
                            <p v-if="form.errors.description" class="text-sm text-red-600">
                                {{ form.errors.description }}
                            </p>
                            <p class="text-xs text-gray-400 ml-auto">
                                {{ form.description.length }}/2000
                            </p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Category
                        </label>
                        <select
                            v-model="form.category_id"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all"
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
                        <p v-if="form.errors.category_id" class="text-sm text-red-600">
                            {{ form.errors.category_id }}
                        </p>
                    </div>
                </CardContent>
            </Card>

            <!-- Pricing -->
            <Card>
                <CardHeader>
                    <CardTitle>Pricing</CardTitle>
                    <CardDescription>Set product price</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Price (BDT) <span class="text-red-500">*</span>
                            </label>
                            <Input
                                v-model="form.price"
                                type="number"
                                step="0.01"
                                min="0"
                                placeholder="0.00"
                            />
                            <p v-if="form.errors.price" class="text-sm text-red-600">
                                {{ form.errors.price }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Compare at Price (BDT)
                            </label>
                            <Input
                                v-model="form.compare_price"
                                type="number"
                                step="0.01"
                                min="0"
                                placeholder="0.00"
                            />
                            <p class="text-xs text-gray-400">
                                Original price to show discount
                            </p>
                            <p v-if="form.errors.compare_price" class="text-sm text-red-600">
                                {{ form.errors.compare_price }}
                            </p>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Status -->
            <Card>
                <CardHeader>
                    <CardTitle>Availability</CardTitle>
                    <CardDescription>Set product status</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label
                            v-for="option in statusOptions"
                            :key="option.value"
                            :class="[
                                'relative flex items-start p-4 rounded-xl border-2 cursor-pointer transition-all',
                                form.status === option.value
                                    ? 'border-primary-500 bg-primary-50'
                                    : 'border-gray-200 hover:border-gray-300'
                            ]"
                        >
                            <input
                                type="radio"
                                :value="option.value"
                                v-model="form.status"
                                class="sr-only"
                            />
                            <div>
                                <span class="block text-sm font-medium text-gray-900">
                                    {{ option.label }}
                                </span>
                                <span class="block text-xs text-gray-500 mt-1">
                                    {{ option.description }}
                                </span>
                            </div>
                            <div
                                v-if="form.status === option.value"
                                class="absolute top-3 right-3 w-4 h-4 bg-primary-500 rounded-full flex items-center justify-center"
                            >
                                <div class="w-1.5 h-1.5 bg-white rounded-full" />
                            </div>
                        </label>
                    </div>
                    <p v-if="form.errors.status" class="mt-2 text-sm text-red-600">
                        {{ form.errors.status }}
                    </p>
                </CardContent>
            </Card>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-4">
                <Link :href="route('products.index')">
                    <Button type="button" variant="outline">
                        Cancel
                    </Button>
                </Link>
                <Button type="submit" :disabled="form.processing">
                    <Loader2 v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                    <Save v-else class="w-4 h-4 mr-2" />
                    {{ form.processing ? 'Saving...' : 'Create Product' }}
                </Button>
            </div>
        </form>
    </div>
</template>
