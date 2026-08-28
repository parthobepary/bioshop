<script setup lang="ts">
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
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
    if (confirm('Are you sure you want to delete this link?')) {
        router.delete(route('links.destroy', props.link.id), {
            preserveScroll: true,
        })
    }
}
</script>

<template>
    <div
        :class="[
            'group flex items-center gap-4 p-4 bg-white rounded-xl border transition-all duration-200',
            link.is_active
                ? 'border-gray-200 hover:border-primary-300 hover:shadow-md'
                : 'border-gray-200 bg-gray-50 opacity-60'
        ]"
    >
        <!-- Drag Handle -->
        <div class="cursor-grab active:cursor-grabbing text-gray-400 hover:text-gray-600">
            <GripVertical class="w-5 h-5" />
        </div>

        <!-- Icon -->
        <div
            :class="[
                'w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0',
                link.is_active ? 'bg-primary-100 text-primary-600' : 'bg-gray-200 text-gray-500'
            ]"
        >
            <component :is="iconComponent" class="w-5 h-5" />
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
            <h3 class="font-medium text-gray-900 truncate">
                {{ link.title }}
            </h3>
            <a
                :href="link.url"
                target="_blank"
                class="text-sm text-gray-500 hover:text-primary-600 truncate block"
            >
                {{ link.url }}
            </a>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
            <!-- Toggle Active -->
            <Button
                variant="ghost"
                size="sm"
                @click="toggleActive"
                :title="link.is_active ? 'Disable link' : 'Enable link'"
            >
                <ToggleRight v-if="link.is_active" class="w-5 h-5 text-green-500" />
                <ToggleLeft v-else class="w-5 h-5 text-gray-400" />
            </Button>

            <!-- Open Link -->
            <a
                :href="link.url"
                target="_blank"
                class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors"
                title="Open link"
            >
                <ExternalLink class="w-4 h-4" />
            </a>

            <!-- Edit -->
            <Button
                variant="ghost"
                size="sm"
                @click="emit('edit', link)"
                title="Edit link"
            >
                <Pencil class="w-4 h-4 text-gray-400" />
            </Button>

            <!-- Delete -->
            <Button
                variant="ghost"
                size="sm"
                @click="deleteLink"
                title="Delete link"
            >
                <Trash2 class="w-4 h-4 text-red-400" />
            </Button>
        </div>
    </div>
</template>
