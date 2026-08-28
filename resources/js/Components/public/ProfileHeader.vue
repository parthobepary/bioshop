<script setup lang="ts">
import { computed } from 'vue'
import { MapPin, Globe, Mail, Phone } from 'lucide-vue-next'

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

interface Props {
    profile: Profile
}

const props = defineProps<Props>()

const photoUrl = computed(() => {
    if (props.profile.photo) {
        return `/storage/${props.profile.photo}`
    }
    // Generate initials avatar
    const initials = props.profile.name
        .split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase()
        .slice(0, 2)
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
</script>

<template>
    <div class="text-center mb-8">
        <!-- Profile Photo -->
        <div class="relative inline-block mb-4">
            <div
                v-if="photoUrl"
                class="w-28 h-28 rounded-full overflow-hidden ring-4 ring-white shadow-xl mx-auto"
            >
                <img
                    :src="photoUrl"
                    :alt="profile.name"
                    class="w-full h-full object-cover"
                />
            </div>
            <div
                v-else
                class="w-28 h-28 rounded-full flex items-center justify-center text-3xl font-bold text-white ring-4 ring-white shadow-xl mx-auto theme-bg"
            >
                {{ initials }}
            </div>
        </div>

        <!-- Name -->
        <h1 class="text-2xl font-bold text-slate-900 mb-2">
            {{ profile.name }}
        </h1>

        <!-- Username -->
        <p class="text-sm text-slate-500 mb-3">
            @{{ profile.username }}
        </p>

        <!-- Bio -->
        <p
            v-if="profile.bio"
            class="text-slate-600 max-w-md mx-auto leading-relaxed mb-4"
        >
            {{ profile.bio }}
        </p>

        <!-- Meta Info -->
        <div class="flex flex-wrap items-center justify-center gap-4 text-sm text-slate-500">
            <a
                v-if="profile.location"
                href="#"
                class="flex items-center gap-1.5 hover:text-slate-700 transition-colors"
            >
                <MapPin class="w-4 h-4" />
                <span>{{ profile.location }}</span>
            </a>

            <a
                v-if="profile.website"
                :href="profile.website"
                target="_blank"
                rel="noopener noreferrer"
                class="flex items-center gap-1.5 hover:text-slate-700 transition-colors"
            >
                <Globe class="w-4 h-4" />
                <span>{{ websiteDisplay }}</span>
            </a>

            <a
                v-if="profile.email"
                :href="`mailto:${profile.email}`"
                class="flex items-center gap-1.5 hover:text-slate-700 transition-colors"
            >
                <Mail class="w-4 h-4" />
                <span>{{ profile.email }}</span>
            </a>
        </div>
    </div>
</template>
