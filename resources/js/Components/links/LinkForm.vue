<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
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
        <DialogContent>
            <DialogHeader>
                <DialogTitle>{{ isEdit ? 'Edit Link' : 'Add New Link' }}</DialogTitle>
                <DialogDescription>
                    {{ isEdit ? 'Update your link details below.' : 'Add a new link to your profile.' }}
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- Title -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <Input
                        v-model="form.title"
                        type="text"
                        placeholder="My Facebook Page"
                        maxlength="100"
                    />
                    <p v-if="form.errors.title" class="text-sm text-red-600">
                        {{ form.errors.title }}
                    </p>
                </div>

                <!-- URL -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        URL <span class="text-red-500">*</span>
                    </label>
                    <Input
                        v-model="form.url"
                        type="url"
                        placeholder="https://facebook.com/mypage"
                    />
                    <p v-if="form.errors.url" class="text-sm text-red-600">
                        {{ form.errors.url }}
                    </p>
                    <p class="text-xs text-gray-400">
                        Icon will be auto-detected from URL
                    </p>
                </div>

                <!-- Icon Selection -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Icon (Optional)
                    </label>
                    <div class="grid grid-cols-6 gap-2">
                        <button
                            v-for="iconOption in iconOptions"
                            :key="iconOption.value"
                            type="button"
                            :class="[
                                'p-3 rounded-xl flex items-center justify-center transition-all',
                                form.icon === iconOption.value
                                    ? 'bg-primary-100 text-primary-600 ring-2 ring-primary-500'
                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                            ]"
                            :title="iconOption.label"
                            @click="form.icon = iconOption.value"
                        >
                            <component :is="iconOption.icon" class="w-5 h-5" />
                        </button>
                    </div>
                    <p class="text-xs text-gray-400">
                        Leave empty to auto-detect from URL
                    </p>
                </div>

                <DialogFooter class="pt-4">
                    <Button
                        type="button"
                        variant="outline"
                        @click="closeDialog"
                    >
                        Cancel
                    </Button>
                    <Button
                        type="submit"
                        :disabled="form.processing"
                    >
                        <Loader2 v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                        {{ isEdit ? 'Update Link' : 'Add Link' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
