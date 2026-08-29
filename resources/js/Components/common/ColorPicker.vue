<script setup lang="ts">
import { ref, computed } from 'vue'
import { Check } from 'lucide-vue-next'

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

// Predefined colors (professional and attractive)
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
    { name: 'Slate', value: '#64748b' },
]

const showCustom = ref(false)
const customColor = ref(props.modelValue)

const selectedColor = computed(() => props.modelValue)

const selectColor = (color: string) => {
    emit('update:modelValue', color)
}

const handleCustomColorChange = (event: Event) => {
    const target = event.target as HTMLInputElement
    customColor.value = target.value
    emit('update:modelValue', target.value)
}

const isPresetSelected = (color: string) => {
    return selectedColor.value.toLowerCase() === color.toLowerCase()
}
</script>

<template>
    <div class="space-y-3">
        <label class="block text-sm font-medium text-slate-700">
            {{ label }}
        </label>

        <!-- Preset Colors Grid -->
        <div class="grid grid-cols-8 gap-2.5">
            <button
                v-for="color in presetColors"
                :key="color.value"
                type="button"
                :class="[
                    'h-9 w-9 rounded-full ring-1 ring-slate-200 transition flex items-center justify-center hover:scale-110 focus:outline-none',
                    isPresetSelected(color.value) ? 'ring-2 ring-offset-2 ring-slate-900' : '',
                ]"
                :style="{
                    backgroundColor: color.value,
                }"
                :title="color.name"
                @click="selectColor(color.value)"
            >
                <Check
                    v-if="isPresetSelected(color.value)"
                    :size="16"
                    class="text-white drop-shadow"
                />
            </button>
        </div>

        <!-- Custom Color Toggle -->
        <div class="flex items-center gap-2">
            <button
                type="button"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-700"
                @click="showCustom = !showCustom"
            >
                {{ showCustom ? 'Hide custom color' : 'Use custom color' }}
            </button>
        </div>

        <!-- Custom Color Picker -->
        <div
            v-if="showCustom"
            class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-200"
        >
            <input
                type="color"
                :value="selectedColor"
                class="h-10 w-10 rounded-xl cursor-pointer border-0"
                @input="handleCustomColorChange"
            />
            <input
                type="text"
                :value="selectedColor"
                class="flex-1 px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="#6366f1"
                @input="handleCustomColorChange"
            />
        </div>

        <!-- Preview -->
        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-200">
            <div
                class="h-12 w-12 rounded-xl ring-1 ring-slate-200 shadow-sm"
                :style="{ backgroundColor: selectedColor }"
            />
            <div class="text-sm">
                <p class="font-medium text-slate-900">Preview</p>
                <p class="text-slate-500">{{ selectedColor }}</p>
            </div>
        </div>
    </div>
</template>
