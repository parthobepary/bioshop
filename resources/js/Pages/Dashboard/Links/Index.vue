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

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Links</h1>
                <p class="text-gray-500 mt-1">Manage your profile links</p>
            </div>
            <Button @click="openAddModal">
                <Plus class="w-4 h-4 mr-2" />
                Add Link
            </Button>
        </div>

        <!-- Flash Messages -->
        <div
            v-if="flash?.success"
            class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-700"
        >
            {{ flash.success }}
        </div>
        <div
            v-if="flash?.error"
            class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700"
        >
            {{ flash.error }}
        </div>

        <!-- Links List -->
        <Card>
            <CardHeader>
                <CardTitle class="flex items-center gap-2">
                    <LinkIcon class="w-5 h-5 text-primary-600" />
                    Your Links
                </CardTitle>
                <CardDescription>
                    Drag and drop to reorder. Links will appear on your public profile in this order.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <!-- Empty State -->
                <div
                    v-if="links.length === 0"
                    class="text-center py-12"
                >
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <LinkIcon class="w-8 h-8 text-gray-400" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No links yet</h3>
                    <p class="text-gray-500 mb-6 max-w-sm mx-auto">
                        Add links to your social media profiles, website, or any other URLs you want to share.
                    </p>
                    <Button @click="openAddModal">
                        <Plus class="w-4 h-4 mr-2" />
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
        <Card v-if="links.length > 0">
            <CardContent class="p-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <ExternalLink class="w-5 h-5 text-primary-600" />
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Tips for better links</h3>
                        <ul class="mt-2 text-sm text-gray-500 space-y-1">
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
