<script setup lang="ts">
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import {
    GripVertical,
    Pencil,
    Trash2,
    ExternalLink,
    ToggleLeft,
    ToggleRight,
    Facebook,
    Instagram,
    Twitter,
    Youtube,
    Linkedin,
    Github,
    Link as LinkIcon,
    MessageCircle,
    Send,
    Music,
    Disc,
    Globe,
} from 'lucide-vue-next'

interface Link {
    id: number
    title: string
    url: string
    icon: string
    is_active: boolean
    sort_order: number
}

interface Props {
    link: Link
}

const props = defineProps<Props>()

const emit = defineEmits<{
    edit: [link: Link]
    delete: [link: { id: number; title: string }]
}>()

const iconComponent = computed(() => {
    const iconMap: Record<string, any> = {
        facebook: Facebook,
        instagram: Instagram,
        twitter: Twitter,
        youtube: Youtube,
        linkedin: Linkedin,
        github: Github,
        whatsapp: MessageCircle,
        telegram: Send,
        spotify: Music,
        soundcloud: Disc,
        link: LinkIcon,
    }
    return iconMap[props.link.icon] || Globe
})

const toggleActive = () => {
    router.post(route('links.toggle', props.link.id), {}, {
        preserveScroll: true,
    })
}

const deleteLink = () => {
    emit('delete', { id: props.link.id, title: props.link.title })
}
</script>

<template>
    <div
        :class="[
            'group flex items-center gap-4 rounded-2xl border border-line bg-white p-4 shadow-sm transition-all hover:shadow-md',
            !link.is_active && 'bg-slate-50 opacity-70'
        ]"
    >
        <!-- Drag Handle -->
        <button
            type="button"
            class="flex h-9 w-9 flex-shrink-0 cursor-grab items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100 active:cursor-grabbing"
        >
            <GripVertical class="h-5 w-5" />
        </button>

        <!-- Icon -->
        <div
            :class="[
                'flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl',
                link.is_active
                    ? 'accent-bg'
                    : 'bg-slate-200 text-slate-500'
            ]"
        >
            <component :is="iconComponent" class="h-5 w-5" />
        </div>

        <!-- Content -->
        <div class="min-w-0 flex-1">
            <div class="flex items-center gap-2">
                <h3 class="truncate font-semibold text-slate-900">
                    {{ link.title }}
                </h3>
                <span
                    :class="[
                        'flex-shrink-0 rounded-full px-2.5 py-0.5 text-xs font-medium',
                        link.is_active
                            ? 'bg-emerald-50 text-emerald-700'
                            : 'bg-slate-100 text-slate-500'
                    ]"
                >
                    {{ link.is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <a
                :href="link.url"
                target="_blank"
                class="mt-0.5 block truncate text-sm text-slate-500 transition-colors hover-accent-text"
            >
                {{ link.url }}
            </a>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-1 opacity-0 transition-opacity group-hover:opacity-100">
            <!-- Toggle Active -->
            <button
                type="button"
                @click="toggleActive"
                :title="link.is_active ? 'Disable link' : 'Enable link'"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100"
            >
                <ToggleRight v-if="link.is_active" class="h-5 w-5 text-emerald-500" />
                <ToggleLeft v-else class="h-5 w-5 text-slate-400" />
            </button>

            <!-- Open Link -->
            <a
                :href="link.url"
                target="_blank"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100"
                title="Open link"
            >
                <ExternalLink class="h-4 w-4" />
            </a>

            <!-- Edit -->
            <button
                type="button"
                @click="emit('edit', link)"
                title="Edit link"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100"
            >
                <Pencil class="h-4 w-4" />
            </button>

            <!-- Delete -->
            <button
                type="button"
                @click="deleteLink"
                title="Delete link"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-rose-50 hover:text-rose-600"
            >
                <Trash2 class="h-4 w-4" />
            </button>
        </div>
    </div>
</template>
