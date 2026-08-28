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
        <label class="block text-sm font-medium text-gray-700">
            {{ label }}
        </label>

        <!-- Preset Colors Grid -->
        <div class="grid grid-cols-8 gap-2">
            <button
                v-for="color in presetColors"
                :key="color.value"
                type="button"
                class="w-8 h-8 rounded-full flex items-center justify-center transition-transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-offset-2"
                :style="{
                    backgroundColor: color.value,
                    '--tw-ring-color': color.value,
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
                class="text-sm text-primary-600 hover:text-primary-700 underline"
                @click="showCustom = !showCustom"
            >
                {{ showCustom ? 'Hide custom color' : 'Use custom color' }}
            </button>
        </div>

        <!-- Custom Color Picker -->
        <div
            v-if="showCustom"
            class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg"
        >
            <input
                type="color"
                :value="selectedColor"
                class="w-10 h-10 rounded-lg cursor-pointer border-0"
                @input="handleCustomColorChange"
            />
            <input
                type="text"
                :value="selectedColor"
                class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                placeholder="#6366f1"
                @input="handleCustomColorChange"
            />
        </div>

        <!-- Preview -->
        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
            <div
                class="w-12 h-12 rounded-xl shadow-sm"
                :style="{ backgroundColor: selectedColor }"
            />
            <div class="text-sm">
                <p class="font-medium text-gray-900">Preview</p>
                <p class="text-gray-500">{{ selectedColor }}</p>
            </div>
        </div>
    </div>
</template>
