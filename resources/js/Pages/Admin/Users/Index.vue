<script setup lang="ts">
import { ref, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Input } from '@/Components/ui/input'
import { Button } from '@/Components/ui/button'
import { useDebounceFn } from '@vueuse/core'
import {
    Search,
    User,
    Shield,
    Ban,
    Eye,
    ChevronLeft,
    ChevronRight,
} from 'lucide-vue-next'

interface Profile {
    id: number
    username: string
}

interface Plan {
    id: number
    name: string
}

interface Subscription {
    id: number
    status: string
    plan: Plan
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
    completed_payments_count: number
}

interface PaginatedUsers {
    data: UserData[]
    current_page: number
    last_page: number
    per_page: number
    total: number
    links: { url: string | null; label: string; active: boolean }[]
}

interface Filters {
    search: string
    status: string
    role: string
}

interface Props {
    users: PaginatedUsers
    filters: Filters
}

const props = defineProps<Props>()

defineOptions({
    layout: AdminLayout,
})

const search = ref(props.filters.search)
const status = ref(props.filters.status)
const role = ref(props.filters.role)

const applyFilters = useDebounceFn(() => {
    router.get('/admin/users', {
        search: search.value || undefined,
        status: status.value || undefined,
        role: role.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
    })
}, 300)

watch([search, status, role], applyFilters)

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    })
}

const getActivePlan = (subscriptions: Subscription[]) => {
    const active = subscriptions.find(s => s.status === 'active')
    return active?.plan?.name || 'Free'
}
</script>

<template>
    <Head title="Users - Admin" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-ink-900">Users</h1>
                <p class="text-ink-500 mt-1">Manage platform users</p>
            </div>
            <p class="text-ink-500">{{ users.total }} total users</p>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap gap-4">
            <div class="relative flex-1 min-w-[200px]">
                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-ink-500" />
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search users..."
                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-line rounded-xl text-ink-900 placeholder-ink-400 focus:border-ink-900 focus:ring-1 focus:ring-accent-600/30"
                />
            </div>

            <select
                v-model="status"
                class="py-2.5 pl-4 pr-9 bg-white border border-line rounded-xl text-ink-900 focus:border-ink-900 focus:ring-1 focus:ring-accent-600/30"
            >
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="banned">Banned</option>
            </select>

            <select
                v-model="role"
                class="py-2.5 pl-4 pr-9 bg-white border border-line rounded-xl text-ink-900 focus:border-ink-900 focus:ring-1 focus:ring-accent-600/30"
            >
                <option value="">All Roles</option>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-2xl border border-line overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-line">
                            <th class="text-left px-6 py-4 text-sm font-medium text-ink-500">User</th>
                            <th class="text-left px-6 py-4 text-sm font-medium text-ink-500">Username</th>
                            <th class="text-left px-6 py-4 text-sm font-medium text-ink-500">Plan</th>
                            <th class="text-left px-6 py-4 text-sm font-medium text-ink-500">Role</th>
                            <th class="text-left px-6 py-4 text-sm font-medium text-ink-500">Status</th>
                            <th class="text-left px-6 py-4 text-sm font-medium text-ink-500">Joined</th>
                            <th class="text-right px-6 py-4 text-sm font-medium text-ink-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        <tr
                            v-for="user in users.data"
                            :key="user.id"
                            class="hover:bg-paper-subtle transition-colors"
                        >
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-paper-deep rounded-full flex items-center justify-center text-ink-900 font-medium">
                                        {{ user.name.charAt(0) }}
                                    </div>
                                    <div>
                                        <p class="text-ink-900 font-medium">{{ user.name }}</p>
                                        <p class="text-sm text-ink-500">{{ user.email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span v-if="user.profile" class="text-ink-600">
                                    @{{ user.profile.username }}
                                </span>
                                <span v-else class="text-slate-500">No profile</span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="[
                                        'px-2 py-1 rounded-lg text-xs font-medium',
                                        getActivePlan(user.subscriptions) === 'Free'
                                            ? 'bg-paper-deep text-ink-600'
                                            : 'bg-purple-500/20 text-purple-400'
                                    ]"
                                >
                                    {{ getActivePlan(user.subscriptions) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    v-if="user.role === 'admin'"
                                    class="flex items-center gap-1 text-accent-600"
                                >
                                    <Shield class="w-4 h-4" />
                                    Admin
                                </span>
                                <span v-else class="text-ink-500">User</span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="[
                                        'px-2 py-1 rounded-lg text-xs font-medium',
                                        user.is_active
                                            ? 'bg-success-500/20 text-success-600'
                                            : 'bg-error-500/20 text-error-600'
                                    ]"
                                >
                                    {{ user.is_active ? 'Active' : 'Banned' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-ink-500">
                                {{ formatDate(user.created_at) }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <Link
                                    :href="`/admin/users/${user.id}`"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-paper-deep hover:bg-ink-200 text-ink-800 rounded-lg text-sm transition-colors"
                                >
                                    <Eye class="w-4 h-4" />
                                    View
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="users.last_page > 1" class="px-6 py-4 border-t border-line flex items-center justify-between">
                <p class="text-sm text-ink-500">
                    Showing {{ (users.current_page - 1) * users.per_page + 1 }} to
                    {{ Math.min(users.current_page * users.per_page, users.total) }} of
                    {{ users.total }} users
                </p>
                <div class="flex items-center gap-2">
                    <template v-for="link in users.links" :key="link.label">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            :class="[
                                'px-3 py-1.5 rounded-lg text-sm transition-colors',
                                link.active
                                    ? 'bg-ink-900 text-ink-900'
                                    : 'bg-paper-deep text-ink-600 hover:bg-ink-200'
                            ]"
                            v-html="link.label"
                        />
                        <span
                            v-else
                            class="px-3 py-1.5 text-slate-500 text-sm"
                            v-html="link.label"
                        />
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
