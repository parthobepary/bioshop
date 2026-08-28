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
            class="bg-white rounded-xl border border-slate-200 p-6"
        >
            <div class="flex items-center justify-between">
                <div class="space-y-3 flex-1">
                    <Skeleton class="h-4 w-24" />
                    <Skeleton class="h-8 w-16" />
                    <Skeleton class="h-3 w-20" />
                </div>
                <Skeleton class="h-12 w-12 rounded-lg" />
            </div>
        </div>
    </div>
</template>
