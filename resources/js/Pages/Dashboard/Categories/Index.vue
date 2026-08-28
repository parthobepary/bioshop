<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, useForm, router, usePage } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
    DialogFooter,
} from '@/Components/ui/dialog'
import draggable from 'vuedraggable'
import { Plus, Folder, GripVertical, Pencil, Trash2, Loader2, Package } from 'lucide-vue-next'

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

const deleteCategory = (category: Category) => {
    if (category.products_count > 0) {
        alert(`Cannot delete "${category.name}" because it has ${category.products_count} products. Move or delete products first.`)
        return
    }

    if (confirm(`Are you sure you want to delete "${category.name}"?`)) {
        router.delete(route('categories.destroy', category.id), {
            preserveScroll: true,
        })
    }
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

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
                <p class="text-gray-500 mt-1">Organize your products into categories</p>
            </div>
            <Button @click="openAddModal">
                <Plus class="w-4 h-4 mr-2" />
                Add Category
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

        <!-- Categories List -->
        <Card>
            <CardHeader>
                <CardTitle class="flex items-center gap-2">
                    <Folder class="w-5 h-5 text-primary-600" />
                    Your Categories
                </CardTitle>
                <CardDescription>
                    Drag and drop to reorder. Categories help customers browse your products.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <!-- Empty State -->
                <div
                    v-if="categories.length === 0"
                    class="text-center py-12"
                >
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <Folder class="w-8 h-8 text-gray-400" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No categories yet</h3>
                    <p class="text-gray-500 mb-6 max-w-sm mx-auto">
                        Create categories to organize your products and make them easier to find.
                    </p>
                    <Button @click="openAddModal">
                        <Plus class="w-4 h-4 mr-2" />
                        Add Your First Category
                    </Button>
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
                            class="group flex items-center gap-4 p-4 bg-white rounded-xl border border-gray-200 hover:border-primary-300 hover:shadow-md transition-all duration-200"
                        >
                            <!-- Drag Handle -->
                            <div class="cursor-grab active:cursor-grabbing text-gray-400 hover:text-gray-600">
                                <GripVertical class="w-5 h-5" />
                            </div>

                            <!-- Icon -->
                            <div class="w-10 h-10 rounded-xl bg-primary-100 text-primary-600 flex items-center justify-center flex-shrink-0">
                                <Folder class="w-5 h-5" />
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <h3 class="font-medium text-gray-900">
                                    {{ element.name }}
                                </h3>
                                <p class="text-sm text-gray-500 flex items-center gap-1">
                                    <Package class="w-3.5 h-3.5" />
                                    {{ element.products_count }} {{ element.products_count === 1 ? 'product' : 'products' }}
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <Button
                                    variant="ghost"
                                    size="sm"
                                    @click="openEditModal(element)"
                                    title="Edit category"
                                >
                                    <Pencil class="w-4 h-4 text-gray-400" />
                                </Button>
                                <Button
                                    variant="ghost"
                                    size="sm"
                                    @click="deleteCategory(element)"
                                    title="Delete category"
                                >
                                    <Trash2 class="w-4 h-4 text-red-400" />
                                </Button>
                            </div>
                        </div>
                    </template>
                </draggable>
            </CardContent>
        </Card>
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

            <form @submit.prevent="submit" class="space-y-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <Input
                        v-model="form.name"
                        type="text"
                        placeholder="e.g., Electronics, Clothing, Food"
                        maxlength="100"
                        autofocus
                    />
                    <p v-if="form.errors.name" class="text-sm text-red-600">
                        {{ form.errors.name }}
                    </p>
                </div>

                <DialogFooter class="pt-4">
                    <Button
                        type="button"
                        variant="outline"
                        @click="closeModal"
                    >
                        Cancel
                    </Button>
                    <Button
                        type="submit"
                        :disabled="form.processing"
                    >
                        <Loader2 v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                        {{ isEdit ? 'Update' : 'Create' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
