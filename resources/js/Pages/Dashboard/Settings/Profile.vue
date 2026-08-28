<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, useForm, router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card'
import AvatarUpload from '@/Components/common/AvatarUpload.vue'
import ColorPicker from '@/Components/common/ColorPicker.vue'
import { Save, Loader2, ExternalLink, Copy, Check } from 'lucide-vue-next'

interface Profile {
    id: number
    username: string
    name: string
    bio: string | null
    photo: string | null
    whatsapp: string
    theme_color: string
    social_links: Record<string, string> | null
    seo_title: string | null
    seo_description: string | null
}

interface Props {
    profile: Profile
}

const props = defineProps<Props>()

defineOptions({
    layout: DashboardLayout,
})

const form = useForm({
    username: props.profile.username,
    name: props.profile.name,
    bio: props.profile.bio || '',
    whatsapp: props.profile.whatsapp,
    theme_color: props.profile.theme_color,
    photo: null as File | null,
    social_links: props.profile.social_links || {
        facebook: '',
        instagram: '',
        youtube: '',
        tiktok: '',
        twitter: '',
    },
    seo_title: props.profile.seo_title || '',
    seo_description: props.profile.seo_description || '',
})

const copied = ref(false)
const siteOrigin = typeof window !== 'undefined' ? window.location.origin : ''
const shopUrl = computed(() => `${siteOrigin}/${props.profile.username}`)

const copyUrl = () => {
    navigator.clipboard.writeText(shopUrl.value)
    copied.value = true
    setTimeout(() => copied.value = false, 2000)
}

const handlePhotoChange = (file: File | null) => {
    form.photo = file
}

const handlePhotoRemove = () => {
    router.delete(route('shop.profile.delete-photo'), {
        preserveScroll: true,
    })
}

const submit = () => {
    form.post(route('shop.profile.update'), {
        preserveScroll: true,
        forceFormData: true,
    })
}
</script>

<template>
    <Head title="Profile Settings" />

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
                <p class="text-gray-500 mt-1">Manage your shop profile and appearance</p>
            </div>
            <a
                :href="`/${profile.username}`"
                target="_blank"
                class="flex items-center gap-2 px-4 py-2 text-primary-600 hover:bg-primary-50 rounded-xl font-medium transition-colors"
            >
                <ExternalLink class="w-4 h-4" />
                View Shop
            </a>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <!-- Shop URL Card -->
            <Card>
                <CardHeader>
                    <CardTitle>Shop URL</CardTitle>
                    <CardDescription>Your public shop link</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="flex items-center gap-3">
                        <div class="flex-1 flex items-center bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                            <span class="px-4 py-3 text-gray-500 bg-gray-100 border-r border-gray-200">
                                {{ siteOrigin }}/
                            </span>
                            <Input
                                v-model="form.username"
                                type="text"
                                class="border-0 rounded-none focus:ring-0"
                                placeholder="username"
                            />
                        </div>
                        <Button
                            type="button"
                            variant="outline"
                            @click="copyUrl"
                        >
                            <Check v-if="copied" class="w-4 h-4 mr-2 text-green-500" />
                            <Copy v-else class="w-4 h-4 mr-2" />
                            {{ copied ? 'Copied!' : 'Copy' }}
                        </Button>
                    </div>
                    <p v-if="form.errors.username" class="mt-2 text-sm text-red-600">
                        {{ form.errors.username }}
                    </p>
                </CardContent>
            </Card>

            <!-- Basic Info Card -->
            <Card>
                <CardHeader>
                    <CardTitle>Basic Information</CardTitle>
                    <CardDescription>Your shop's public profile</CardDescription>
                </CardHeader>
                <CardContent class="space-y-6">
                    <!-- Photo -->
                    <div class="flex justify-center">
                        <AvatarUpload
                            :model-value="form.photo"
                            :current-photo="profile.photo"
                            @update:model-value="handlePhotoChange"
                            @remove="handlePhotoRemove"
                        />
                    </div>

                    <!-- Name -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Shop Name <span class="text-red-500">*</span>
                        </label>
                        <Input
                            v-model="form.name"
                            type="text"
                            placeholder="My Awesome Shop"
                            maxlength="100"
                        />
                        <p v-if="form.errors.name" class="text-sm text-red-600">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Bio -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Bio
                        </label>
                        <textarea
                            v-model="form.bio"
                            rows="3"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all resize-none"
                            placeholder="Tell visitors about your shop..."
                            maxlength="500"
                        />
                        <div class="flex justify-between">
                            <p v-if="form.errors.bio" class="text-sm text-red-600">
                                {{ form.errors.bio }}
                            </p>
                            <p class="text-xs text-gray-400 ml-auto">
                                {{ form.bio.length }}/500
                            </p>
                        </div>
                    </div>

                    <!-- WhatsApp -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            WhatsApp Number <span class="text-red-500">*</span>
                        </label>
                        <Input
                            v-model="form.whatsapp"
                            type="tel"
                            placeholder="01712345678"
                            maxlength="11"
                        />
                        <p v-if="form.errors.whatsapp" class="text-sm text-red-600">
                            {{ form.errors.whatsapp }}
                        </p>
                        <p class="text-xs text-gray-400">
                            Bangladesh number format (e.g., 01712345678)
                        </p>
                    </div>
                </CardContent>
            </Card>

            <!-- Theme Color Card -->
            <Card>
                <CardHeader>
                    <CardTitle>Theme Color</CardTitle>
                    <CardDescription>Choose a color for your shop</CardDescription>
                </CardHeader>
                <CardContent>
                    <ColorPicker
                        v-model="form.theme_color"
                        label=""
                    />
                    <p v-if="form.errors.theme_color" class="mt-2 text-sm text-red-600">
                        {{ form.errors.theme_color }}
                    </p>
                </CardContent>
            </Card>

            <!-- Social Links Card -->
            <Card>
                <CardHeader>
                    <CardTitle>Social Links</CardTitle>
                    <CardDescription>Add your social media profiles</CardDescription>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Facebook</label>
                            <Input
                                v-model="form.social_links.facebook"
                                type="url"
                                placeholder="https://facebook.com/yourpage"
                            />
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Instagram</label>
                            <Input
                                v-model="form.social_links.instagram"
                                type="url"
                                placeholder="https://instagram.com/yourpage"
                            />
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">YouTube</label>
                            <Input
                                v-model="form.social_links.youtube"
                                type="url"
                                placeholder="https://youtube.com/@yourchannel"
                            />
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">TikTok</label>
                            <Input
                                v-model="form.social_links.tiktok"
                                type="url"
                                placeholder="https://tiktok.com/@yourprofile"
                            />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- SEO Card -->
            <Card>
                <CardHeader>
                    <CardTitle>SEO Settings</CardTitle>
                    <CardDescription>Optimize your shop for search engines</CardDescription>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">SEO Title</label>
                        <Input
                            v-model="form.seo_title"
                            type="text"
                            placeholder="My Shop - Best Products in Town"
                            maxlength="60"
                        />
                        <p class="text-xs text-gray-400">
                            {{ form.seo_title.length }}/60 characters
                        </p>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">SEO Description</label>
                        <textarea
                            v-model="form.seo_description"
                            rows="2"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all resize-none"
                            placeholder="Describe your shop for search engines..."
                            maxlength="160"
                        />
                        <p class="text-xs text-gray-400">
                            {{ form.seo_description.length }}/160 characters
                        </p>
                    </div>
                </CardContent>
            </Card>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <Button
                    type="submit"
                    :disabled="form.processing"
                    size="lg"
                >
                    <Loader2 v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                    <Save v-else class="w-4 h-4 mr-2" />
                    {{ form.processing ? 'Saving...' : 'Save Changes' }}
                </Button>
            </div>
        </form>
    </div>
</template>
