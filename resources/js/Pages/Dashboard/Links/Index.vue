<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import LinkList from '@/Components/links/LinkList.vue'
import LinkForm from '@/Components/links/LinkForm.vue'
import { Plus, Link as LinkIcon, ExternalLink } from 'lucide-vue-next'

interface Link {
    id: number
    title: string
    url: string
    icon: string
    is_active: boolean
    sort_order: number
}

interface Props {
    links: Link[]
}

const props = defineProps<Props>()

defineOptions({
    layout: DashboardLayout,
})

const page = usePage()

// Modal state
const showForm = ref(false)
const editingLink = ref<Link | null>(null)

// Flash messages
const flash = computed(() => page.props.flash as { success?: string; error?: string })

const openAddModal = () => {
    editingLink.value = null
    showForm.value = true
}

const openEditModal = (link: Link) => {
    editingLink.value = link
    showForm.value = true
}
</script>

<template>
    <Head title="Links" />

    <div class="mx-auto max-w-7xl space-y-8">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-500 text-white shadow-sm">
                    <LinkIcon class="h-5 w-5" />
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Links</h1>
                    <p class="mt-0.5 text-sm text-slate-500">Manage your profile links</p>
                </div>
            </div>
            <Button
                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-500/30"
                @click="openAddModal"
            >
                <Plus class="h-4 w-4" />
                Add Link
            </Button>
        </div>

        <!-- Flash Messages -->
        <div
            v-if="flash?.success"
            class="rounded-2xl border border-emerald-200/70 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 shadow-sm"
        >
            {{ flash.success }}
        </div>
        <div
            v-if="flash?.error"
            class="rounded-2xl border border-rose-200/70 bg-rose-50 p-4 text-sm font-medium text-rose-700 shadow-sm"
        >
            {{ flash.error }}
        </div>

        <!-- Links List -->
        <Card class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <CardHeader class="p-0">
                <CardTitle class="flex items-center gap-2 text-base font-semibold text-slate-900">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                        <LinkIcon class="h-4 w-4" />
                    </span>
                    Your Links
                </CardTitle>
                <CardDescription class="mt-1 text-sm text-slate-500">
                    Drag and drop to reorder. Links will appear on your public profile in this order.
                </CardDescription>
            </CardHeader>
            <CardContent class="mt-6 p-0">
                <!-- Empty State -->
                <div
                    v-if="links.length === 0"
                    class="flex flex-col items-center py-12 text-center"
                >
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50">
                        <LinkIcon class="h-6 w-6 text-slate-300" />
                    </div>
                    <h3 class="mt-4 text-base font-semibold text-slate-900">No links yet</h3>
                    <p class="mt-1 max-w-sm text-sm text-slate-400">
                        Add links to your social media profiles, website, or any other URLs you want to share.
                    </p>
                    <Button
                        class="mt-6 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-500/30"
                        @click="openAddModal"
                    >
                        <Plus class="h-4 w-4" />
                        Add Your First Link
                    </Button>
                </div>

                <!-- Links -->
                <LinkList
                    v-else
                    :links="links"
                    @edit="openEditModal"
                />
            </CardContent>
        </Card>

        <!-- Tips Card -->
        <Card v-if="links.length > 0" class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <CardContent class="p-0">
                <div class="flex items-start gap-4">
                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                        <ExternalLink class="h-5 w-5" />
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Tips for better links</h3>
                        <ul class="mt-2 space-y-1 text-sm text-slate-500">
                            <li>• Use clear, descriptive titles for your links</li>
                            <li>• Place your most important links at the top</li>
                            <li>• Disable links temporarily instead of deleting them</li>
                            <li>• Icons are auto-detected from popular platforms</li>
                        </ul>
                    </div>
                </div>
            </CardContent>
        </Card>
    </div>

    <!-- Link Form Modal -->
    <LinkForm
        v-model:open="showForm"
        :link="editingLink"
    />
</template>
