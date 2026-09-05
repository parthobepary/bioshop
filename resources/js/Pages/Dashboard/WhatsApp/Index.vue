<script setup lang="ts">
import { ref, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Input } from '@/Components/ui/input'
import { Button } from '@/Components/ui/button'
import { useDebounceFn } from '@vueuse/core'
import {
    MessageCircle,
    Search,
    Settings,
    User,
    Clock,
    CheckCircle,
    AlertCircle,
    MessageSquare,
} from 'lucide-vue-next'

interface Message {
    id: number
    content: string
    created_at: string
}

interface Conversation {
    id: number
    customer_phone: string
    customer_name: string | null
    status: string
    last_message_at: string
    unread_count: number
    messages_count: number
    latest_message: Message | null
}

interface PaginatedConversations {
    data: Conversation[]
    current_page: number
    last_page: number
    per_page: number
    total: number
    links: { url: string | null; label: string; active: boolean }[]
}

interface Stats {
    total: number
    active: number
    unread: number
}

interface Filters {
    status: string
    search: string
}

interface Props {
    conversations: PaginatedConversations
    stats: Stats
    /** False when the WhatsApp Business API credentials are missing. */
    apiConfigured: boolean
    filters: Filters
}

const props = defineProps<Props>()

defineOptions({
    layout: DashboardLayout,
})

const search = ref(props.filters.search)
const status = ref(props.filters.status)

const applyFilters = useDebounceFn(() => {
    router.get('/dashboard/whatsapp', {
        search: search.value || undefined,
        status: status.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
    })
}, 300)

watch([search, status], applyFilters)

const formatDate = (dateString: string) => {
    const date = new Date(dateString)
    const now = new Date()
    const diffMs = now.getTime() - date.getTime()
    const diffMins = Math.floor(diffMs / 60000)
    const diffHours = Math.floor(diffMs / 3600000)
    const diffDays = Math.floor(diffMs / 86400000)

    if (diffMins < 1) return 'এখনই'
    if (diffMins < 60) return `${diffMins} মিনিট আগে`
    if (diffHours < 24) return `${diffHours} ঘন্টা আগে`
    if (diffDays < 7) return `${diffDays} দিন আগে`

    return date.toLocaleDateString('bn-BD', {
        month: 'short',
        day: 'numeric',
    })
}

const formatPhone = (phone: string) => {
    if (phone.startsWith('880')) {
        return '+' + phone.slice(0, 3) + ' ' + phone.slice(3, 7) + '-' + phone.slice(7)
    }
    return phone
}

const getStatusColor = (status: string) => {
    switch (status) {
        case 'active': return 'bg-green-500'
        case 'pending': return 'bg-amber-500'
        case 'resolved': return 'bg-slate-400'
        default: return 'bg-slate-400'
    }
}

const getStatusIcon = (status: string) => {
    switch (status) {
        case 'active': return MessageSquare
        case 'pending': return AlertCircle
        case 'resolved': return CheckCircle
        default: return MessageCircle
    }
}
</script>

<template>
    <Head title="WhatsApp - Dashboard" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">WhatsApp Messages</h1>
                <p class="text-slate-500 mt-1">Manage customer conversations</p>
            </div>
            <Link href="/dashboard/whatsapp/settings">
                <Button variant="outline">
                    <Settings class="w-4 h-4 mr-2" />
                    Settings
                </Button>
            </Link>
        </div>

        <!-- Credentials missing: history still reads, but replies will not send -->
        <div
            v-if="!apiConfigured"
            class="flex items-start gap-3 rounded-xl border border-warning-100 bg-warning-50 px-4 py-3.5"
        >
            <AlertCircle class="mt-0.5 h-4 w-4 shrink-0 text-warning-600" />
            <div>
                <p class="text-[13px] font-medium text-ink-900">WhatsApp API is not connected</p>
                <p class="mt-0.5 text-[13px] text-ink-600">
                    You can read past conversations, but replies will not be delivered until
                    <code class="rounded bg-white px-1 py-0.5 text-[12px]">WHATSAPP_ACCESS_TOKEN</code>
                    and
                    <code class="rounded bg-white px-1 py-0.5 text-[12px]">WHATSAPP_PHONE_NUMBER_ID</code>
                    are set.
                </p>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl p-5 border border-line">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm">Total Conversations</p>
                        <p class="text-xl font-semibold text-slate-900 mt-1">{{ stats.total }}</p>
                    </div>
                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
                        <MessageCircle class="w-6 h-6 text-slate-600" />
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 border border-line">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm">Active</p>
                        <p class="text-xl font-semibold text-green-600 mt-1">{{ stats.active }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <MessageSquare class="w-6 h-6 text-green-600" />
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 border border-line">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm">Unread</p>
                        <p class="text-xl font-semibold text-orange-600 mt-1">{{ stats.unread }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <AlertCircle class="w-6 h-6 text-orange-600" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap gap-4">
            <div class="relative flex-1 min-w-[200px]">
                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
                <Input
                    v-model="search"
                    type="text"
                    placeholder="Search by name or phone..."
                    class="pl-10"
                />
            </div>
            <select
                v-model="status"
                class="py-2 pl-3.5 pr-9 bg-white border border-line rounded-lg text-sm text-ink-800 focus:accent-border focus:outline-none focus:ring-2 focus:ring-accent-600/15"
            >
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="pending">Pending</option>
                <option value="resolved">Resolved</option>
            </select>
        </div>

        <!-- Conversations List -->
        <div class="bg-white rounded-xl border border-line overflow-hidden">
            <div v-if="conversations.data.length === 0" class="p-12 text-center">
                <MessageCircle class="w-12 h-12 text-slate-300 mx-auto mb-4" />
                <h3 class="text-lg font-semibold text-slate-900 mb-2">No conversations yet</h3>
                <p class="text-slate-500">When customers message you on WhatsApp, conversations will appear here.</p>
            </div>

            <div v-else class="divide-y divide-line">
                <Link
                    v-for="conversation in conversations.data"
                    :key="conversation.id"
                    :href="`/dashboard/whatsapp/${conversation.id}`"
                    class="flex items-center gap-4 p-4 hover:bg-slate-50 transition-colors"
                >
                    <!-- Avatar -->
                    <div class="relative flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <User class="w-6 h-6 text-green-600" />
                        </div>
                        <div
                            :class="['absolute -bottom-0.5 -right-0.5 w-4 h-4 rounded-full border-2 border-white', getStatusColor(conversation.status)]"
                        ></div>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-semibold text-slate-900 truncate">
                                {{ conversation.customer_name || formatPhone(conversation.customer_phone) }}
                            </h3>
                            <span class="text-xs text-slate-500 flex-shrink-0 ml-2">
                                {{ formatDate(conversation.last_message_at) }}
                            </span>
                        </div>
                        <p class="text-sm text-slate-500 truncate">
                            {{ conversation.latest_message?.content || 'No messages' }}
                        </p>
                    </div>

                    <!-- Unread Badge -->
                    <div v-if="conversation.unread_count > 0" class="flex-shrink-0">
                        <span class="px-2 py-1 bg-green-500 text-white text-xs font-bold rounded-full">
                            {{ conversation.unread_count }}
                        </span>
                    </div>
                </Link>
            </div>

            <!-- Pagination -->
            <div v-if="conversations.last_page > 1" class="px-6 py-4 border-t border-line flex items-center justify-between">
                <p class="text-sm text-slate-500">
                    Showing {{ (conversations.current_page - 1) * conversations.per_page + 1 }} to
                    {{ Math.min(conversations.current_page * conversations.per_page, conversations.total) }} of
                    {{ conversations.total }}
                </p>
                <div class="flex items-center gap-2">
                    <template v-for="link in conversations.links" :key="link.label">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            :class="[
                                'px-3 py-1.5 rounded-lg text-sm transition-colors',
                                link.active
                                    ? 'accent-bg'
                                    : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                            ]"
                            v-html="link.label"
                        />
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
