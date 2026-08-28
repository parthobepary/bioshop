<script setup lang="ts">
import { computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import StockBadge from './StockBadge.vue'
import {
    GripVertical,
    Pencil,
    Trash2,
    ToggleLeft,
    ToggleRight,
    ImageIcon,
    Tag,
} from 'lucide-vue-next'

interface ProductImage {
    id: number
    url: string
    sort_order: number
}

interface Category {
    id: number
    name: string
}

interface Product {
    id: number
    name: string
    description: string | null
    price: number
    compare_price: number | null
    status: 'available' | 'stock_out' | 'pre_order'
    is_active: boolean
    sort_order: number
    category: Category | null
    images: ProductImage[]
}

interface Props {
    product: Product
}

const props = defineProps<Props>()

const primaryImage = computed(() => {
    if (props.product.images && props.product.images.length > 0) {
        const sorted = [...props.product.images].sort((a, b) => a.sort_order - b.sort_order)
        return `/storage/${sorted[0].url}`
    }
    return null
})

const discountPercentage = computed(() => {
    if (props.product.compare_price && props.product.compare_price > props.product.price) {
        return Math.round((1 - props.product.price / props.product.compare_price) * 100)
    }
    return 0
})

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-BD', {
        style: 'currency',
        currency: 'BDT',
        minimumFractionDigits: 0,
    }).format(price)
}

const toggleActive = () => {
    router.post(route('products.toggle', props.product.id), {}, {
        preserveScroll: true,
    })
}

const deleteProduct = () => {
    if (confirm(`Are you sure you want to delete "${props.product.name}"?`)) {
        router.delete(route('products.destroy', props.product.id), {
            preserveScroll: true,
        })
    }
}
</script>

<template>
    <div
        :class="[
            'group relative bg-white rounded-2xl border overflow-hidden transition-all duration-200',
            product.is_active
                ? 'border-gray-200 hover:border-primary-300 hover:shadow-lg'
                : 'border-gray-200 bg-gray-50 opacity-60'
        ]"
    >
        <!-- Drag Handle -->
        <div class="absolute top-3 left-3 z-10 cursor-grab active:cursor-grabbing p-1.5 bg-white/80 backdrop-blur rounded-lg text-gray-400 hover:text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity">
            <GripVertical class="w-4 h-4" />
        </div>

        <!-- Discount Badge -->
        <div
            v-if="discountPercentage > 0"
            class="absolute top-3 right-3 z-10 px-2 py-1 bg-red-500 text-white text-xs font-bold rounded-lg"
        >
            -{{ discountPercentage }}%
        </div>

        <!-- Image -->
        <div class="aspect-square bg-gray-100 relative">
            <img
                v-if="primaryImage"
                :src="primaryImage"
                :alt="product.name"
                class="w-full h-full object-cover"
            />
            <div
                v-else
                class="w-full h-full flex items-center justify-center"
            >
                <ImageIcon class="w-12 h-12 text-gray-300" />
            </div>

            <!-- Image count badge -->
            <div
                v-if="product.images && product.images.length > 1"
                class="absolute bottom-2 right-2 px-2 py-1 bg-black/60 text-white text-xs rounded-lg"
            >
                +{{ product.images.length - 1 }}
            </div>
        </div>

        <!-- Content -->
        <div class="p-4">
            <!-- Category -->
            <div
                v-if="product.category"
                class="flex items-center gap-1 text-xs text-gray-500 mb-1"
            >
                <Tag class="w-3 h-3" />
                {{ product.category.name }}
            </div>

            <!-- Name -->
            <h3 class="font-medium text-gray-900 truncate" :title="product.name">
                {{ product.name }}
            </h3>

            <!-- Price -->
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-lg font-bold text-gray-900">
                    {{ formatPrice(product.price) }}
                </span>
                <span
                    v-if="product.compare_price"
                    class="text-sm text-gray-400 line-through"
                >
                    {{ formatPrice(product.compare_price) }}
                </span>
            </div>

            <!-- Status -->
            <div class="mt-2">
                <StockBadge :status="product.status" />
            </div>
        </div>

        <!-- Actions -->
        <div class="px-4 pb-4 flex items-center justify-between">
            <div class="flex items-center gap-1">
                <Button
                    variant="ghost"
                    size="sm"
                    @click="toggleActive"
                    :title="product.is_active ? 'Disable product' : 'Enable product'"
                >
                    <ToggleRight v-if="product.is_active" class="w-5 h-5 text-green-500" />
                    <ToggleLeft v-else class="w-5 h-5 text-gray-400" />
                </Button>
            </div>

            <div class="flex items-center gap-1">
                <Link
                    :href="route('products.edit', product.id)"
                    class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors"
                    title="Edit product"
                >
                    <Pencil class="w-4 h-4" />
                </Link>
                <Button
                    variant="ghost"
                    size="sm"
                    @click="deleteProduct"
                    title="Delete product"
                >
                    <Trash2 class="w-4 h-4 text-red-400" />
                </Button>
            </div>
        </div>
    </div>
</template>
