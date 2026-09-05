<script setup lang="ts">
import { computed, ref } from 'vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import AvatarUpload from '@/Components/common/AvatarUpload.vue'
import ColorPicker from '@/Components/common/ColorPicker.vue'
import { mediaUrl } from '@/lib/media'
import { shopVars } from '@/lib/color'
import {
    Check,
    Copy,
    ExternalLink,
    Image as ImageIcon,
    Link2,
    Loader2,
    MessageCircle,
    Palette,
    Save,
    Search,
    Share2,
    Store,
} from 'lucide-vue-next'

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
    photo: null as string | null,
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

/**
 * Every accent on this page comes from the colour the seller picked, so the
 * panel always matches the public shop page it configures.
 */
const themeVars = computed(() => shopVars(form.theme_color))

const copied = ref(false)
const siteOrigin = typeof window !== 'undefined' ? window.location.origin : ''
const shopUrl = computed(() => `${siteOrigin}/${form.username}`)

const previewPhoto = computed(() => {
    const photo = form.photo || props.profile.photo
    return photo ? mediaUrl(photo) : null
})

const initials = computed(() =>
    (form.name || props.profile.username)
        .split(' ')
        .map((word) => word[0])
        .join('')
        .toUpperCase()
        .slice(0, 2),
)

const socialFields = [
    { key: 'facebook', label: 'Facebook', placeholder: 'https://facebook.com/yourpage' },
    { key: 'instagram', label: 'Instagram', placeholder: 'https://instagram.com/yourpage' },
    { key: 'youtube', label: 'YouTube', placeholder: 'https://youtube.com/@yourchannel' },
    { key: 'tiktok', label: 'TikTok', placeholder: 'https://tiktok.com/@yourprofile' },
]

/** Shared field styling: the focus state picks up the shop colour. */
const field =
    'w-full rounded-lg border border-line bg-white px-3.5 py-2.5 text-sm text-ink-900 placeholder:text-ink-400 transition-colors focus:outline-none focus:border-[color:var(--shop)] focus:ring-2 focus:ring-[color:var(--shop-ring)]'

const copyUrl = () => {
    navigator.clipboard.writeText(shopUrl.value)
    copied.value = true
    setTimeout(() => (copied.value = false), 2000)
}

const handlePhotoChange = (path: string | null) => {
    form.photo = path
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

    <div :style="themeVars" class="mx-auto max-w-3xl space-y-5">
        <!-- Identity strip, painted in the shop's colour -->
        <header class="overflow-hidden rounded-xl border border-[color:var(--shop-line)] bg-white">
            <div class="h-14 bg-[color:var(--shop)]"></div>

            <div class="flex flex-col gap-4 px-5 pb-5 sm:flex-row sm:items-end sm:justify-between">
                <div class="flex items-end gap-3">
                    <span class="-mt-8 block h-16 w-16 shrink-0 rounded-full ring-4 ring-white">
                        <img
                            v-if="previewPhoto"
                            :src="previewPhoto"
                            :alt="form.name"
                            class="h-16 w-16 rounded-full object-cover"
                        />
                        <span
                            v-else
                            class="flex h-16 w-16 items-center justify-center rounded-full bg-[color:var(--shop)] text-[15px] font-semibold text-[color:var(--shop-on)]"
                        >
                            {{ initials }}
                        </span>
                    </span>

                    <div class="min-w-0 pb-0.5">
                        <h1 class="truncate font-display text-lg font-semibold tracking-tight text-ink-900">
                            {{ form.name || 'Your shop' }}
                        </h1>
                        <p class="truncate text-[13px] text-ink-500">
                            {{ siteOrigin }}/<span class="font-medium accent-text">{{ form.username }}</span>
                        </p>
                    </div>
                </div>

                <div class="flex shrink-0 items-center gap-2">
                    <button
                        type="button"
                        class="inline-flex h-9 items-center gap-2 rounded-lg border border-line bg-white px-3 text-[13px] font-medium text-ink-700 transition-colors hover:border-ink-300"
                        @click="copyUrl"
                    >
                        <Check v-if="copied" class="h-3.5 w-3.5 text-success-600" />
                        <Copy v-else class="h-3.5 w-3.5" />
                        {{ copied ? 'Copied' : 'Copy link' }}
                    </button>
                    <a
                        :href="`/${profile.username}`"
                        target="_blank"
                        rel="noopener"
                        class="inline-flex h-9 items-center gap-2 rounded-lg bg-[color:var(--shop)] px-3 text-[13px] font-medium text-[color:var(--shop-on)] transition-opacity hover:opacity-90"
                    >
                        <ExternalLink class="h-3.5 w-3.5" />
                        View shop
                    </a>
                </div>
            </div>
        </header>

        <form class="space-y-5" @submit.prevent="submit">
            <!-- Shop URL -->
            <section class="rounded-xl border border-line bg-white p-5">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg accent-chip">
                        <Link2 class="h-4 w-4" />
                    </span>
                    <div>
                        <h2 class="text-[15px] font-semibold text-ink-900">Shop URL</h2>
                        <p class="text-[13px] text-ink-500">Your public shop link — share it anywhere.</p>
                    </div>
                </div>

                <div class="mt-4 flex">
                    <span class="inline-flex items-center rounded-l-lg border border-r-0 border-line bg-paper-subtle px-3 text-[13px] text-ink-500">
                        {{ siteOrigin }}/
                    </span>
                    <input
                        v-model="form.username"
                        type="text"
                        placeholder="username"
                        :class="[field, 'rounded-l-none']"
                    />
                </div>
                <p v-if="form.errors.username" class="mt-1.5 text-[13px] text-error-600">
                    {{ form.errors.username }}
                </p>
            </section>

            <!-- Photo -->
            <section class="rounded-xl border border-line bg-white p-5">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg accent-chip">
                        <ImageIcon class="h-4 w-4" />
                    </span>
                    <div>
                        <h2 class="text-[15px] font-semibold text-ink-900">Profile photo</h2>
                        <p class="text-[13px] text-ink-500">A clear photo helps customers recognise your shop.</p>
                    </div>
                </div>

                <div class="mt-5 flex justify-center">
                    <AvatarUpload
                        :model-value="form.photo"
                        :current-photo="profile.photo"
                        @update:model-value="handlePhotoChange"
                        @remove="handlePhotoRemove"
                    />
                </div>
            </section>

            <!-- Basic information -->
            <section class="rounded-xl border border-line bg-white p-5">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg accent-chip">
                        <Store class="h-4 w-4" />
                    </span>
                    <div>
                        <h2 class="text-[15px] font-semibold text-ink-900">Basic information</h2>
                        <p class="text-[13px] text-ink-500">Your shop's public profile details.</p>
                    </div>
                </div>

                <div class="mt-5 space-y-4">
                    <div>
                        <label for="shop-name" class="mb-1.5 block text-[13px] font-medium text-ink-700">
                            Shop name <span class="text-error-600">*</span>
                        </label>
                        <input
                            id="shop-name"
                            v-model="form.name"
                            type="text"
                            placeholder="My Awesome Shop"
                            maxlength="100"
                            :class="field"
                        />
                        <p v-if="form.errors.name" class="mt-1.5 text-[13px] text-error-600">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <div>
                        <label for="shop-bio" class="mb-1.5 block text-[13px] font-medium text-ink-700">Bio</label>
                        <textarea
                            id="shop-bio"
                            v-model="form.bio"
                            rows="3"
                            maxlength="500"
                            placeholder="Tell visitors about your shop…"
                            :class="[field, 'resize-none']"
                        />
                        <div class="mt-1.5 flex justify-between gap-3">
                            <p v-if="form.errors.bio" class="text-[13px] text-error-600">{{ form.errors.bio }}</p>
                            <p class="ml-auto text-[12px] text-ink-400">{{ form.bio.length }}/500</p>
                        </div>
                    </div>

                    <div>
                        <label for="shop-whatsapp" class="mb-1.5 block text-[13px] font-medium text-ink-700">
                            WhatsApp number <span class="text-error-600">*</span>
                        </label>
                        <input
                            id="shop-whatsapp"
                            v-model="form.whatsapp"
                            type="tel"
                            placeholder="01712345678"
                            maxlength="11"
                            :class="field"
                        />
                        <p v-if="form.errors.whatsapp" class="mt-1.5 text-[13px] text-error-600">
                            {{ form.errors.whatsapp }}
                        </p>
                        <p class="mt-1.5 text-[12px] text-ink-400">Bangladesh format, e.g. 01712345678</p>
                    </div>
                </div>
            </section>

            <!-- Theme colour + live preview -->
            <section class="rounded-xl border border-[color:var(--shop-line)] bg-white p-5">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg accent-chip">
                        <Palette class="h-4 w-4" />
                    </span>
                    <div>
                        <h2 class="text-[15px] font-semibold text-ink-900">Shop colour</h2>
                        <p class="text-[13px] text-ink-500">
                            Used across your shop page — and across this panel.
                        </p>
                    </div>
                </div>

                <div class="mt-5 grid items-start gap-6 md:grid-cols-[minmax(0,1fr)_216px]">
                    <div>
                        <ColorPicker v-model="form.theme_color" label="" />

                        <div class="mt-5 flex items-center gap-3 rounded-lg border border-line bg-paper-subtle p-3">
                            <span
                                class="h-9 w-9 shrink-0 rounded-lg ring-1 ring-inset ring-black/10"
                                :style="{ backgroundColor: form.theme_color }"
                            ></span>
                            <div class="min-w-0">
                                <p class="font-mono text-[13px] uppercase text-ink-900">{{ form.theme_color }}</p>
                                <p class="text-[12px] text-ink-500">Applied to your shop page and this panel.</p>
                            </div>
                        </div>

                        <p v-if="form.errors.theme_color" class="mt-2 text-[13px] text-error-600">
                            {{ form.errors.theme_color }}
                        </p>
                    </div>

                    <!-- Live preview of the public shop page -->
                    <div class="rounded-xl border border-line bg-paper-subtle p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-ink-400">
                            Live preview
                        </p>

                        <div class="mx-auto mt-3 max-w-[220px] overflow-hidden rounded-xl border border-line bg-white">
                            <div class="h-12 bg-[color:var(--shop)]"></div>

                            <div class="-mt-7 flex flex-col items-center px-3 pb-4">
                                <img
                                    v-if="previewPhoto"
                                    :src="previewPhoto"
                                    :alt="form.name"
                                    class="h-14 w-14 rounded-full object-cover ring-4 ring-white"
                                />
                                <span
                                    v-else
                                    class="flex h-14 w-14 items-center justify-center rounded-full bg-[color:var(--shop)] text-[13px] font-semibold text-[color:var(--shop-on)] ring-4 ring-white"
                                >
                                    {{ initials }}
                                </span>

                                <p class="mt-2 truncate text-[13px] font-semibold text-ink-900">
                                    {{ form.name || 'Your shop' }}
                                </p>
                                <p class="text-[11px] accent-text">@{{ form.username }}</p>

                                <div class="mt-3 flex w-full items-center gap-2 rounded-lg border border-line p-2">
                                    <span class="flex h-5 w-5 items-center justify-center rounded bg-[color:var(--shop-tint)]">
                                        <Link2 class="h-3 w-3 accent-text" />
                                    </span>
                                    <span class="h-1.5 w-16 rounded-full bg-ink-200"></span>
                                </div>

                                <div class="mt-2 flex h-8 w-full items-center justify-center gap-1.5 rounded-lg bg-[color:var(--shop)] text-[11px] font-medium text-[color:var(--shop-on)]">
                                    <MessageCircle class="h-3 w-3" />
                                    Order on WhatsApp
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Social links -->
            <section class="rounded-xl border border-line bg-white p-5">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg accent-chip">
                        <Share2 class="h-4 w-4" />
                    </span>
                    <div>
                        <h2 class="text-[15px] font-semibold text-ink-900">Social links</h2>
                        <p class="text-[13px] text-ink-500">Add your social media profiles.</p>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div v-for="social in socialFields" :key="social.key">
                        <label :for="`social-${social.key}`" class="mb-1.5 block text-[13px] font-medium text-ink-700">
                            {{ social.label }}
                        </label>
                        <input
                            :id="`social-${social.key}`"
                            v-model="form.social_links[social.key]"
                            type="url"
                            :placeholder="social.placeholder"
                            :class="field"
                        />
                    </div>
                </div>
            </section>

            <!-- SEO -->
            <section class="rounded-xl border border-line bg-white p-5">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg accent-chip">
                        <Search class="h-4 w-4" />
                    </span>
                    <div>
                        <h2 class="text-[15px] font-semibold text-ink-900">SEO</h2>
                        <p class="text-[13px] text-ink-500">How your shop appears in search results.</p>
                    </div>
                </div>

                <div class="mt-5 space-y-4">
                    <div>
                        <label for="seo-title" class="mb-1.5 block text-[13px] font-medium text-ink-700">
                            SEO title
                        </label>
                        <input
                            id="seo-title"
                            v-model="form.seo_title"
                            type="text"
                            placeholder="My Shop — Best Products in Town"
                            maxlength="60"
                            :class="field"
                        />
                        <p class="mt-1.5 text-[12px] text-ink-400">{{ form.seo_title.length }}/60 characters</p>
                    </div>

                    <div>
                        <label for="seo-description" class="mb-1.5 block text-[13px] font-medium text-ink-700">
                            SEO description
                        </label>
                        <textarea
                            id="seo-description"
                            v-model="form.seo_description"
                            rows="2"
                            maxlength="160"
                            placeholder="Describe your shop for search engines…"
                            :class="[field, 'resize-none']"
                        />
                        <p class="mt-1.5 text-[12px] text-ink-400">
                            {{ form.seo_description.length }}/160 characters
                        </p>
                    </div>
                </div>
            </section>

            <!-- Save bar follows you down the page -->
            <div class="sticky bottom-0 -mx-4 border-t border-line bg-paper/90 px-4 py-3 backdrop-blur lg:-mx-6 lg:px-6">
                <div class="flex items-center justify-end gap-3">
                    <p v-if="form.isDirty" class="mr-auto text-[13px] text-ink-500">
                        You have unsaved changes.
                    </p>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex h-10 items-center gap-2 rounded-lg bg-[color:var(--shop)] px-5 text-sm font-medium text-[color:var(--shop-on)] transition-opacity hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        <Loader2 v-if="form.processing" class="h-4 w-4 animate-spin" />
                        <Save v-else class="h-4 w-4" />
                        {{ form.processing ? 'Saving…' : 'Save changes' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
