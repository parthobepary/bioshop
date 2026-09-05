<script setup lang="ts">
import { computed, ref } from 'vue'
import { Check } from 'lucide-vue-next'
import { readableOn } from '@/lib/color'

interface Props {
    modelValue: string
    label?: string
}

const props = withDefaults(defineProps<Props>(), {
    label: 'Theme Color',
})

const emit = defineEmits<{
    'update:modelValue': [value: string]
}>()

const presetColors = [
    { name: 'Indigo', value: '#6366f1' },
    { name: 'Purple', value: '#8b5cf6' },
    { name: 'Pink', value: '#ec4899' },
    { name: 'Rose', value: '#f43f5e' },
    { name: 'Red', value: '#ef4444' },
    { name: 'Orange', value: '#f97316' },
    { name: 'Amber', value: '#f59e0b' },
    { name: 'Yellow', value: '#eab308' },
    { name: 'Lime', value: '#84cc16' },
    { name: 'Green', value: '#22c55e' },
    { name: 'Emerald', value: '#10b981' },
    { name: 'Teal', value: '#14b8a6' },
    { name: 'Cyan', value: '#06b6d4' },
    { name: 'Sky', value: '#0ea5e9' },
    { name: 'Blue', value: '#3b82f6' },
    { name: 'Ink', value: '#1a1a19' },
]

const showCustom = ref(false)

const selectedColor = computed(() => props.modelValue)
const checkColor = computed(() => readableOn(props.modelValue))

const selectColor = (color: string) => emit('update:modelValue', color)

const handleCustomColorChange = (event: Event) => {
    emit('update:modelValue', (event.target as HTMLInputElement).value)
}

const isPresetSelected = (color: string) =>
    selectedColor.value?.toLowerCase() === color.toLowerCase()
</script>

<template>
    <div class="space-y-4">
        <label v-if="label" class="block text-[13px] font-medium text-ink-700">
            {{ label }}
        </label>

        <!-- Presets: the selected swatch wears its own colour as the ring -->
        <div class="grid grid-cols-8 gap-2.5">
            <button
                v-for="color in presetColors"
                :key="color.value"
                type="button"
                class="flex h-9 w-9 items-center justify-center rounded-full transition-transform hover:scale-110 focus:outline-none"
                :style="{
                    backgroundColor: color.value,
                    boxShadow: isPresetSelected(color.value)
                        ? `0 0 0 2px #fff, 0 0 0 4px ${color.value}`
                        : '0 0 0 1px rgba(26,26,25,0.12)',
                }"
                :title="color.name"
                :aria-label="color.name"
                :aria-pressed="isPresetSelected(color.value)"
                @click="selectColor(color.value)"
            >
                <Check
                    v-if="isPresetSelected(color.value)"
                    :size="15"
                    :style="{ color: checkColor }"
                />
            </button>
        </div>

        <button
            type="button"
            class="text-[13px] font-medium underline-offset-4 hover:underline"
            :style="{ color: selectedColor }"
            @click="showCustom = !showCustom"
        >
            {{ showCustom ? 'Hide custom colour' : 'Use a custom colour' }}
        </button>

        <div
            v-if="showCustom"
            class="flex items-center gap-2.5 rounded-xl border border-line bg-paper-subtle p-3"
        >
            <input
                type="color"
                :value="selectedColor"
                class="h-9 w-9 cursor-pointer rounded-lg border-0 bg-transparent p-0"
                aria-label="Pick a custom colour"
                @input="handleCustomColorChange"
            />
            <input
                type="text"
                :value="selectedColor"
                class="flex-1 rounded-lg border border-line bg-white px-3 py-2 font-mono text-[13px] text-ink-900 focus:outline-none"
                :style="{ borderColor: selectedColor }"
                placeholder="#6366f1"
                @input="handleCustomColorChange"
            />
        </div>
    </div>
</template>
