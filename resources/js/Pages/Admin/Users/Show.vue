<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Button } from '@/Components/ui/button'
import {
    ArrowLeft,
    User,
    Mail,
    Shield,
    Ban,
    Calendar,
    CreditCard,
    Package,
    Link as LinkIcon,
    Eye,
    ExternalLink,
    Crown,
    UserX,
    Trash2,
    CheckCircle,
    XCircle,
    Clock,
} from 'lucide-vue-next'

interface Profile {
    id: number
    username: string
    name: string
    bio: string | null
    photo: string | null
    whatsapp: string | null
    products: { id: number; name: string; price: number }[]
    links: { id: number; title: string; url: string }[]
    payment_methods: { id: number; type: string; account_number: string }[]
}

interface Plan {
    id: number
    name: string
    price: number
}

interface Subscription {
    id: number
    status: string
    starts_at: string | null
    ends_at: string | null
    plan: Plan
}

interface Payment {
    id: number
    amount: number
    method: string
    transaction_id: string
    status: string
    created_at: string
    subscription: { plan: Plan } | null
}

interface UserData {
    id: number
    name: string
    email: string
    role: string
    is_active: boolean
    created_at: string
    profile: Profile | null
    subscriptions: Subscription[]
    payments: Payment[]
}

interface Metric {
    total: number
    today: number
    this_week: number
    this_month: number
}

interface Analytics {
    page_views: Metric
    link_clicks: Metric
    product_views: Metric
    whatsapp_clicks: Metric
}

interface Props {
    user: UserData
    analytics: Analytics | null
}

const props = defineProps<Props>()

defineOptions({
    layout: AdminLayout,
})

const processing = ref(false)

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-BD', {
        style: 'currency',
        currency: 'BDT',
        minimumFractionDigits: 0,
    }).format(price).replace('BDT', '৳')
}

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    })
}

const formatDateTime = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    })
}

const toggleBan = () => {
    if (confirm(`Are you sure you want to ${props.user.is_active ? 'ban' : 'unban'} this user?`)) {
        processing.value = true
        router.post(`/admin/users/${props.user.id}/toggle-ban`, {}, {
            onFinish: () => processing.value = false,
        })
    }
}

const makeAdmin = () => {
    if (confirm('Are you sure you want to make this user an admin?')) {
        processing.value = true
        router.post(`/admin/users/${props.user.id}/make-admin`, {}, {
            onFinish: () => processing.value = false,
        })
    }
}

const removeAdmin = () => {
    if (confirm('Are you sure you want to remove admin status from this user?')) {
        processing.value = true
        router.post(`/admin/users/${props.user.id}/remove-admin`, {}, {
            onFinish: () => processing.value = false,
        })
    }
}

const deleteUser = () => {
    if (confirm('Are you sure you want to permanently delete this user? This action cannot be undone.')) {
        processing.value = true
        router.delete(`/admin/users/${props.user.id}`, {
            onFinish: () => processing.value = false,
        })
    }
}

const getStatusColor = (status: string) => {
    switch (status) {
        case 'active': return 'bg-success-500/20 text-success-600'
        case 'pending': return 'bg-warning-500/20 text-warning-600'
        case 'completed': return 'bg-success-500/20 text-success-600'
        case 'cancelled': return 'bg-error-500/20 text-error-600'
        case 'failed': return 'bg-error-500/20 text-error-600'
        case 'refunded': return 'bg-purple-500/20 text-purple-400'
        default: return 'bg-paper-deep text-ink-500'
    }
}

const getActivePlan = () => {
    const active = props.user.subscriptions.find(s => s.status === 'active')
    return active?.plan?.name || 'Free'
}
</script>

<template>
    <Head :title="`${user.name} - Admin`" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <Link
                    href="/admin/users"
                    class="p-2 bg-white hover:bg-paper-deep rounded-lg text-ink-500 hover:text-ink-900 transition-colors"
                >
                    <ArrowLeft class="w-5 h-5" />
                </Link>
                <div>
                    <h1 class="text-xl font-semibold text-ink-900">{{ user.name }}</h1>
                    <p class="text-ink-500 mt-1">User Details</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <Link
                    v-if="user.profile"
                    :href="`/${user.profile.username}`"
                    target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-paper-deep hover:bg-ink-200 text-ink-800 rounded-lg transition-colors"
                >
                    <ExternalLink class="w-4 h-4" />
                    View Shop
                </Link>
            </div>
        </div>

        <!-- User Info Card -->
        <div class="bg-white rounded-2xl border border-line p-6">
            <div class="flex flex-col md:flex-row md:items-start gap-6">
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 bg-paper-deep rounded-2xl flex items-center justify-center text-ink-900 text-2xl font-semibold">
                        {{ user.name.charAt(0) }}
                    </div>
                </div>

                <!-- Info -->
                <div class="flex-1 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="flex items-center gap-3">
                            <User class="w-5 h-5 text-ink-500" />
                            <div>
                                <p class="text-xs text-slate-500">Name</p>
                                <p class="text-ink-900">{{ user.name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <Mail class="w-5 h-5 text-ink-500" />
                            <div>
                                <p class="text-xs text-slate-500">Email</p>
                                <p class="text-ink-900">{{ user.email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <Shield class="w-5 h-5 text-ink-500" />
                            <div>
                                <p class="text-xs text-slate-500">Role</p>
                                <p :class="user.role === 'admin' ? 'text-accent-600' : 'text-ink-900'">
                                    {{ user.role === 'admin' ? 'Admin' : 'User' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <Calendar class="w-5 h-5 text-ink-500" />
                            <div>
                                <p class="text-xs text-slate-500">Joined</p>
                                <p class="text-ink-900">{{ formatDate(user.created_at) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <CreditCard class="w-5 h-5 text-ink-500" />
                            <div>
                                <p class="text-xs text-slate-500">Plan</p>
                                <p class="text-purple-400">{{ getActivePlan() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div :class="user.is_active ? 'text-success-600' : 'text-error-600'">
                                <CheckCircle v-if="user.is_active" class="w-5 h-5" />
                                <XCircle v-else class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Status</p>
                                <p :class="user.is_active ? 'text-success-600' : 'text-error-600'">
                                    {{ user.is_active ? 'Active' : 'Banned' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-wrap gap-2 pt-4 border-t border-line">
                        <Button
                            v-if="user.role !== 'admin'"
                            @click="toggleBan"
                            :disabled="processing"
                            :class="user.is_active ? 'bg-warning-600 hover:bg-warning-600' : 'bg-success-600 hover:bg-success-600'"
                            class="text-ink-900"
                        >
                            <Ban class="w-4 h-4 mr-2" />
                            {{ user.is_active ? 'Ban User' : 'Unban User' }}
                        </Button>
                        <Button
                            v-if="user.role !== 'admin'"
                            @click="makeAdmin"
                            :disabled="processing"
                            class="bg-purple-600 hover:bg-purple-700 text-ink-900"
                        >
                            <Crown class="w-4 h-4 mr-2" />
                            Make Admin
                        </Button>
                        <Button
                            v-if="user.role === 'admin'"
                            @click="removeAdmin"
                            :disabled="processing"
                            class="bg-paper-deep hover:bg-ink-200 text-ink-800"
                        >
                            <UserX class="w-4 h-4 mr-2" />
                            Remove Admin
                        </Button>
                        <Button
                            v-if="user.role !== 'admin'"
                            @click="deleteUser"
                            :disabled="processing"
                            class="bg-error-600 hover:bg-error-500 text-ink-900"
                        >
                            <Trash2 class="w-4 h-4 mr-2" />
                            Delete User
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics (if profile exists) -->
        <div v-if="analytics" class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl p-4 border border-line">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-accent-600/20 rounded-lg flex items-center justify-center">
                        <Eye class="w-5 h-5 text-accent-600" />
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Total Views</p>
                        <p class="text-xl font-bold text-ink-900">{{ (analytics.page_views?.total ?? 0).toLocaleString() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-line">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-success-500/20 rounded-lg flex items-center justify-center">
                        <Eye class="w-5 h-5 text-success-600" />
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Views Today</p>
                        <p class="text-xl font-bold text-ink-900">{{ analytics.page_views?.today ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-line">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-ink-900/20 rounded-lg flex items-center justify-center">
                        <LinkIcon class="w-5 h-5 text-accent-600" />
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Link Clicks</p>
                        <p class="text-xl font-bold text-ink-900">{{ (analytics.link_clicks?.total ?? 0).toLocaleString() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-line">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                        <Package class="w-5 h-5 text-purple-400" />
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Product Views</p>
                        <p class="text-xl font-bold text-ink-900">{{ (analytics.product_views?.total ?? 0).toLocaleString() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div v-if="user.profile" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Products -->
            <div class="bg-white rounded-2xl border border-line">
                <div class="p-5 border-b border-line flex items-center justify-between">
                    <h3 class="font-semibold text-ink-900">Products ({{ user.profile.products.length }})</h3>
                </div>
                <div v-if="user.profile.products.length === 0" class="p-8 text-center">
                    <Package class="w-10 h-10 text-slate-600 mx-auto mb-2" />
                    <p class="text-ink-500">No products</p>
                </div>
                <div v-else class="divide-y divide-line max-h-[300px] overflow-y-auto">
                    <div
                        v-for="product in user.profile.products"
                        :key="product.id"
                        class="p-4 flex items-center justify-between"
                    >
                        <p class="text-ink-900">{{ product.name }}</p>
                        <p class="text-success-600 font-medium">{{ formatPrice(product.price) }}</p>
                    </div>
                </div>
            </div>

            <!-- Links -->
            <div class="bg-white rounded-2xl border border-line">
                <div class="p-5 border-b border-line flex items-center justify-between">
                    <h3 class="font-semibold text-ink-900">Links ({{ user.profile.links.length }})</h3>
                </div>
                <div v-if="user.profile.links.length === 0" class="p-8 text-center">
                    <LinkIcon class="w-10 h-10 text-slate-600 mx-auto mb-2" />
                    <p class="text-ink-500">No links</p>
                </div>
                <div v-else class="divide-y divide-line max-h-[300px] overflow-y-auto">
                    <div
                        v-for="link in user.profile.links"
                        :key="link.id"
                        class="p-4 flex items-center justify-between"
                    >
                        <p class="text-ink-900">{{ link.title }}</p>
                        <a
                            :href="link.url"
                            target="_blank"
                            class="text-ink-500 hover:text-ink-900"
                        >
                            <ExternalLink class="w-4 h-4" />
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscriptions -->
        <div class="bg-white rounded-2xl border border-line">
            <div class="p-5 border-b border-line">
                <h3 class="font-semibold text-ink-900">Subscriptions</h3>
            </div>
            <div v-if="user.subscriptions.length === 0" class="p-8 text-center">
                <CreditCard class="w-10 h-10 text-slate-600 mx-auto mb-2" />
                <p class="text-ink-500">No subscriptions</p>
            </div>
            <div v-else class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-line">
                            <th class="text-left px-6 py-3 text-sm font-medium text-ink-500">Plan</th>
                            <th class="text-left px-6 py-3 text-sm font-medium text-ink-500">Status</th>
                            <th class="text-left px-6 py-3 text-sm font-medium text-ink-500">Start Date</th>
                            <th class="text-left px-6 py-3 text-sm font-medium text-ink-500">End Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        <tr v-for="sub in user.subscriptions" :key="sub.id">
                            <td class="px-6 py-4 text-ink-900">{{ sub.plan.name }}</td>
                            <td class="px-6 py-4">
                                <span :class="['px-2 py-1 rounded-lg text-xs font-medium', getStatusColor(sub.status)]">
                                    {{ sub.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-ink-500">
                                {{ sub.starts_at ? formatDate(sub.starts_at) : '-' }}
                            </td>
                            <td class="px-6 py-4 text-ink-500">
                                {{ sub.ends_at ? formatDate(sub.ends_at) : '-' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payments -->
        <div class="bg-white rounded-2xl border border-line">
            <div class="p-5 border-b border-line">
                <h3 class="font-semibold text-ink-900">Payment History</h3>
            </div>
            <div v-if="user.payments.length === 0" class="p-8 text-center">
                <CreditCard class="w-10 h-10 text-slate-600 mx-auto mb-2" />
                <p class="text-ink-500">No payments</p>
            </div>
            <div v-else class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-line">
                            <th class="text-left px-6 py-3 text-sm font-medium text-ink-500">Date</th>
                            <th class="text-left px-6 py-3 text-sm font-medium text-ink-500">Plan</th>
                            <th class="text-left px-6 py-3 text-sm font-medium text-ink-500">Method</th>
                            <th class="text-left px-6 py-3 text-sm font-medium text-ink-500">Transaction</th>
                            <th class="text-left px-6 py-3 text-sm font-medium text-ink-500">Amount</th>
                            <th class="text-left px-6 py-3 text-sm font-medium text-ink-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        <tr v-for="payment in user.payments" :key="payment.id">
                            <td class="px-6 py-4 text-ink-500">{{ formatDateTime(payment.created_at) }}</td>
                            <td class="px-6 py-4 text-ink-900">{{ payment.subscription?.plan?.name || '-' }}</td>
                            <td class="px-6 py-4 text-ink-500 uppercase">{{ payment.method }}</td>
                            <td class="px-6 py-4 text-ink-500 font-mono text-sm">{{ payment.transaction_id }}</td>
                            <td class="px-6 py-4 text-ink-900 font-medium">{{ formatPrice(payment.amount) }}</td>
                            <td class="px-6 py-4">
                                <span :class="['px-2 py-1 rounded-lg text-xs font-medium', getStatusColor(payment.status)]">
                                    {{ payment.status }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
