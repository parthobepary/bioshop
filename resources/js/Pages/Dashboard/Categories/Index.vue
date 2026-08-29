<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, useForm, router, usePage } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Input } from '@/Components/ui/input'
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
    DialogFooter,
} from '@/Components/ui/dialog'
import ConfirmDialog from '@/Components/common/ConfirmDialog.vue'
import draggable from 'vuedraggable'
import { Plus, Folder, GripVertical, Pencil, Trash2, Loader2, Package, X } from 'lucide-vue-next'

interface Category {
    id: number
    name: string
    sort_order: number
    products_count: number
}

interface Props {
    categories: Category[]
}

const props = defineProps<Props>()

defineOptions({
    layout: DashboardLayout,
})

const page = usePage()
const flash = computed(() => page.props.flash as { success?: string; error?: string })

// Local categories for dragging
const localCategories = ref<Category[]>([...props.categories])

// Modal state
const showForm = ref(false)
const editingCategory = ref<Category | null>(null)

const form = useForm({
    name: '',
})

const isEdit = computed(() => !!editingCategory.value)

const openAddModal = () => {
    editingCategory.value = null
    form.reset()
    form.clearErrors()
    showForm.value = true
}

const openEditModal = (category: Category) => {
    editingCategory.value = category
    form.name = category.name
    form.clearErrors()
    showForm.value = true
}

const closeModal = () => {
    showForm.value = false
    editingCategory.value = null
    form.reset()
}

const submit = () => {
    if (isEdit.value && editingCategory.value) {
        form.put(route('categories.update', editingCategory.value.id), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        })
    } else {
        form.post(route('categories.store'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        })
    }
}

// Delete confirmation
const categoryToDelete = ref<{ id: number; name: string } | null>(null)
const deleteBlockedMessage = ref<string | null>(null)

const deleteCategory = (category: Category) => {
    if (category.products_count > 0) {
        deleteBlockedMessage.value = `Can't delete “${category.name}” — it has ${category.products_count} ${category.products_count === 1 ? 'product' : 'products'}. Move or delete those products first.`
        return
    }
    deleteBlockedMessage.value = null
    categoryToDelete.value = { id: category.id, name: category.name }
}

const confirmDelete = () => {
    if (!categoryToDelete.value) return
    const id = categoryToDelete.value.id
    categoryToDelete.value = null
    router.delete(route('categories.destroy', id), { preserveScroll: true })
}

const onDragEnd = () => {
    const updatedCategories = localCategories.value.map((cat, index) => ({
        id: cat.id,
        sort_order: index + 1,
    }))

    router.post(route('categories.reorder'), {
        categories: updatedCategories,
    }, {
        preserveScroll: true,
    })
}

// Sync with props
import { watch } from 'vue'
watch(() => props.categories, (newCategories) => {
    localCategories.value = [...newCategories]
}, { deep: true })
</script>

<template>
    <Head title="Categories" />

    <div class="mx-auto max-w-4xl space-y-8">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Categories</h1>
                <p class="mt-0.5 text-sm text-slate-500">Organize your products into categories</p>
            </div>
            <button
                @click="openAddModal"
                class="inline-flex items-center gap-2 self-start rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-500/30 sm:self-auto"
            >
                <Plus class="h-4 w-4" />
                Add Category
            </button>
        </div>

        <!-- Flash Messages -->
        <div
            v-if="flash?.success"
            class="rounded-2xl border border-emerald-200/70 bg-emerald-50 p-4 text-sm text-emerald-700"
        >
            {{ flash.success }}
        </div>
        <div
            v-if="flash?.error"
            class="rounded-2xl border border-rose-200/70 bg-rose-50 p-4 text-sm text-rose-700"
        >
            {{ flash.error }}
        </div>
        <div
            v-if="deleteBlockedMessage"
            class="flex items-start justify-between gap-3 rounded-2xl border border-amber-200/70 bg-amber-50 p-4 text-sm text-amber-800"
        >
            <span>{{ deleteBlockedMessage }}</span>
            <button
                type="button"
                class="flex-shrink-0 rounded-lg p-1 text-amber-500 transition-colors hover:bg-amber-100 hover:text-amber-700"
                @click="deleteBlockedMessage = null"
            >
                <X class="h-4 w-4" />
            </button>
        </div>

        <!-- Categories List -->
        <div class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 text-white shadow-sm">
                    <Folder class="h-5 w-5" />
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Your Categories</h2>
                    <p class="mt-0.5 text-sm text-slate-500">
                        Drag and drop to reorder. Categories help customers browse your products.
                    </p>
                </div>
            </div>

            <div class="mt-6">
                <!-- Empty State -->
                <div
                    v-if="categories.length === 0"
                    class="flex flex-col items-center py-12 text-center"
                >
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50">
                        <Folder class="h-6 w-6 text-slate-300" />
                    </div>
                    <h3 class="mt-4 text-base font-semibold text-slate-900">No categories yet</h3>
                    <p class="mt-1 max-w-sm text-sm text-slate-400">
                        Create categories to organize your products and make them easier to find.
                    </p>
                    <button
                        @click="openAddModal"
                        class="mt-6 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-500/30"
                    >
                        <Plus class="h-4 w-4" />
                        Add Your First Category
                    </button>
                </div>

                <!-- Categories -->
                <draggable
                    v-else
                    v-model="localCategories"
                    item-key="id"
                    handle=".cursor-grab"
                    ghost-class="opacity-50"
                    animation="200"
                    class="space-y-3"
                    @end="onDragEnd"
                >
                    <template #item="{ element }">
                        <div
                            class="group flex items-center gap-4 rounded-2xl border border-slate-200/70 bg-white p-4 shadow-sm transition-all hover:shadow-md"
                        >
                            <!-- Drag Handle -->
                            <div class="cursor-grab text-slate-300 transition-colors active:cursor-grabbing hover:text-slate-500">
                                <GripVertical class="h-5 w-5" />
                            </div>

                            <!-- Icon -->
                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 text-white shadow-sm">
                                <Folder class="h-5 w-5" />
                            </div>

                            <!-- Content -->
                            <div class="min-w-0 flex-1">
                                <h3 class="font-semibold text-slate-900">
                                    {{ element.name }}
                                </h3>
                                <p class="mt-1 flex items-center gap-1.5">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">
                                        <Package class="h-3.5 w-3.5" />
                                        {{ element.products_count }} {{ element.products_count === 1 ? 'product' : 'products' }}
                                    </span>
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                                <button
                                    @click="openEditModal(element)"
                                    title="Edit category"
                                    class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100"
                                >
                                    <Pencil class="h-4 w-4" />
                                </button>
                                <button
                                    @click="deleteCategory(element)"
                                    title="Delete category"
                                    class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-rose-50 hover:text-rose-600"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                    </template>
                </draggable>
            </div>
        </div>
    </div>

    <!-- Category Form Modal -->
    <Dialog :open="showForm" @update:open="showForm = $event">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>{{ isEdit ? 'Edit Category' : 'Add New Category' }}</DialogTitle>
                <DialogDescription>
                    {{ isEdit ? 'Update the category name.' : 'Create a new category for your products.' }}
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-5">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-slate-700">
                        Category Name <span class="text-rose-500">*</span>
                    </label>
                    <Input
                        v-model="form.name"
                        type="text"
                        placeholder="e.g., Electronics, Clothing, Food"
                        maxlength="100"
                        autofocus
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-900 placeholder:text-slate-400 transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10"
                    />
                    <p v-if="form.errors.name" class="text-sm text-rose-600">
                        {{ form.errors.name }}
                    </p>
                </div>

                <DialogFooter class="gap-2 pt-2">
                    <button
                        type="button"
                        @click="closeModal"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-500/30 disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0 disabled:hover:shadow-sm"
                    >
                        <Loader2 v-if="form.processing" class="h-4 w-4 animate-spin" />
                        {{ isEdit ? 'Update' : 'Create' }}
                    </button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>

    <!-- Delete confirmation -->
    <ConfirmDialog
        :open="categoryToDelete !== null"
        title="Delete category?"
        :message="categoryToDelete
            ? `“${categoryToDelete.name}” will be permanently removed. This can't be undone.`
            : ''"
        confirm-label="Delete category"
        variant="danger"
        @confirm="confirmDelete"
        @update:open="(v) => { if (!v) categoryToDelete = null }"
    />
</template>
