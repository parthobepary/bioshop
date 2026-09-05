<script setup lang="ts">
import { computed } from 'vue'
import { MapPin, Globe, Mail, BadgeCheck, Facebook, Instagram, Youtube, Twitter, Music2 } from 'lucide-vue-next'
import { mediaUrl } from '@/lib/media'

interface SocialLinks {
    facebook?: string | null
    instagram?: string | null
    youtube?: string | null
    tiktok?: string | null
    twitter?: string | null
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
    social_links: SocialLinks | null
}

interface Props {
    profile: Profile
}

const props = defineProps<Props>()

const photoUrl = computed(() => {
    if (props.profile.photo) {
        return mediaUrl(props.profile.photo)
    }
    return null
})

const initials = computed(() => {
    return props.profile.name
        .split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase()
        .slice(0, 2)
})

const websiteDisplay = computed(() => {
    if (!props.profile.website) return null
    try {
        const url = new URL(props.profile.website)
        return url.hostname.replace('www.', '')
    } catch {
        return props.profile.website
    }
})

const socials = computed(() => {
    const s = props.profile.social_links
    if (!s) return []
    const map = [
        { key: 'facebook', url: s.facebook, icon: Facebook, color: 'text-[#1877F2]' },
        { key: 'instagram', url: s.instagram, icon: Instagram, color: 'text-[#E1306C]' },
        { key: 'youtube', url: s.youtube, icon: Youtube, color: 'text-[#FF0000]' },
        { key: 'tiktok', url: s.tiktok, icon: Music2, color: 'text-slate-900' },
        { key: 'twitter', url: s.twitter, icon: Twitter, color: 'text-[#1DA1F2]' },
    ]
    return map.filter((item) => item.url && item.url.trim() !== '')
})
</script>

<template>
    <div class="relative mb-6">
        <!-- Cover banner: one flat band in the shop's own colour -->
        <div class="theme-bg relative h-24 overflow-hidden rounded-2xl sm:h-28"></div>

        <!-- Profile card -->
        <div class="relative mx-1.5 -mt-12 rounded-2xl border border-line bg-white px-6 pb-5 pt-14 text-center">
            <!-- Avatar overlapping -->
            <div class="absolute -top-14 left-1/2 -translate-x-1/2">
                <div class="theme-ring rounded-full">
                    <div
                        v-if="photoUrl"
                        class="h-28 w-28 overflow-hidden rounded-full ring-4 ring-white"
                    >
                        <img :src="photoUrl" :alt="profile.name" class="h-full w-full object-cover" />
                    </div>
                    <div
                        v-else
                        class="theme-gradient flex h-28 w-28 items-center justify-center rounded-full text-2xl font-semibold text-white ring-4 ring-white"
                    >
                        {{ initials }}
                    </div>
                </div>
            </div>

            <!-- Name -->
            <div class="flex items-center justify-center gap-1.5">
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">
                    {{ profile.name }}
                </h1>
                <BadgeCheck class="theme-text h-5 w-5 flex-shrink-0" />
            </div>

            <!-- Username pill -->
            <div class="mt-2 flex justify-center">
                <span class="theme-bg-light theme-text inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold">
                    @{{ profile.username }}
                </span>
            </div>

            <!-- Bio -->
            <p
                v-if="profile.bio"
                class="mx-auto mt-3 max-w-md leading-relaxed text-slate-600"
            >
                {{ profile.bio }}
            </p>

            <!-- Meta chips -->
            <div
                v-if="profile.location || profile.website || profile.email"
                class="mt-4 flex flex-wrap items-center justify-center gap-2"
            >
                <span
                    v-if="profile.location"
                    class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-sm text-slate-600"
                >
                    <MapPin class="h-3.5 w-3.5" />
                    {{ profile.location }}
                </span>

                <a
                    v-if="profile.website"
                    :href="profile.website"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-sm text-slate-600 transition-colors hover:bg-slate-200"
                >
                    <Globe class="h-3.5 w-3.5" />
                    {{ websiteDisplay }}
                </a>

                <a
                    v-if="profile.email"
                    :href="`mailto:${profile.email}`"
                    class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-sm text-slate-600 transition-colors hover:bg-slate-200"
                >
                    <Mail class="h-3.5 w-3.5" />
                    {{ profile.email }}
                </a>
            </div>

            <!-- Social links -->
            <div v-if="socials.length" class="mt-4 flex flex-wrap items-center justify-center gap-2.5">
                <a
                    v-for="social in socials"
                    :key="social.key"
                    :href="social.url!"
                    target="_blank"
                    rel="noopener noreferrer"
                    :aria-label="social.key"
                    class="flex h-10 w-10 items-center justify-center rounded-full border border-line bg-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md"
                >
                    <component :is="social.icon" :class="['h-5 w-5', social.color]" />
                </a>
            </div>
        </div>
    </div>
</template>
