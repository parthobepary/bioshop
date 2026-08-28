<script setup lang="ts">
import { computed } from 'vue'

interface Props {
    status: 'available' | 'stock_out' | 'pre_order'
}

const props = defineProps<Props>()

const badgeConfig = computed(() => {
    switch (props.status) {
        case 'available':
            return {
                label: 'In Stock',
                class: 'bg-green-100 text-green-700',
            }
        case 'stock_out':
            return {
                label: 'Out of Stock',
                class: 'bg-red-100 text-red-700',
            }
        case 'pre_order':
            return {
                label: 'Pre-order',
                class: 'bg-amber-100 text-amber-700',
            }
        default:
            return {
                label: 'Unknown',
                class: 'bg-gray-100 text-gray-700',
            }
    }
})
</script>

<template>
    <span
        :class="[
            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
            badgeConfig.class
        ]"
    >
        {{ badgeConfig.label }}
    </span>
</template>
