<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'
import { Line } from 'vue-chartjs'
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    Filler,
} from 'chart.js'

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    Filler
)

interface Props {
    labels: string[]
    data: number[]
    title?: string
    color?: string
    loading?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    title: 'Page Views',
    color: '',
    loading: false,
})

const root = ref<HTMLElement | null>(null)

/**
 * Chart.js needs a concrete colour, so resolve the inherited --shop variable
 * once the chart is in the DOM. An explicit `color` prop still wins.
 */
const lineColor = ref(props.color || '#1a1a19')

onMounted(() => {
    if (props.color || !root.value) return

    const inherited = getComputedStyle(root.value).getPropertyValue('--shop').trim()
    if (inherited) lineColor.value = inherited
})

const chartData = computed(() => ({
    labels: props.labels,
    datasets: [
        {
            label: props.title,
            data: props.data,
            fill: true,
            borderColor: lineColor.value,
            backgroundColor: `${lineColor.value}1f`,
            tension: 0.4,
            pointRadius: 0,
            pointHoverRadius: 6,
            pointHoverBackgroundColor: lineColor.value,
            pointHoverBorderColor: '#fff',
            pointHoverBorderWidth: 2,
        },
    ],
}))

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    interaction: {
        intersect: false,
        mode: 'index' as const,
    },
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            backgroundColor: '#1e293b',
            titleColor: '#fff',
            bodyColor: '#fff',
            padding: 12,
            cornerRadius: 8,
            displayColors: false,
            callbacks: {
                title: (items: any) => items[0]?.label || '',
                label: (item: any) => `${item.formattedValue} views`,
            },
        },
    },
    scales: {
        x: {
            grid: {
                display: false,
            },
            ticks: {
                color: '#94a3b8',
                font: {
                    size: 11,
                },
                maxRotation: 0,
                autoSkip: true,
                maxTicksLimit: 7,
            },
            border: {
                display: false,
            },
        },
        y: {
            beginAtZero: true,
            grid: {
                color: '#f1f5f9',
            },
            ticks: {
                color: '#94a3b8',
                font: {
                    size: 11,
                },
                precision: 0,
            },
            border: {
                display: false,
            },
        },
    },
}

const totalViews = computed(() => {
    return props.data.reduce((sum, val) => sum + val, 0)
})

const averageViews = computed(() => {
    if (props.data.length === 0) return 0
    return Math.round(totalViews.value / props.data.length)
})
</script>

<template>
    <div ref="root" class="h-full rounded-2xl border border-line bg-white p-6 shadow-sm">
        <div class="mb-5 flex items-start justify-between gap-4">
            <div>
                <h3 class="text-base font-semibold text-slate-900">{{ title }}</h3>
                <p class="mt-1 flex items-center gap-2 text-sm text-slate-500">
                    <span class="font-semibold text-slate-700">{{ totalViews.toLocaleString() }}</span>
                    total
                    <span class="text-slate-300">&middot;</span>
                    <span class="font-semibold text-slate-700">{{ averageViews.toLocaleString() }}</span>
                    avg/day
                </p>
            </div>
            <slot name="actions" />
        </div>

        <div v-if="loading" class="h-[240px] animate-pulse rounded-xl bg-slate-100"></div>
        <div v-else class="h-[240px]">
            <Line :data="chartData" :options="chartOptions" />
        </div>
    </div>
</template>
