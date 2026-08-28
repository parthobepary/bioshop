<script setup lang="ts">
import { computed } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
    defaultValue?: string | number
    modelValue?: string | number
    class?: string
}>()

const emits = defineEmits<{
    (e: 'update:modelValue', payload: string | number): void
}>()

const modelValue = computed({
    get() {
        return props.modelValue
    },
    set(val) {
        emits('update:modelValue', val as string | number)
    },
})
</script>

<template>
    <input
        v-model="modelValue"
        :class="
            cn(
                'flex h-11 w-full rounded-xl border-2 border-transparent bg-slate-50 px-4 py-3 text-sm text-slate-900 ring-offset-background transition-all duration-200 file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-slate-400 focus:bg-white focus:border-primary-500 focus:outline-none focus:ring-4 focus:ring-primary-500/10 disabled:cursor-not-allowed disabled:opacity-50',
                props.class
            )
        "
    />
</template>
