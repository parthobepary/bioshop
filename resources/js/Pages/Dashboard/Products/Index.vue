<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import ProductCard from '@/Components/products/ProductCard.vue'
import draggable from 'vuedraggable'
import { Plus, Package, Filter, X, ArrowLeft } from 'lucide-vue-next'

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
    is_active: boolean
    sort_order: number
    category: Category | null
    images: ProductImage[]
}

interface Props {
    products: Product[]
    categories: Category[]
    filters: {
        category: string | null
        status: string | null
    }
}

const props = defineProps<Props>()

defineOptions({
    layout: DashboardLayout,
})

const page = usePage()
const flash = computed(() => page.props.flash as { success?: string; error?: string })

// Local products for dragging
const localProducts = ref<Product[]>([...props.products])

// Filter state
const selectedCategory = ref(props.filters.category || '')
const selectedStatus = ref(props.filters.status || '')

// Sync with props
watch(() => props.products, (newProducts) => {
    localProducts.value = [...newProducts]
}, { deep: true })

const hasFilters = computed(() => {
    return selectedCategory.value || selectedStatus.value
})

const applyFilters = () => {
    router.get(route('products.index'), {
        category: selectedCategory.value || undefined,
        status: selectedStatus.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
    })
}

const clearFilters = () => {
    selectedCategory.value = ''
    selectedStatus.value = ''
    router.get(route('products.index'), {}, {
        preserveState: true,
        preserveScroll: true,
    })
}

const onDragEnd = () => {
    const updatedProducts = localProducts.value.map((product, index) => ({
        id: product.id,
        sort_order: index + 1,
    }))

    router.post(route('products.reorder'), {
        products: updatedProducts,
    }, {
        preserveScroll: true,
    })
}

const statusOptions = [
    { value: '', label: 'All Statuses' },
    { value: 'available', label: 'In Stock' },
    { value: 'stock_out', label: 'Out of Stock' },
    { value: 'pre_order', label: 'Pre-order' },
]
</script>

<template>
    <Head title="Products" />

    <div class="mx-auto max-w-7xl space-y-8">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-3">
                <Link
                    href="/dashboard"
                    class="mt-0.5 flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition-colors hover:bg-slate-50 hover:text-slate-700"
                >
                    <ArrowLeft class="h-5 w-5" />
                </Link>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Products</h1>
                    <p class="mt-0.5 text-sm text-slate-500">Manage your product catalog</p>
                </div>
            </div>
            <Link
                :href="route('products.create')"
                class="inline-flex items-center gap-2 self-start rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-500/30 sm:self-auto"
            >
                <Plus class="h-4 w-4" />
                Add Product
            </Link>
        </div>

        <!-- Flash Messages -->
        <div
            v-if="flash?.success"
            class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700"
        >
            {{ flash.success }}
        </div>
        <div
            v-if="flash?.error"
            class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700"
        >
            {{ flash.error }}
        </div>

        <!-- Filters -->
        <div class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2 text-sm font-medium text-slate-500">
                    <Filter class="h-4 w-4" />
                    <span>Filter</span>
                </div>

                <select
                    v-model="selectedCategory"
                    class="cursor-pointer rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-4 pr-10 text-sm text-slate-900 transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10"
                    @change="applyFilters"
                >
                    <option value="">All Categories</option>
                    <option
                        v-for="category in categories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>

                <select
                    v-model="selectedStatus"
                    class="cursor-pointer rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-4 pr-10 text-sm text-slate-900 transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10"
                    @change="applyFilters"
                >
                    <option
                        v-for="option in statusOptions"
                        :key="option.value"
                        :value="option.value"
                    >
                        {{ option.label }}
                    </option>
                </select>

                <button
                    v-if="hasFilters"
                    type="button"
                    class="inline-flex items-center gap-1 rounded-xl px-3 py-2 text-sm font-semibold text-rose-600 transition-colors hover:bg-rose-50"
                    @click="clearFilters"
                >
                    <X class="h-4 w-4" />
                    Clear
                </button>

                <div class="ml-auto text-sm text-slate-500">
                    {{ products.length }} {{ products.length === 1 ? 'product' : 'products' }}
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div
            v-if="products.length === 0"
            class="flex flex-col items-center justify-center rounded-2xl border border-slate-200/70 bg-white px-6 py-16 text-center shadow-sm"
        >
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50">
                <Package class="h-6 w-6 text-slate-300" />
            </div>
            <h3 class="mt-4 text-base font-semibold text-slate-900">
                {{ hasFilters ? 'No products found' : 'No products yet' }}
            </h3>
            <p class="mx-auto mt-1 max-w-sm text-sm text-slate-400">
                {{ hasFilters
                    ? 'Try changing your filters to see more products.'
                    : 'Add products to showcase them on your shop page.'
                }}
            </p>
            <div class="mt-6">
                <Link
                    v-if="!hasFilters"
                    :href="route('products.create')"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-500/30"
                >
                    <Plus class="h-4 w-4" />
                    Add Your First Product
                </Link>
                <button
                    v-else
                    type="button"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50"
                    @click="clearFilters"
                >
                    Clear Filters
                </button>
            </div>
        </div>

        <draggable
            v-else
            v-model="localProducts"
            item-key="id"
            handle=".cursor-grab"
            ghost-class="opacity-50"
            animation="200"
            class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
            @end="onDragEnd"
        >
            <template #item="{ element }">
                <ProductCard :product="element" />
            </template>
        </draggable>
    </div>
</template>
