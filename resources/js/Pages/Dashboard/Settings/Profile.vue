<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, useForm, router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
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

    <div class="mx-auto max-w-3xl space-y-8">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Profile Settings</h1>
                <p class="mt-0.5 text-sm text-slate-500">Manage your shop profile and appearance</p>
            </div>
            <a
                :href="`/${profile.username}`"
                target="_blank"
                class="inline-flex items-center gap-2 self-start rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-indigo-600 shadow-sm transition-colors hover:bg-indigo-50 sm:self-auto"
            >
                <ExternalLink class="h-4 w-4" />
                View Shop
            </a>
        </div>

        <form @submit.prevent="submit" class="space-y-8">
            <!-- Shop URL Section -->
            <section class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Shop URL</h2>
                <p class="mt-1 text-sm text-slate-500">Your public shop link — share it anywhere.</p>

                <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="flex flex-1">
                        <span class="inline-flex items-center rounded-l-xl border border-r-0 border-slate-200 bg-slate-100 px-3 text-sm text-slate-500">
                            {{ siteOrigin }}/
                        </span>
                        <input
                            v-model="form.username"
                            type="text"
                            placeholder="username"
                            class="w-full rounded-r-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder:text-slate-400 transition focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10"
                        />
                    </div>
                    <button
                        type="button"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50"
                        @click="copyUrl"
                    >
                        <Check v-if="copied" class="h-4 w-4 text-emerald-500" />
                        <Copy v-else class="h-4 w-4" />
                        {{ copied ? 'Copied!' : 'Copy' }}
                    </button>
                </div>
                <p v-if="form.errors.username" class="mt-1 text-sm text-rose-600">
                    {{ form.errors.username }}
                </p>
            </section>

            <!-- Avatar Section -->
            <section class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Profile Photo</h2>
                <p class="mt-1 text-sm text-slate-500">A clear photo helps customers recognize your shop.</p>

                <div class="mt-5 flex justify-center">
                    <AvatarUpload
                        :model-value="form.photo"
                        :current-photo="profile.photo"
                        @update:model-value="handlePhotoChange"
                        @remove="handlePhotoRemove"
                    />
                </div>
            </section>

            <!-- Basic Info Section -->
            <section class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Basic Information</h2>
                <p class="mt-1 text-sm text-slate-500">Your shop's public profile details.</p>

                <div class="mt-6 space-y-6">
                    <!-- Name -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">
                            Shop Name <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="My Awesome Shop"
                            maxlength="100"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder:text-slate-400 transition focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10"
                        />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-rose-600">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Bio -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">
                            Bio
                        </label>
                        <textarea
                            v-model="form.bio"
                            rows="3"
                            class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder:text-slate-400 transition focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10"
                            placeholder="Tell visitors about your shop..."
                            maxlength="500"
                        />
                        <div class="flex justify-between">
                            <p v-if="form.errors.bio" class="text-sm text-rose-600">
                                {{ form.errors.bio }}
                            </p>
                            <p class="ml-auto text-sm text-slate-400">
                                {{ form.bio.length }}/500
                            </p>
                        </div>
                    </div>

                    <!-- WhatsApp -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">
                            WhatsApp Number <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.whatsapp"
                            type="tel"
                            placeholder="01712345678"
                            maxlength="11"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder:text-slate-400 transition focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10"
                        />
                        <p v-if="form.errors.whatsapp" class="mt-1 text-sm text-rose-600">
                            {{ form.errors.whatsapp }}
                        </p>
                        <p class="text-sm text-slate-500">
                            Bangladesh number format (e.g., 01712345678)
                        </p>
                    </div>
                </div>
            </section>

            <!-- Theme Color Section -->
            <section class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Theme Color</h2>
                <p class="mt-1 text-sm text-slate-500">Choose an accent color for your shop.</p>

                <div class="mt-5">
                    <ColorPicker
                        v-model="form.theme_color"
                        label=""
                    />
                    <p v-if="form.errors.theme_color" class="mt-2 text-sm text-rose-600">
                        {{ form.errors.theme_color }}
                    </p>
                </div>
            </section>

            <!-- Social Links Section -->
            <section class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Social Links</h2>
                <p class="mt-1 text-sm text-slate-500">Add your social media profiles.</p>

                <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">Facebook</label>
                        <input
                            v-model="form.social_links.facebook"
                            type="url"
                            placeholder="https://facebook.com/yourpage"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder:text-slate-400 transition focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10"
                        />
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">Instagram</label>
                        <input
                            v-model="form.social_links.instagram"
                            type="url"
                            placeholder="https://instagram.com/yourpage"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder:text-slate-400 transition focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10"
                        />
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">YouTube</label>
                        <input
                            v-model="form.social_links.youtube"
                            type="url"
                            placeholder="https://youtube.com/@yourchannel"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder:text-slate-400 transition focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10"
                        />
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">TikTok</label>
                        <input
                            v-model="form.social_links.tiktok"
                            type="url"
                            placeholder="https://tiktok.com/@yourprofile"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder:text-slate-400 transition focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10"
                        />
                    </div>
                </div>
            </section>

            <!-- SEO Section -->
            <section class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">SEO Settings</h2>
                <p class="mt-1 text-sm text-slate-500">Optimize your shop for search engines.</p>

                <div class="mt-6 space-y-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">SEO Title</label>
                        <input
                            v-model="form.seo_title"
                            type="text"
                            placeholder="My Shop - Best Products in Town"
                            maxlength="60"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder:text-slate-400 transition focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10"
                        />
                        <p class="text-sm text-slate-500">
                            {{ form.seo_title.length }}/60 characters
                        </p>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">SEO Description</label>
                        <textarea
                            v-model="form.seo_description"
                            rows="2"
                            class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder:text-slate-400 transition focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10"
                            placeholder="Describe your shop for search engines..."
                            maxlength="160"
                        />
                        <p class="text-sm text-slate-500">
                            {{ form.seo_description.length }}/160 characters
                        </p>
                    </div>
                </div>
            </section>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-500/30 disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0 disabled:hover:shadow-sm"
                >
                    <Loader2 v-if="form.processing" class="h-4 w-4 animate-spin" />
                    <Save v-else class="h-4 w-4" />
                    {{ form.processing ? 'Saving...' : 'Save Changes' }}
                </button>
            </div>
        </form>
    </div>
</template>
