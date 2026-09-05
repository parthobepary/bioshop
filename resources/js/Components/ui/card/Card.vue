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
                'rounded-xl border border-line bg-white transition-colors duration-200',
                props.class
            )
        "
    >
        <slot />
    </div>
</template>
