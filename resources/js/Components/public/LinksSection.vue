<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import {
    Link as LinkIcon,
    Facebook,
    Instagram,
    Youtube,
    Twitter,
    Linkedin,
    Github,
    Globe,
    Mail,
    Phone,
    MapPin,
    Music,
    Video,
    ShoppingBag,
    FileText,
    MessageCircle,
    ExternalLink,
} from 'lucide-vue-next'

interface Link {
    id: number
    title: string
    url: string
    icon: string | null
    is_active: boolean
}

interface Props {
    links: Link[]
}

defineProps<Props>()

const iconMap: Record<string, any> = {
    facebook: Facebook,
    instagram: Instagram,
    youtube: Youtube,
    twitter: Twitter,
    linkedin: Linkedin,
    github: Github,
    globe: Globe,
    mail: Mail,
    phone: Phone,
    location: MapPin,
    music: Music,
    video: Video,
    shop: ShoppingBag,
    document: FileText,
    message: MessageCircle,
    link: LinkIcon,
}

const getIcon = (icon: string | null) => {
    if (!icon) return LinkIcon
    return iconMap[icon] || LinkIcon
}

const trackClick = async (linkId: number) => {
    try {
        await axios.post(`/track/link/${linkId}`)
    } catch (error) {
        // Silently fail tracking
    }
}

const handleLinkClick = (link: Link) => {
    trackClick(link.id)
}
</script>

<template>
    <div v-if="links.length > 0" class="mb-8">
        <div class="space-y-3">
            <a
                v-for="link in links"
                :key="link.id"
                :href="link.url"
                target="_blank"
                rel="noopener noreferrer"
                class="group flex items-center gap-4 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-slate-200 transition-all duration-200"
                @click="handleLinkClick(link)"
            >
                <!-- Icon -->
                <div class="w-10 h-10 rounded-xl flex items-center justify-center theme-bg-light flex-shrink-0">
                    <component
                        :is="getIcon(link.icon)"
                        class="w-5 h-5 theme-text"
                    />
                </div>

                <!-- Title -->
                <span class="flex-1 font-medium text-slate-800 group-hover:text-slate-900">
                    {{ link.title }}
                </span>

                <!-- Arrow -->
                <ExternalLink class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-colors" />
            </a>
        </div>
    </div>
</template>
