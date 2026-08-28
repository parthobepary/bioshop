<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import axios from 'axios'
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/Components/ui/dialog'
import { Button } from '@/Components/ui/button'
import { X, ChevronLeft, ChevronRight, ShoppingBag, MessageCircle, Tag } from 'lucide-vue-next'

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
    open: boolean
    product: Product | null
    whatsapp: string | null
    profileId: number
}

const props = defineProps<Props>()

const emit = defineEmits<{
    'update:open': [value: boolean]
}>()

const currentImageIndex = ref(0)

// Reset image index when modal opens
watch(() => props.open, (isOpen) => {
    if (isOpen) {
        currentImageIndex.value = 0
        // Track product view
        if (props.product) {
            trackProductView(props.product.id)
        }
    }
})

const images = computed(() => {
    if (!props.product?.images?.length) return []
    return props.product.images.sort((a, b) => a.sort_order - b.sort_order)
})

const currentImage = computed(() => {
    if (!images.value.length) return null
    return `/storage/${images.value[currentImageIndex.value].path}`
})

const discount = computed(() => {
    if (!props.product?.compare_price || !props.product?.price) return null
    if (props.product.compare_price <= props.product.price) return null
    return Math.round(((props.product.compare_price - props.product.price) / props.product.compare_price) * 100)
})

const statusText = computed(() => {
    if (!props.product) return ''
    const statuses: Record<string, string> = {
        available: 'In Stock',
        stock_out: 'Out of Stock',
        pre_order: 'Pre-Order',
    }
    return statuses[props.product.status] || props.product.status
})

const statusClass = computed(() => {
    if (!props.product) return ''
    const classes: Record<string, string> = {
        available: 'bg-green-100 text-green-700',
        stock_out: 'bg-red-100 text-red-700',
        pre_order: 'bg-amber-100 text-amber-700',
    }
    return classes[props.product.status] || ''
})

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-BD', {
        style: 'currency',
        currency: 'BDT',
        minimumFractionDigits: 0,
    }).format(price).replace('BDT', '৳')
}

const prevImage = () => {
    if (currentImageIndex.value > 0) {
        currentImageIndex.value--
    }
}

const nextImage = () => {
    if (currentImageIndex.value < images.value.length - 1) {
        currentImageIndex.value++
    }
}

const trackProductView = async (productId: number) => {
    try {
        await axios.post(`/track/product/${productId}`)
    } catch (error) {
        // Silently fail tracking
    }
}

const trackWhatsappClick = async () => {
    try {
        await axios.post('/track/whatsapp', {
            profile_id: props.profileId,
            product_id: props.product?.id,
        })
    } catch (error) {
        // Silently fail tracking
    }
}

const orderOnWhatsApp = () => {
    if (!props.whatsapp || !props.product) return

    trackWhatsappClick()

    const message = encodeURIComponent(
        `Hi! I'm interested in ordering:\n\n` +
        `*${props.product.name}*\n` +
        `Price: ${formatPrice(props.product.price)}\n\n` +
        `Please let me know more details.`
    )

    const phone = props.whatsapp.replace(/[^0-9]/g, '')
    const whatsappUrl = `https://wa.me/${phone}?text=${message}`

    window.open(whatsappUrl, '_blank')
}

const closeModal = () => {
    emit('update:open', false)
}
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="max-w-lg p-0 overflow-hidden">
            <div v-if="product">
                <!-- Image Gallery -->
                <div class="relative bg-slate-100">
                    <div class="aspect-square">
                        <img
                            v-if="currentImage"
                            :src="currentImage"
                            :alt="product.name"
                            class="w-full h-full object-contain"
                        />
                        <div
                            v-else
                            class="w-full h-full flex items-center justify-center"
                        >
                            <ShoppingBag class="w-16 h-16 text-slate-300" />
                        </div>
                    </div>

                    <!-- Navigation Arrows -->
                    <template v-if="images.length > 1">
                        <button
                            v-if="currentImageIndex > 0"
                            class="absolute left-2 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/90 rounded-full flex items-center justify-center shadow-lg hover:bg-white transition-colors"
                            @click="prevImage"
                        >
                            <ChevronLeft class="w-5 h-5 text-slate-600" />
                        </button>
                        <button
                            v-if="currentImageIndex < images.length - 1"
                            class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/90 rounded-full flex items-center justify-center shadow-lg hover:bg-white transition-colors"
                            @click="nextImage"
                        >
                            <ChevronRight class="w-5 h-5 text-slate-600" />
                        </button>

                        <!-- Dots -->
                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-1.5">
                            <button
                                v-for="(_, index) in images"
                                :key="index"
                                :class="[
                                    'w-2 h-2 rounded-full transition-all',
                                    index === currentImageIndex
                                        ? 'bg-white w-6'
                                        : 'bg-white/50 hover:bg-white/75'
                                ]"
                                @click="currentImageIndex = index"
                            />
                        </div>
                    </template>

                    <!-- Discount Badge -->
                    <div
                        v-if="discount"
                        class="absolute top-4 left-4 px-3 py-1.5 bg-red-500 text-white text-sm font-bold rounded-full flex items-center gap-1"
                    >
                        <Tag class="w-3.5 h-3.5" />
                        {{ discount }}% OFF
                    </div>

                    <!-- Close Button -->
                    <button
                        class="absolute top-4 right-4 w-10 h-10 bg-white/90 rounded-full flex items-center justify-center shadow-lg hover:bg-white transition-colors"
                        @click="closeModal"
                    >
                        <X class="w-5 h-5 text-slate-600" />
                    </button>
                </div>

                <!-- Product Info -->
                <div class="p-6">
                    <!-- Category & Status -->
                    <div class="flex items-center gap-2 mb-3">
                        <span
                            v-if="product.category"
                            class="text-xs font-medium px-2.5 py-1 rounded-full bg-slate-100 text-slate-600"
                        >
                            {{ product.category.name }}
                        </span>
                        <span
                            :class="[
                                'text-xs font-medium px-2.5 py-1 rounded-full',
                                statusClass
                            ]"
                        >
                            {{ statusText }}
                        </span>
                    </div>

                    <!-- Name -->
                    <h2 class="text-xl font-bold text-slate-900 mb-2">
                        {{ product.name }}
                    </h2>

                    <!-- Price -->
                    <div class="flex items-baseline gap-2 mb-4">
                        <span class="text-2xl font-bold theme-text">
                            {{ formatPrice(product.price) }}
                        </span>
                        <span
                            v-if="product.compare_price && product.compare_price > product.price"
                            class="text-lg text-slate-400 line-through"
                        >
                            {{ formatPrice(product.compare_price) }}
                        </span>
                    </div>

                    <!-- Description -->
                    <p
                        v-if="product.description"
                        class="text-slate-600 leading-relaxed mb-6 whitespace-pre-line"
                    >
                        {{ product.description }}
                    </p>

                    <!-- Order Button -->
                    <Button
                        v-if="whatsapp && product.status !== 'stock_out'"
                        class="w-full h-12 text-base font-semibold theme-bg hover:opacity-90"
                        @click="orderOnWhatsApp"
                    >
                        <MessageCircle class="w-5 h-5 mr-2" />
                        Order on WhatsApp
                    </Button>

                    <p
                        v-else-if="product.status === 'stock_out'"
                        class="text-center text-slate-500 py-3"
                    >
                        This product is currently out of stock
                    </p>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
