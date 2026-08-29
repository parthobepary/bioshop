<script setup lang="ts">
import { Skeleton } from '@/Components/ui/skeleton'

interface Props {
    rows?: number
    columns?: number
}

withDefaults(defineProps<Props>(), {
    rows: 5,
    columns: 4,
})
</script>

<template>
    <div class="bg-white rounded-2xl border border-slate-200/70 overflow-hidden shadow-sm">
        <!-- Header -->
        <div class="border-b border-slate-200 bg-slate-50/60 px-6 py-4">
            <div class="flex gap-4">
                <Skeleton
                    v-for="i in columns"
                    :key="`header-${i}`"
                    :class="'h-4 rounded bg-slate-200 ' + (i === 1 ? 'w-1/4' : 'w-1/6')"
                />
            </div>
        </div>

        <!-- Rows -->
        <div class="divide-y divide-slate-100">
            <div
                v-for="row in rows"
                :key="`row-${row}`"
                class="px-6 py-4"
            >
                <div class="flex items-center gap-4">
                    <Skeleton
                        v-for="col in columns"
                        :key="`row-${row}-col-${col}`"
                        :class="'h-4 rounded bg-slate-100 ' + (col === 1 ? 'w-1/4' : 'w-1/6')"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
