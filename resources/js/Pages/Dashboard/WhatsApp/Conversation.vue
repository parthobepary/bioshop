<script setup lang="ts">
import { ref, nextTick, onMounted } from 'vue'
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Button } from '@/Components/ui/button'
import {
    ArrowLeft,
    Send,
    User,
    Bot,
    UserCircle,
    MoreVertical,
    CheckCircle,
    Clock,
    AlertCircle,
    Phone,
    Package,
} from 'lucide-vue-next'

interface Product {
    id: number
    name: string
    price: number
}

interface Message {
    id: number
    direction: 'incoming' | 'outgoing'
    sender_type: 'customer' | 'ai' | 'seller'
    content: string
    message_type: string
    status: string
    created_at: string
    product: Product | null
}

interface Conversation {
    id: number
    customer_phone: string
    customer_name: string | null
    status: string
    last_message_at: string
    unread_count: number
}

interface Props {
    conversation: Conversation
    messages: Message[]
}

const props = defineProps<Props>()

defineOptions({
    layout: DashboardLayout,
})

const messagesContainer = ref<HTMLElement | null>(null)
const showStatusMenu = ref(false)

const form = useForm({
    message: '',
})

const scrollToBottom = () => {
    nextTick(() => {
        if (messagesContainer.value) {
            messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
        }
    })
}

onMounted(() => {
    scrollToBottom()
})

const sendMessage = () => {
    if (!form.message.trim()) return

    form.post(`/dashboard/whatsapp/${props.conversation.id}/reply`, {
        onSuccess: () => {
            form.reset()
            scrollToBottom()
        },
        preserveScroll: true,
    })
}

const updateStatus = (status: string) => {
    router.post(`/dashboard/whatsapp/${props.conversation.id}/status`, {
        status,
    }, {
        preserveScroll: true,
    })
    showStatusMenu.value = false
}

const formatTime = (dateString: string) => {
    return new Date(dateString).toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true,
    })
}

const formatDate = (dateString: string) => {
    const date = new Date(dateString)
    const today = new Date()
    const yesterday = new Date(today)
    yesterday.setDate(yesterday.getDate() - 1)

    if (date.toDateString() === today.toDateString()) {
        return 'Today'
    } else if (date.toDateString() === yesterday.toDateString()) {
        return 'Yesterday'
    } else {
        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: date.getFullYear() !== today.getFullYear() ? 'numeric' : undefined,
        })
    }
}

const formatPhone = (phone: string) => {
    if (phone.startsWith('880')) {
        return '+' + phone.slice(0, 3) + ' ' + phone.slice(3, 7) + '-' + phone.slice(7)
    }
    return phone
}

const getSenderIcon = (senderType: string) => {
    switch (senderType) {
        case 'customer': return User
        case 'ai': return Bot
        case 'seller': return UserCircle
        default: return User
    }
}

const getStatusColor = (status: string) => {
    switch (status) {
        case 'active': return 'text-green-600 bg-green-100'
        case 'pending': return 'text-amber-600 bg-amber-100'
        case 'resolved': return 'text-slate-600 bg-slate-100'
        default: return 'text-slate-600 bg-slate-100'
    }
}

const groupMessagesByDate = (messages: Message[]) => {
    const groups: { date: string; messages: Message[] }[] = []
    let currentDate = ''

    messages.forEach(message => {
        const messageDate = formatDate(message.created_at)
        if (messageDate !== currentDate) {
            currentDate = messageDate
            groups.push({ date: messageDate, messages: [] })
        }
        groups[groups.length - 1].messages.push(message)
    })

    return groups
}
</script>

<template>
    <Head :title="`Chat with ${conversation.customer_name || formatPhone(conversation.customer_phone)}`" />

    <div class="flex flex-col h-[calc(100vh-120px)]">
        <!-- Header -->
        <div class="bg-white border-b border-line px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <Link
                    href="/dashboard/whatsapp"
                    class="p-2 hover:bg-slate-100 rounded-lg transition-colors"
                >
                    <ArrowLeft class="w-5 h-5 text-slate-600" />
                </Link>
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <User class="w-5 h-5 text-green-600" />
                </div>
                <div>
                    <h2 class="font-semibold text-slate-900">
                        {{ conversation.customer_name || 'Customer' }}
                    </h2>
                    <p class="text-sm text-slate-500">{{ formatPhone(conversation.customer_phone) }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <!-- Status Badge -->
                <div class="relative">
                    <button
                        @click="showStatusMenu = !showStatusMenu"
                        :class="['px-3 py-1.5 rounded-lg text-sm font-medium', getStatusColor(conversation.status)]"
                    >
                        {{ conversation.status }}
                    </button>

                    <!-- Status Dropdown -->
                    <div
                        v-if="showStatusMenu"
                        class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-line py-1 z-10"
                    >
                        <button
                            @click="updateStatus('active')"
                            class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 flex items-center gap-2"
                        >
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            Active
                        </button>
                        <button
                            @click="updateStatus('pending')"
                            class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 flex items-center gap-2"
                        >
                            <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                            Pending
                        </button>
                        <button
                            @click="updateStatus('resolved')"
                            class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 flex items-center gap-2"
                        >
                            <div class="w-2 h-2 rounded-full bg-slate-400"></div>
                            Resolved
                        </button>
                    </div>
                </div>

                <a
                    :href="`tel:${conversation.customer_phone}`"
                    class="p-2 hover:bg-slate-100 rounded-lg transition-colors"
                >
                    <Phone class="w-5 h-5 text-slate-600" />
                </a>
            </div>
        </div>

        <!-- Messages -->
        <div
            ref="messagesContainer"
            class="flex-1 overflow-y-auto bg-slate-50 px-4 py-4"
        >
            <div v-if="messages.length === 0" class="text-center py-12">
                <Bot class="w-12 h-12 text-slate-300 mx-auto mb-4" />
                <p class="text-slate-500">No messages yet</p>
            </div>

            <div v-else class="space-y-6">
                <div
                    v-for="group in groupMessagesByDate(messages)"
                    :key="group.date"
                >
                    <!-- Date Divider -->
                    <div class="flex items-center justify-center mb-4">
                        <span class="px-3 py-1 bg-white text-slate-500 text-xs rounded-full shadow-sm">
                            {{ group.date }}
                        </span>
                    </div>

                    <!-- Messages -->
                    <div class="space-y-3">
                        <div
                            v-for="message in group.messages"
                            :key="message.id"
                            :class="[
                                'flex gap-2',
                                message.direction === 'outgoing' ? 'justify-end' : 'justify-start'
                            ]"
                        >
                            <!-- Avatar (for incoming) -->
                            <div
                                v-if="message.direction === 'incoming'"
                                class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0"
                            >
                                <User class="w-4 h-4 text-green-600" />
                            </div>

                            <!-- Message Bubble -->
                            <div
                                :class="[
                                    'max-w-[70%] rounded-2xl px-4 py-2',
                                    message.direction === 'outgoing'
                                        ? message.sender_type === 'ai'
                                            ? 'bg-purple-100 text-purple-900'
                                            : 'accent-bg'
                                        : 'bg-white text-slate-900 shadow-sm'
                                ]"
                            >
                                <!-- Sender Label -->
                                <div
                                    v-if="message.direction === 'outgoing'"
                                    :class="[
                                        'text-xs mb-1 flex items-center gap-1',
                                        message.sender_type === 'ai' ? 'text-purple-600' : 'text-white/70'
                                    ]"
                                >
                                    <component :is="getSenderIcon(message.sender_type)" class="w-3 h-3" />
                                    {{ message.sender_type === 'ai' ? 'AI Assistant' : 'You' }}
                                </div>

                                <!-- Product Card (if applicable) -->
                                <div
                                    v-if="message.product"
                                    class="bg-white/10 rounded-lg p-2 mb-2 flex items-center gap-2"
                                >
                                    <Package class="w-4 h-4" />
                                    <span class="text-sm">{{ message.product.name }}</span>
                                </div>

                                <!-- Content -->
                                <p class="whitespace-pre-wrap break-words">{{ message.content }}</p>

                                <!-- Time & Status -->
                                <div
                                    :class="[
                                        'text-xs mt-1 flex items-center gap-1 justify-end',
                                        message.direction === 'outgoing'
                                            ? message.sender_type === 'ai' ? 'text-purple-500' : 'text-white/70'
                                            : 'text-slate-400'
                                    ]"
                                >
                                    {{ formatTime(message.created_at) }}
                                    <CheckCircle
                                        v-if="message.direction === 'outgoing' && message.status === 'read'"
                                        class="w-3 h-3"
                                    />
                                </div>
                            </div>

                            <!-- Avatar (for outgoing) -->
                            <div
                                v-if="message.direction === 'outgoing'"
                                :class="[
                                    'w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0',
                                    message.sender_type === 'ai' ? 'bg-purple-100' : 'accent-tint'
                                ]"
                            >
                                <component
                                    :is="getSenderIcon(message.sender_type)"
                                    :class="[
                                        'w-4 h-4',
                                        message.sender_type === 'ai' ? 'text-purple-600' : 'accent-text'
                                    ]"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input -->
        <div class="bg-white border-t border-line px-4 py-3">
            <form @submit.prevent="sendMessage" class="flex items-center gap-3">
                <input
                    v-model="form.message"
                    type="text"
                    placeholder="Type a message..."
                    class="flex-1 px-4 py-2.5 bg-slate-100 border-0 rounded-full accent-focus"
                    :disabled="form.processing"
                />
                <Button
                    type="submit"
                    :disabled="form.processing || !form.message.trim()"
                    class="rounded-full w-10 h-10 p-0 flex items-center justify-center"
                >
                    <Send class="w-5 h-5" />
                </Button>
            </form>
        </div>
    </div>
</template>
