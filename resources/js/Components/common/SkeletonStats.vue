<script setup lang="ts">
import { Skeleton } from '@/Components/ui/skeleton'

interface Props {
    count?: number
}

withDefaults(defineProps<Props>(), {
    count: 4,
})
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div
            v-for="i in count"
            :key="i"
            class="bg-white rounded-2xl border border-line p-5 shadow-sm"
        >
            <div class="flex items-center justify-between gap-3">
                <div class="space-y-3 flex-1">
                    <Skeleton class="h-4 w-24 rounded bg-slate-100" />
                    <Skeleton class="h-8 w-16 rounded bg-slate-200" />
                    <Skeleton class="h-3 w-20 rounded bg-slate-100" />
                </div>
                <Skeleton class="h-12 w-12 rounded-xl bg-slate-200" />
            </div>
        </div>
    </div>
</template>
