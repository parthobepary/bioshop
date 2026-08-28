<script setup lang="ts">
import { cn } from '@/lib/utils'

interface Props {
    class?: string
}

const props = defineProps<Props>()
</script>

<template>
    <div
        :class="cn('animate-pulse rounded-md bg-slate-200', props.class)"
    />
</template>
