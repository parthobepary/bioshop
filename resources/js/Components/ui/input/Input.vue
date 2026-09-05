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
                'flex h-10 w-full rounded-lg border border-line bg-white px-3.5 py-2 text-sm text-ink-900 ring-offset-background transition-colors duration-150 file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-ink-400 focus:border-accent-600 focus:outline-none focus:ring-2 focus:ring-accent-600/15 disabled:cursor-not-allowed disabled:opacity-50',
                props.class
            )
        "
    />
</template>
