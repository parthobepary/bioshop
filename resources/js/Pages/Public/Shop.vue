<script setup lang="ts">
import { h, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import ProfileHeader from '@/Components/public/ProfileHeader.vue'
import LinksSection from '@/Components/public/LinksSection.vue'
import ProductsSection from '@/Components/public/ProductsSection.vue'
import PaymentSection from '@/Components/public/PaymentSection.vue'
import WhatsAppButton from '@/Components/public/WhatsAppButton.vue'

interface ProductImage {
    id: number
    path: string
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
    images: ProductImage[]
    category: Category | null
}

interface Link {
    id: number
    title: string
    url: string
    icon: string | null
    is_active: boolean
}

interface PaymentMethod {
    id: number
    type: 'bkash' | 'nagad' | 'rocket' | 'bank' | 'other'
    account_name: string
    account_number: string
    qr_code: string | null
    instructions: string | null
    is_active: boolean
}

interface Profile {
    id: number
    username: string
    name: string
    bio: string | null
    photo: string | null
    theme_color: string
    whatsapp: string | null
    location: string | null
    website: string | null
    email: string | null
}

interface SEO {
    title: string
    description: string | null
    image: string | null
    url: string
}

interface Props {
    profile: Profile
    links: Link[]
    categories: Category[]
    products: Product[]
    paymentMethods: PaymentMethod[]
    seo: SEO
}

const props = defineProps<Props>()

// Apply theme color on mount
onMounted(() => {
    const themeColor = props.profile.theme_color || '#6366f1'
    document.documentElement.style.setProperty('--theme-color', themeColor)

    // Calculate lighter and darker variants
    const hex = themeColor.replace('#', '')
    const r = parseInt(hex.substring(0, 2), 16)
    const g = parseInt(hex.substring(2, 4), 16)
    const b = parseInt(hex.substring(4, 6), 16)

    // Lighter variant (for backgrounds)
    document.documentElement.style.setProperty(
        '--theme-color-light',
        `rgba(${r}, ${g}, ${b}, 0.1)`
    )

    // Darker variant
    const darken = (value: number) => Math.max(0, Math.floor(value * 0.8))
    document.documentElement.style.setProperty(
        '--theme-color-dark',
        `rgb(${darken(r)}, ${darken(g)}, ${darken(b)})`
    )
})

defineOptions({
    layout: PublicLayout,
})
</script>

<template>
    <Head>
        <title>{{ seo.title }}</title>
        <meta name="description" :content="seo.description || ''" />

        <!-- Open Graph -->
        <meta property="og:type" content="website" />
        <meta property="og:url" :content="seo.url" />
        <meta property="og:title" :content="seo.title" />
        <meta property="og:description" :content="seo.description || ''" />
        <meta v-if="seo.image" property="og:image" :content="seo.image" />

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:url" :content="seo.url" />
        <meta name="twitter:title" :content="seo.title" />
        <meta name="twitter:description" :content="seo.description || ''" />
        <meta v-if="seo.image" name="twitter:image" :content="seo.image" />
    </Head>

    <!-- Profile Header -->
    <ProfileHeader :profile="profile" />

    <!-- Links Section -->
    <LinksSection :links="links" />

    <!-- Products Section -->
    <ProductsSection
        :products="products"
        :categories="categories"
        :whatsapp="profile.whatsapp"
        :profile-id="profile.id"
    />

    <!-- Payment Methods Section -->
    <PaymentSection :payment-methods="paymentMethods" />

    <!-- WhatsApp Floating Button -->
    <WhatsAppButton
        v-if="profile.whatsapp"
        :whatsapp="profile.whatsapp"
        :profile-id="profile.id"
        :shop-name="profile.name"
    />
</template>
