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
    <div v-if="links.length > 0" class="mb-6">
        <div class="space-y-3">
            <a
                v-for="link in links"
                :key="link.id"
                :href="link.url"
                target="_blank"
                rel="noopener noreferrer"
                class="group flex items-center gap-4 rounded-2xl border border-white/60 bg-white/80 p-3.5 shadow-sm shadow-slate-900/5 backdrop-blur-md transition-all duration-200 hover:-translate-y-0.5 hover:border-white hover:shadow-lg hover:shadow-slate-900/10"
                @click="handleLinkClick(link)"
            >
                <!-- Icon -->
                <div class="theme-gradient flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl text-white shadow-sm">
                    <component :is="getIcon(link.icon)" class="h-5 w-5" />
                </div>

                <!-- Title -->
                <span class="flex-1 font-semibold text-slate-800 group-hover:text-slate-900">
                    {{ link.title }}
                </span>

                <!-- Arrow -->
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-400 transition-all group-hover:translate-x-0.5 group-hover:bg-slate-200 group-hover:text-slate-600">
                    <ExternalLink class="h-4 w-4" />
                </span>
            </a>
        </div>
    </div>
</template>
