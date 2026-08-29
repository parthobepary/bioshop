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
                class: 'bg-emerald-50 text-emerald-700',
            }
        case 'stock_out':
            return {
                label: 'Out of Stock',
                class: 'bg-rose-50 text-rose-700',
            }
        case 'pre_order':
            return {
                label: 'Pre-order',
                class: 'bg-amber-50 text-amber-700',
            }
        default:
            return {
                label: 'Unknown',
                class: 'bg-slate-100 text-slate-700',
            }
    }
})
</script>

<template>
    <span
        :class="[
            'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
            badgeConfig.class
        ]"
    >
        {{ badgeConfig.label }}
    </span>
</template>
