<script setup lang="ts">
import { Skeleton } from '@/Components/ui/skeleton'

interface Props {
    showImage?: boolean
    lines?: number
}

withDefaults(defineProps<Props>(), {
    showImage: true,
    lines: 3,
})
</script>

<template>
    <div class="bg-white rounded-2xl border border-slate-200/70 p-5 space-y-4 shadow-sm">
        <!-- Image skeleton -->
        <Skeleton v-if="showImage" class="h-40 w-full rounded-xl bg-slate-200" />

        <!-- Content skeleton -->
        <div class="space-y-3">
            <Skeleton class="h-5 w-3/4 rounded bg-slate-200" />
            <template v-for="i in lines" :key="i">
                <Skeleton
                    :class="'h-4 rounded bg-slate-100 ' + (i === lines ? 'w-1/2' : 'w-full')"
                />
            </template>
        </div>

        <!-- Action skeleton -->
        <div class="flex gap-2 pt-2">
            <Skeleton class="h-9 w-20 rounded-xl bg-slate-200" />
            <Skeleton class="h-9 w-20 rounded-xl bg-slate-200" />
        </div>
    </div>
</template>
