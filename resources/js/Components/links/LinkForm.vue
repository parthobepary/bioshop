<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
    DialogFooter,
} from '@/Components/ui/dialog'
import {
    Loader2,
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
    open: boolean
    link?: Link | null
}

const props = defineProps<Props>()

const emit = defineEmits<{
    'update:open': [value: boolean]
}>()

const isEdit = computed(() => !!props.link)

const form = useForm({
    title: '',
    url: '',
    icon: '',
})

// Icon options
const iconOptions = [
    { value: 'link', label: 'Link', icon: LinkIcon },
    { value: 'facebook', label: 'Facebook', icon: Facebook },
    { value: 'instagram', label: 'Instagram', icon: Instagram },
    { value: 'twitter', label: 'Twitter/X', icon: Twitter },
    { value: 'youtube', label: 'YouTube', icon: Youtube },
    { value: 'linkedin', label: 'LinkedIn', icon: Linkedin },
    { value: 'github', label: 'GitHub', icon: Github },
    { value: 'whatsapp', label: 'WhatsApp', icon: MessageCircle },
    { value: 'telegram', label: 'Telegram', icon: Send },
    { value: 'spotify', label: 'Spotify', icon: Music },
    { value: 'globe', label: 'Website', icon: Globe },
]

// Watch for link changes (when editing)
watch(() => props.link, (newLink) => {
    if (newLink) {
        form.title = newLink.title
        form.url = newLink.url
        form.icon = newLink.icon
    } else {
        form.reset()
    }
}, { immediate: true })

// Watch for dialog open state
watch(() => props.open, (isOpen) => {
    if (!isOpen) {
        form.reset()
        form.clearErrors()
    }
})

const closeDialog = () => {
    emit('update:open', false)
}

const submit = () => {
    if (isEdit.value && props.link) {
        form.put(route('links.update', props.link.id), {
            preserveScroll: true,
            onSuccess: () => closeDialog(),
        })
    } else {
        form.post(route('links.store'), {
            preserveScroll: true,
            onSuccess: () => closeDialog(),
        })
    }
}
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="rounded-2xl bg-white p-6 shadow-xl">
            <DialogHeader>
                <DialogTitle class="text-lg font-bold tracking-tight text-slate-900">{{ isEdit ? 'Edit Link' : 'Add New Link' }}</DialogTitle>
                <DialogDescription class="text-sm text-slate-500">
                    {{ isEdit ? 'Update your link details below.' : 'Add a new link to your profile.' }}
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Title -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700">
                        Title <span class="text-rose-500">*</span>
                    </label>
                    <input
                        v-model="form.title"
                        type="text"
                        placeholder="My Facebook Page"
                        maxlength="100"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder:text-slate-400 transition focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10"
                    />
                    <p v-if="form.errors.title" class="mt-1 text-sm text-rose-600">
                        {{ form.errors.title }}
                    </p>
                </div>

                <!-- URL -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700">
                        URL <span class="text-rose-500">*</span>
                    </label>
                    <input
                        v-model="form.url"
                        type="url"
                        placeholder="https://facebook.com/mypage"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder:text-slate-400 transition focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10"
                    />
                    <p v-if="form.errors.url" class="mt-1 text-sm text-rose-600">
                        {{ form.errors.url }}
                    </p>
                    <p class="text-xs text-slate-400">
                        Icon will be auto-detected from URL
                    </p>
                </div>

                <!-- Icon Selection -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700">
                        Icon (Optional)
                    </label>
                    <div class="grid grid-cols-6 gap-2">
                        <button
                            v-for="iconOption in iconOptions"
                            :key="iconOption.value"
                            type="button"
                            :class="[
                                'flex items-center justify-center rounded-xl p-3 transition-all',
                                form.icon === iconOption.value
                                    ? 'bg-indigo-100 text-indigo-600 ring-2 ring-indigo-500'
                                    : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                            ]"
                            :title="iconOption.label"
                            @click="form.icon = iconOption.value"
                        >
                            <component :is="iconOption.icon" class="h-5 w-5" />
                        </button>
                    </div>
                    <p class="text-xs text-slate-400">
                        Leave empty to auto-detect from URL
                    </p>
                </div>

                <DialogFooter class="gap-2 pt-4">
                    <button
                        type="button"
                        @click="closeDialog"
                        class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-500/30 disabled:pointer-events-none disabled:opacity-60"
                    >
                        <Loader2 v-if="form.processing" class="h-4 w-4 animate-spin" />
                        {{ isEdit ? 'Update Link' : 'Add Link' }}
                    </button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
