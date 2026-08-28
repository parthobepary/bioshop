<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import ProductCard from '@/Components/products/ProductCard.vue'
import draggable from 'vuedraggable'
import { Plus, Package, Filter, X } from 'lucide-vue-next'

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

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Products</h1>
                <p class="text-gray-500 mt-1">Manage your product catalog</p>
            </div>
            <Link :href="route('products.create')">
                <Button>
                    <Plus class="w-4 h-4 mr-2" />
                    Add Product
                </Button>
            </Link>
        </div>

        <!-- Flash Messages -->
        <div
            v-if="flash?.success"
            class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-700"
        >
            {{ flash.success }}
        </div>
        <div
            v-if="flash?.error"
            class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700"
        >
            {{ flash.error }}
        </div>

        <!-- Filters -->
        <Card>
            <CardContent class="p-4">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <Filter class="w-4 h-4" />
                        <span>Filter:</span>
                    </div>

                    <select
                        v-model="selectedCategory"
                        class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
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
                        class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
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

                    <Button
                        v-if="hasFilters"
                        variant="ghost"
                        size="sm"
                        @click="clearFilters"
                    >
                        <X class="w-4 h-4 mr-1" />
                        Clear
                    </Button>

                    <div class="ml-auto text-sm text-gray-500">
                        {{ products.length }} {{ products.length === 1 ? 'product' : 'products' }}
                    </div>
                </div>
            </CardContent>
        </Card>

        <!-- Products Grid -->
        <div v-if="products.length === 0" class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <Package class="w-8 h-8 text-gray-400" />
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                {{ hasFilters ? 'No products found' : 'No products yet' }}
            </h3>
            <p class="text-gray-500 mb-6 max-w-sm mx-auto">
                {{ hasFilters
                    ? 'Try changing your filters to see more products.'
                    : 'Add products to showcase them on your shop page.'
                }}
            </p>
            <Link v-if="!hasFilters" :href="route('products.create')">
                <Button>
                    <Plus class="w-4 h-4 mr-2" />
                    Add Your First Product
                </Button>
            </Link>
            <Button v-else variant="outline" @click="clearFilters">
                Clear Filters
            </Button>
        </div>

        <draggable
            v-else
            v-model="localProducts"
            item-key="id"
            handle=".cursor-grab"
            ghost-class="opacity-50"
            animation="200"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
            @end="onDragEnd"
        >
            <template #item="{ element }">
                <ProductCard :product="element" />
            </template>
        </draggable>
    </div>
</template>
