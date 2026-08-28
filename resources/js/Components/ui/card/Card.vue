<script setup lang="ts">
import { cn } from '@/lib/utils'

const props = defineProps<{
    class?: string
}>()
</script>

<template>
    <div
        :class="
            cn(
                'rounded-2xl border border-slate-100 bg-white shadow-card transition-all duration-300 hover:shadow-card-hover',
                props.class
            )
        "
    >
        <slot />
    </div>
</template>
