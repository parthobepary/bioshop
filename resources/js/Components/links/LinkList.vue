<script setup lang="ts">
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import draggable from 'vuedraggable'
import LinkCard from './LinkCard.vue'

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

const emit = defineEmits<{
    edit: [link: Link]
}>()

const localLinks = ref<Link[]>([...props.links])

// Sync with props
watch(() => props.links, (newLinks) => {
    localLinks.value = [...newLinks]
}, { deep: true })

const onDragEnd = () => {
    // Update sort order based on new positions
    const updatedLinks = localLinks.value.map((link, index) => ({
        id: link.id,
        sort_order: index + 1,
    }))

    router.post(route('links.reorder'), {
        links: updatedLinks,
    }, {
        preserveScroll: true,
    })
}

const handleEdit = (link: Link) => {
    emit('edit', link)
}
</script>

<template>
    <draggable
        v-model="localLinks"
        item-key="id"
        handle=".cursor-grab"
        ghost-class="opacity-50"
        animation="200"
        class="space-y-3"
        @end="onDragEnd"
    >
        <template #item="{ element }">
            <LinkCard
                :link="element"
                @edit="handleEdit"
            />
        </template>
    </draggable>
</template>
