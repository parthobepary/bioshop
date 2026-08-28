<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import { MessageCircle, X } from 'lucide-vue-next'

interface Props {
    whatsapp: string
    profileId: number
    shopName: string
}

const props = defineProps<Props>()

const showTooltip = ref(false)
const isAnimating = ref(true)

// Stop animation after 5 seconds
setTimeout(() => {
    isAnimating.value = false
}, 5000)

const trackWhatsappClick = async () => {
    try {
        await axios.post('/track/whatsapp', {
            profile_id: props.profileId,
            product_id: null,
        })
    } catch (error) {
        // Silently fail tracking
    }
}

const openWhatsApp = () => {
    trackWhatsappClick()

    const message = encodeURIComponent(
        `Hi! I'm visiting your shop "${props.shopName}" and would like to know more about your products.`
    )

    const phone = props.whatsapp.replace(/[^0-9]/g, '')
    const whatsappUrl = `https://wa.me/${phone}?text=${message}`

    window.open(whatsappUrl, '_blank')
    showTooltip.value = false
}
</script>

<template>
    <div class="fixed bottom-20 right-4 z-50">
        <!-- Tooltip -->
        <div
            v-if="showTooltip"
            class="absolute bottom-16 right-0 bg-slate-900 text-white px-4 py-2 rounded-xl shadow-lg whitespace-nowrap text-sm animate-fade-in"
        >
            <button
                class="absolute -top-2 -right-2 w-5 h-5 bg-slate-700 rounded-full flex items-center justify-center hover:bg-slate-600"
                @click.stop="showTooltip = false"
            >
                <X class="w-3 h-3" />
            </button>
            Chat with us on WhatsApp
            <div class="absolute bottom-0 right-6 transform translate-y-1/2 rotate-45 w-2 h-2 bg-slate-900"></div>
        </div>

        <!-- Button -->
        <button
            :class="[
                'w-14 h-14 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110',
                isAnimating ? 'animate-bounce' : ''
            ]"
            @click="openWhatsApp"
            @mouseenter="showTooltip = true"
            @mouseleave="showTooltip = false"
        >
            <MessageCircle class="w-7 h-7" />
        </button>

        <!-- Pulse Ring -->
        <div
            v-if="isAnimating"
            class="absolute inset-0 -z-10"
        >
            <div class="absolute inset-0 bg-green-400 rounded-full animate-ping opacity-30"></div>
        </div>
    </div>
</template>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.2s ease-out;
}
</style>
