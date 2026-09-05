<script setup lang="ts">
import { ref, computed } from 'vue'
import ProductModal from './ProductModal.vue'
import { ShoppingBag, Tag, MessageCircle } from 'lucide-vue-next'
import { mediaUrl } from '@/lib/media'

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
    images: ProductImage[]
    category: Category | null
}

interface Props {
    products: Product[]
    categories: Category[]
    whatsapp: string | null
    profileId: number
}

const props = defineProps<Props>()

const selectedCategory = ref<number | null>(null)
const showModal = ref(false)
const selectedProduct = ref<Product | null>(null)

const filteredProducts = computed(() => {
    if (!selectedCategory.value) return props.products
    return props.products.filter(p => p.category?.id === selectedCategory.value)
})

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-BD', {
        style: 'currency',
        currency: 'BDT',
        minimumFractionDigits: 0,
    }).format(price).replace('BDT', '৳')
}

const getProductImage = (product: Product) => {
    if (!product.images?.length) return null
    const sortedImages = [...product.images].sort((a, b) => a.sort_order - b.sort_order)
    return mediaUrl(sortedImages[0].url)
}

const getDiscount = (product: Product) => {
    if (!product.compare_price || product.compare_price <= product.price) return null
    return Math.round(((product.compare_price - product.price) / product.compare_price) * 100)
}

const openProduct = (product: Product) => {
    selectedProduct.value = product
    showModal.value = true
}
</script>

<template>
    <div v-if="products.length > 0" class="mb-6">
        <!-- Section Header -->
        <div class="mb-4 flex items-center gap-3 px-1">
            <div class="theme-gradient flex h-9 w-9 items-center justify-center rounded-xl text-white shadow-sm">
                <ShoppingBag class="h-5 w-5" />
            </div>
            <h2 class="text-lg font-bold text-slate-900">Products</h2>
            <span class="ml-auto rounded-full bg-white/70 px-2.5 py-0.5 text-xs font-semibold text-slate-500 backdrop-blur">
                {{ products.length }} {{ products.length === 1 ? 'item' : 'items' }}
            </span>
        </div>

        <!-- Category Filter -->
        <div
            v-if="categories.length > 0"
            class="scrollbar-hide -mx-4 mb-4 flex gap-2 overflow-x-auto px-4 pb-2"
        >
            <button
                :class="[
                    'whitespace-nowrap rounded-full px-4 py-2 text-sm font-semibold transition-all',
                    !selectedCategory
                        ? 'theme-gradient text-white shadow-sm'
                        : 'border border-white/60 bg-white/70 text-slate-600 backdrop-blur hover:bg-white'
                ]"
                @click="selectedCategory = null"
            >
                All
            </button>
            <button
                v-for="category in categories"
                :key="category.id"
                :class="[
                    'whitespace-nowrap rounded-full px-4 py-2 text-sm font-semibold transition-all',
                    selectedCategory === category.id
                        ? 'theme-gradient text-white shadow-sm'
                        : 'border border-white/60 bg-white/70 text-slate-600 backdrop-blur hover:bg-white'
                ]"
                @click="selectedCategory = category.id"
            >
                {{ category.name }}
            </button>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-2 gap-3.5">
            <button
                v-for="product in filteredProducts"
                :key="product.id"
                class="group overflow-hidden rounded-2xl border border-white/60 bg-white/85 text-left shadow-sm shadow-slate-900/5 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-900/10"
                @click="openProduct(product)"
            >
                <!-- Image -->
                <div class="relative aspect-square overflow-hidden bg-slate-100">
                    <img
                        v-if="getProductImage(product)"
                        :src="getProductImage(product)!"
                        :alt="product.name"
                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                    />
                    <div v-else class="flex h-full w-full items-center justify-center">
                        <ShoppingBag class="h-10 w-10 text-slate-300" />
                    </div>

                    <!-- Discount Badge -->
                    <div
                        v-if="getDiscount(product)"
                        class="absolute left-2 top-2 flex items-center gap-0.5 rounded-full bg-error-600 px-2 py-1 text-xs font-bold text-white shadow-sm"
                    >
                        <Tag class="h-3 w-3" />
                        {{ getDiscount(product) }}%
                    </div>

                    <!-- Stock Out Overlay -->
                    <div
                        v-if="product.status === 'stock_out'"
                        class="absolute inset-0 flex items-center justify-center bg-slate-900/55 backdrop-blur-[1px]"
                    >
                        <span class="rounded-full bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow">
                            Out of Stock
                        </span>
                    </div>

                    <!-- Pre-order Badge -->
                    <div
                        v-else-if="product.status === 'pre_order'"
                        class="absolute right-2 top-2 rounded-full bg-warning-500 px-2 py-1 text-xs font-bold text-white shadow-sm"
                    >
                        Pre-order
                    </div>

                    <!-- Hover CTA -->
                    <div class="pointer-events-none absolute inset-x-2 bottom-2 translate-y-3 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100">
                        <span class="theme-gradient flex items-center justify-center gap-1.5 rounded-xl py-2 text-xs font-semibold text-white shadow-lg">
                            <MessageCircle class="h-3.5 w-3.5" /> Tap to order
                        </span>
                    </div>
                </div>

                <!-- Info -->
                <div class="p-3">
                    <p v-if="product.category" class="mb-1 truncate text-[11px] font-medium uppercase tracking-wide text-slate-400">
                        {{ product.category.name }}
                    </p>
                    <h3 class="mb-1.5 line-clamp-2 text-sm font-semibold text-slate-800 group-hover:text-slate-900">
                        {{ product.name }}
                    </h3>
                    <div class="flex items-baseline gap-1.5">
                        <span class="theme-text text-base font-extrabold">
                            {{ formatPrice(product.price) }}
                        </span>
                        <span
                            v-if="product.compare_price && product.compare_price > product.price"
                            class="text-xs text-slate-400 line-through"
                        >
                            {{ formatPrice(product.compare_price) }}
                        </span>
                    </div>
                </div>
            </button>
        </div>

        <!-- Empty State for filtered -->
        <div
            v-if="filteredProducts.length === 0 && selectedCategory"
            class="py-12 text-center"
        >
            <p class="text-slate-500">No products in this category</p>
            <button
                class="theme-text mt-2 text-sm font-semibold hover:underline"
                @click="selectedCategory = null"
            >
                View all products
            </button>
        </div>
    </div>

    <!-- Product Modal -->
    <ProductModal
        v-model:open="showModal"
        :product="selectedProduct"
        :whatsapp="whatsapp"
        :profile-id="profileId"
    />
</template>

<style scoped>
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
</style>
