<script setup lang="ts">
import { computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import { mediaUrl } from '@/lib/media'
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

const emit = defineEmits<{
    delete: [product: { id: number; name: string }]
}>()

const primaryImage = computed(() => {
    if (props.product.images && props.product.images.length > 0) {
        const sorted = [...props.product.images].sort((a, b) => a.sort_order - b.sort_order)
        return mediaUrl(sorted[0].url)
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
    emit('delete', props.product)
}
</script>

<template>
    <div
        :class="[
            'group overflow-hidden rounded-2xl border border-line bg-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-200/60',
            product.is_active ? '' : 'opacity-60'
        ]"
    >
        <!-- Image -->
        <div class="relative aspect-square overflow-hidden">
            <img
                v-if="primaryImage"
                :src="primaryImage"
                :alt="product.name"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
            />
            <div
                v-else
                class="flex h-full w-full items-center justify-center bg-slate-100"
            >
                <ImageIcon class="h-12 w-12 text-slate-300" />
            </div>

            <!-- Drag Handle -->
            <div class="absolute left-3 top-3 z-10 cursor-grab rounded-lg bg-white/80 p-1.5 text-slate-400 opacity-0 backdrop-blur transition-opacity hover:text-slate-600 active:cursor-grabbing group-hover:opacity-100">
                <GripVertical class="h-4 w-4" />
            </div>

            <!-- Discount Badge -->
            <div
                v-if="discountPercentage > 0"
                class="absolute right-3 top-3 z-10 rounded-lg bg-ink-900 px-2 py-1 text-xs font-bold text-white shadow-sm"
            >
                -{{ discountPercentage }}%
            </div>

            <!-- Image count badge -->
            <div
                v-if="product.images && product.images.length > 1"
                class="absolute bottom-2 right-2 rounded-lg bg-black/60 px-2 py-1 text-xs text-white"
            >
                +{{ product.images.length - 1 }}
            </div>
        </div>

        <!-- Content -->
        <div class="p-4">
            <!-- Category -->
            <div
                v-if="product.category"
                class="mb-1 flex items-center gap-1 text-xs text-slate-500"
            >
                <Tag class="h-3 w-3" />
                {{ product.category.name }}
            </div>

            <!-- Name -->
            <h3 class="truncate font-semibold text-slate-900" :title="product.name">
                {{ product.name }}
            </h3>

            <!-- Price -->
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-lg font-bold accent-text">
                    {{ formatPrice(product.price) }}
                </span>
                <span
                    v-if="product.compare_price"
                    class="text-sm text-slate-400 line-through"
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
        <div class="flex items-center justify-between border-t border-line px-4 py-3">
            <div class="flex items-center gap-1">
                <Button
                    variant="ghost"
                    size="sm"
                    @click="toggleActive"
                    :title="product.is_active ? 'Disable product' : 'Enable product'"
                >
                    <ToggleRight v-if="product.is_active" class="h-5 w-5 text-emerald-500" />
                    <ToggleLeft v-else class="h-5 w-5 text-slate-400" />
                </Button>
            </div>

            <div class="flex items-center gap-1">
                <Link
                    :href="route('products.edit', product.id)"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100"
                    title="Edit product"
                >
                    <Pencil class="h-4 w-4" />
                </Link>
                <Button
                    variant="ghost"
                    size="sm"
                    @click="deleteProduct"
                    title="Delete product"
                    class="text-slate-500 hover:bg-rose-50 hover:text-rose-600"
                >
                    <Trash2 class="h-4 w-4" />
                </Button>
            </div>
        </div>
    </div>
</template>
