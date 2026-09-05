<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import {
    ArrowLeft,
    Bot,
    Clock,
    MessageSquare,
    Settings,
    Plus,
    Trash2,
    Save,
    HelpCircle,
    Bell,
    Zap,
} from 'lucide-vue-next'

interface FaqItem {
    question: string
    answer: string
}

interface WhatsappSettings {
    id: number
    ai_enabled: boolean
    auto_reply_enabled: boolean
    order_notifications: boolean
    welcome_message: string | null
    away_message: string | null
    business_hours_start: string | null
    business_hours_end: string | null
    business_days: string[] | null
    ai_instructions: string | null
    faq_items: FaqItem[] | null
    quick_replies: string[] | null
}

interface Props {
    settings: WhatsappSettings
}

const props = defineProps<Props>()

defineOptions({
    layout: DashboardLayout,
})

const form = useForm({
    ai_enabled: props.settings.ai_enabled,
    auto_reply_enabled: props.settings.auto_reply_enabled,
    order_notifications: props.settings.order_notifications,
    welcome_message: props.settings.welcome_message || '',
    away_message: props.settings.away_message || '',
    business_hours_start: props.settings.business_hours_start || '09:00',
    business_hours_end: props.settings.business_hours_end || '18:00',
    business_days: props.settings.business_days || ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday'],
    ai_instructions: props.settings.ai_instructions || '',
    faq_items: props.settings.faq_items || [],
    quick_replies: props.settings.quick_replies || [],
})

const allDays = [
    { value: 'saturday', label: 'Saturday' },
    { value: 'sunday', label: 'Sunday' },
    { value: 'monday', label: 'Monday' },
    { value: 'tuesday', label: 'Tuesday' },
    { value: 'wednesday', label: 'Wednesday' },
    { value: 'thursday', label: 'Thursday' },
    { value: 'friday', label: 'Friday' },
]

const addFaqItem = () => {
    form.faq_items.push({ question: '', answer: '' })
}

const removeFaqItem = (index: number) => {
    form.faq_items.splice(index, 1)
}

const addQuickReply = () => {
    form.quick_replies.push('')
}

const removeQuickReply = (index: number) => {
    form.quick_replies.splice(index, 1)
}

const toggleDay = (day: string) => {
    const index = form.business_days.indexOf(day)
    if (index > -1) {
        form.business_days.splice(index, 1)
    } else {
        form.business_days.push(day)
    }
}

const submit = () => {
    form.post('/dashboard/whatsapp/settings', {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="WhatsApp Settings - Dashboard" />

    <div class="space-y-6 max-w-3xl">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <Link
                href="/dashboard/whatsapp"
                class="p-2 hover:bg-slate-100 rounded-lg transition-colors"
            >
                <ArrowLeft class="w-5 h-5 text-slate-600" />
            </Link>
            <div>
                <h1 class="text-xl font-semibold text-slate-900">WhatsApp Settings</h1>
                <p class="text-slate-500 mt-1">Configure AI assistant and auto-replies</p>
            </div>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <!-- AI Settings -->
            <div class="bg-white rounded-xl border border-line p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <Bot class="w-5 h-5 text-purple-600" />
                    </div>
                    <div>
                        <h2 class="font-semibold text-slate-900">AI Assistant</h2>
                        <p class="text-sm text-slate-500">Configure automatic AI responses</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Enable AI -->
                    <label class="flex items-center justify-between p-4 bg-slate-50 rounded-lg cursor-pointer">
                        <div class="flex items-center gap-3">
                            <Zap class="w-5 h-5 text-purple-500" />
                            <div>
                                <p class="font-medium text-slate-900">Enable AI Assistant</p>
                                <p class="text-sm text-slate-500">Let AI respond to customer messages automatically</p>
                            </div>
                        </div>
                        <input
                            v-model="form.ai_enabled"
                            type="checkbox"
                            class="w-5 h-5 accent-text rounded focus:accent-ring"
                        />
                    </label>

                    <!-- Auto Reply -->
                    <label class="flex items-center justify-between p-4 bg-slate-50 rounded-lg cursor-pointer">
                        <div class="flex items-center gap-3">
                            <MessageSquare class="w-5 h-5 text-green-500" />
                            <div>
                                <p class="font-medium text-slate-900">Auto Reply</p>
                                <p class="text-sm text-slate-500">Automatically send replies to new messages</p>
                            </div>
                        </div>
                        <input
                            v-model="form.auto_reply_enabled"
                            type="checkbox"
                            class="w-5 h-5 accent-text rounded focus:accent-ring"
                        />
                    </label>

                    <!-- Order Notifications -->
                    <label class="flex items-center justify-between p-4 bg-slate-50 rounded-lg cursor-pointer">
                        <div class="flex items-center gap-3">
                            <Bell class="w-5 h-5 text-orange-500" />
                            <div>
                                <p class="font-medium text-slate-900">Order Notifications</p>
                                <p class="text-sm text-slate-500">Get notified when customers want to order</p>
                            </div>
                        </div>
                        <input
                            v-model="form.order_notifications"
                            type="checkbox"
                            class="w-5 h-5 accent-text rounded focus:accent-ring"
                        />
                    </label>

                    <!-- AI Instructions -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Custom AI Instructions
                        </label>
                        <textarea
                            v-model="form.ai_instructions"
                            rows="3"
                            placeholder="Add any special instructions for the AI assistant..."
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg accent-focus accent-focus"
                        ></textarea>
                        <p class="text-xs text-slate-500 mt-1">
                            Example: "Always greet customers in Bengali. Mention free delivery for orders over 1000 taka."
                        </p>
                    </div>
                </div>
            </div>

            <!-- Business Hours -->
            <div class="bg-white rounded-xl border border-line p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 accent-tint rounded-lg flex items-center justify-center">
                        <Clock class="w-5 h-5 accent-text" />
                    </div>
                    <div>
                        <h2 class="font-semibold text-slate-900">Business Hours</h2>
                        <p class="text-sm text-slate-500">Set when you're available to respond</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Time Range -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Start Time
                            </label>
                            <Input
                                v-model="form.business_hours_start"
                                type="time"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                End Time
                            </label>
                            <Input
                                v-model="form.business_hours_end"
                                type="time"
                            />
                        </div>
                    </div>

                    <!-- Days -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Business Days
                        </label>
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="day in allDays"
                                :key="day.value"
                                type="button"
                                @click="toggleDay(day.value)"
                                :class="[
                                    'px-3 py-1.5 rounded-lg text-sm font-medium transition-colors',
                                    form.business_days.includes(day.value)
                                        ? 'accent-bg'
                                        : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                                ]"
                            >
                                {{ day.label }}
                            </button>
                        </div>
                    </div>

                    <!-- Away Message -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Away Message
                        </label>
                        <textarea
                            v-model="form.away_message"
                            rows="2"
                            placeholder="Message sent outside business hours..."
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg accent-focus accent-focus"
                        ></textarea>
                    </div>
                </div>
            </div>

            <!-- Welcome Message -->
            <div class="bg-white rounded-xl border border-line p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <MessageSquare class="w-5 h-5 text-green-600" />
                    </div>
                    <div>
                        <h2 class="font-semibold text-slate-900">Welcome Message</h2>
                        <p class="text-sm text-slate-500">First message sent to new customers</p>
                    </div>
                </div>

                <textarea
                    v-model="form.welcome_message"
                    rows="3"
                    placeholder="আস্সালামু আলাইকুম! আমাদের শপে স্বাগতম। কিভাবে সাহায্য করতে পারি?"
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg accent-focus accent-focus"
                ></textarea>
            </div>

            <!-- FAQ Items -->
            <div class="bg-white rounded-xl border border-line p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <HelpCircle class="w-5 h-5 text-orange-600" />
                        </div>
                        <div>
                            <h2 class="font-semibold text-slate-900">FAQ Answers</h2>
                            <p class="text-sm text-slate-500">Common questions and answers for AI</p>
                        </div>
                    </div>
                    <Button type="button" @click="addFaqItem" variant="outline" size="sm">
                        <Plus class="w-4 h-4 mr-1" />
                        Add FAQ
                    </Button>
                </div>

                <div class="space-y-4">
                    <div
                        v-for="(faq, index) in form.faq_items"
                        :key="index"
                        class="p-4 bg-slate-50 rounded-lg"
                    >
                        <div class="flex items-start justify-between mb-3">
                            <span class="text-xs font-medium text-slate-500">FAQ #{{ index + 1 }}</span>
                            <button
                                type="button"
                                @click="removeFaqItem(index)"
                                class="text-red-500 hover:text-red-700"
                            >
                                <Trash2 class="w-4 h-4" />
                            </button>
                        </div>
                        <div class="space-y-3">
                            <Input
                                v-model="faq.question"
                                placeholder="Question (e.g., ডেলিভারি কতদিনে হয়?)"
                            />
                            <textarea
                                v-model="faq.answer"
                                rows="2"
                                placeholder="Answer..."
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg accent-focus accent-focus text-sm"
                            ></textarea>
                        </div>
                    </div>

                    <div v-if="form.faq_items.length === 0" class="text-center py-8 text-slate-500">
                        <HelpCircle class="w-8 h-8 mx-auto mb-2 text-slate-300" />
                        <p>No FAQ items yet. Add common questions and answers.</p>
                    </div>
                </div>
            </div>

            <!-- Quick Replies -->
            <div class="bg-white rounded-xl border border-line p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 accent-tint rounded-lg flex items-center justify-center">
                            <Zap class="w-5 h-5 accent-text" />
                        </div>
                        <div>
                            <h2 class="font-semibold text-slate-900">Quick Replies</h2>
                            <p class="text-sm text-slate-500">Pre-written messages for fast responses</p>
                        </div>
                    </div>
                    <Button type="button" @click="addQuickReply" variant="outline" size="sm">
                        <Plus class="w-4 h-4 mr-1" />
                        Add Reply
                    </Button>
                </div>

                <div class="space-y-3">
                    <div
                        v-for="(reply, index) in form.quick_replies"
                        :key="index"
                        class="flex items-center gap-2"
                    >
                        <Input
                            v-model="form.quick_replies[index]"
                            placeholder="Quick reply message..."
                            class="flex-1"
                        />
                        <button
                            type="button"
                            @click="removeQuickReply(index)"
                            class="p-2 text-red-500 hover:text-red-700"
                        >
                            <Trash2 class="w-4 h-4" />
                        </button>
                    </div>

                    <div v-if="form.quick_replies.length === 0" class="text-center py-8 text-slate-500">
                        <Zap class="w-8 h-8 mx-auto mb-2 text-slate-300" />
                        <p>No quick replies yet. Add messages for faster responses.</p>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <Button type="submit" :disabled="form.processing" size="lg">
                    <Save class="w-5 h-5 mr-2" />
                    {{ form.processing ? 'Saving...' : 'Save Settings' }}
                </Button>
            </div>
        </form>
    </div>
</template>
