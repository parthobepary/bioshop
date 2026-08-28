<script setup lang="ts">
import { ref, computed } from 'vue'
import ProductModal from './ProductModal.vue'
import { ShoppingBag, Tag } from 'lucide-vue-next'

interface ProductImage {
    id: number
    path: string
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
    return `/storage/${sortedImages[0].path}`
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
    <div v-if="products.length > 0" class="mb-8">
        <!-- Section Header -->
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center theme-bg-light">
                <ShoppingBag class="w-4 h-4 theme-text" />
            </div>
            <h2 class="text-lg font-bold text-slate-800">Products</h2>
        </div>

        <!-- Category Filter -->
        <div
            v-if="categories.length > 0"
            class="flex gap-2 overflow-x-auto pb-3 mb-4 -mx-4 px-4 scrollbar-hide"
        >
            <button
                :class="[
                    'px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all',
                    !selectedCategory
                        ? 'theme-bg text-white'
                        : 'bg-white border border-slate-200 text-slate-600 hover:border-slate-300'
                ]"
                @click="selectedCategory = null"
            >
                All
            </button>
            <button
                v-for="category in categories"
                :key="category.id"
                :class="[
                    'px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all',
                    selectedCategory === category.id
                        ? 'theme-bg text-white'
                        : 'bg-white border border-slate-200 text-slate-600 hover:border-slate-300'
                ]"
                @click="selectedCategory = category.id"
            >
                {{ category.name }}
            </button>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-2 gap-3">
            <button
                v-for="product in filteredProducts"
                :key="product.id"
                class="group bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-md hover:border-slate-200 transition-all text-left"
                @click="openProduct(product)"
            >
                <!-- Image -->
                <div class="relative aspect-square bg-slate-100">
                    <img
                        v-if="getProductImage(product)"
                        :src="getProductImage(product)!"
                        :alt="product.name"
                        class="w-full h-full object-cover"
                    />
                    <div
                        v-else
                        class="w-full h-full flex items-center justify-center"
                    >
                        <ShoppingBag class="w-10 h-10 text-slate-300" />
                    </div>

                    <!-- Discount Badge -->
                    <div
                        v-if="getDiscount(product)"
                        class="absolute top-2 left-2 px-2 py-1 bg-red-500 text-white text-xs font-bold rounded-full flex items-center gap-0.5"
                    >
                        <Tag class="w-3 h-3" />
                        {{ getDiscount(product) }}%
                    </div>

                    <!-- Stock Out Overlay -->
                    <div
                        v-if="product.status === 'stock_out'"
                        class="absolute inset-0 bg-slate-900/60 flex items-center justify-center"
                    >
                        <span class="px-3 py-1.5 bg-white rounded-full text-xs font-semibold text-slate-700">
                            Out of Stock
                        </span>
                    </div>

                    <!-- Pre-order Badge -->
                    <div
                        v-else-if="product.status === 'pre_order'"
                        class="absolute top-2 right-2 px-2 py-1 bg-amber-500 text-white text-xs font-bold rounded-full"
                    >
                        Pre-order
                    </div>
                </div>

                <!-- Info -->
                <div class="p-3">
                    <h3 class="font-medium text-slate-800 text-sm line-clamp-2 mb-1.5 group-hover:text-slate-900">
                        {{ product.name }}
                    </h3>
                    <div class="flex items-baseline gap-1.5">
                        <span class="font-bold theme-text">
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
            class="text-center py-12"
        >
            <p class="text-slate-500">No products in this category</p>
            <button
                class="mt-2 text-sm font-medium theme-text hover:underline"
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
