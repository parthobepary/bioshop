<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import {
    Home,
    ArrowLeft,
    RefreshCw,
    Search,
    ShieldX,
    ServerCrash,
    FileQuestion,
} from 'lucide-vue-next'

interface Props {
    status: number
}

const props = defineProps<Props>()

const errorConfig: Record<number, {
    title: string
    description: string
    icon: any
    iconBg: string
    iconColor: string
}> = {
    403: {
        title: 'Access Denied',
        description: "You don't have permission to access this page. Please contact support if you believe this is an error.",
        icon: ShieldX,
        iconBg: 'bg-red-100',
        iconColor: 'text-red-600',
    },
    404: {
        title: 'Page Not Found',
        description: "The page you're looking for doesn't exist or has been moved. Check the URL or navigate back home.",
        icon: FileQuestion,
        iconBg: 'bg-amber-100',
        iconColor: 'text-amber-600',
    },
    500: {
        title: 'Server Error',
        description: "Something went wrong on our end. We're working to fix it. Please try again later.",
        icon: ServerCrash,
        iconBg: 'bg-purple-100',
        iconColor: 'text-purple-600',
    },
    503: {
        title: 'Service Unavailable',
        description: "We're currently performing maintenance. Please check back in a few minutes.",
        icon: ServerCrash,
        iconBg: 'bg-blue-100',
        iconColor: 'text-blue-600',
    },
}

const config = errorConfig[props.status] || errorConfig[404]

const goBack = () => {
    if (typeof window !== 'undefined') {
        window.history.back()
    }
}

const reload = () => {
    if (typeof window !== 'undefined') {
        window.location.reload()
    }
}
</script>

<template>
    <Head :title="`${status} - ${config.title}`" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center p-4">
        <div class="max-w-md w-full text-center">
            <!-- Error Icon -->
            <div class="mb-8">
                <div
                    :class="[
                        'w-24 h-24 rounded-full flex items-center justify-center mx-auto',
                        config.iconBg
                    ]"
                >
                    <component
                        :is="config.icon"
                        :class="['w-12 h-12', config.iconColor]"
                    />
                </div>
            </div>

            <!-- Error Code -->
            <div class="mb-4">
                <span class="text-8xl font-bold bg-gradient-to-r from-primary-600 to-primary-400 bg-clip-text text-transparent">
                    {{ status }}
                </span>
            </div>

            <!-- Error Title -->
            <h1 class="text-2xl font-bold text-slate-900 mb-3">
                {{ config.title }}
            </h1>

            <!-- Error Description -->
            <p class="text-slate-600 mb-8 leading-relaxed">
                {{ config.description }}
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <Button as-child>
                    <Link href="/">
                        <Home class="w-4 h-4 mr-2" />
                        Go Home
                    </Link>
                </Button>

                <Button variant="outline" @click="goBack">
                    <ArrowLeft class="w-4 h-4 mr-2" />
                    Go Back
                </Button>

                <Button
                    v-if="status === 500 || status === 503"
                    variant="outline"
                    @click="reload"
                >
                    <RefreshCw class="w-4 h-4 mr-2" />
                    Retry
                </Button>
            </div>

            <!-- Help Section -->
            <div class="mt-12 pt-8 border-t border-slate-200">
                <p class="text-sm text-slate-500 mb-4">
                    Need help? Contact our support team
                </p>
                <div class="flex justify-center gap-4">
                    <a
                        href="mailto:support@bioshop.com"
                        class="text-sm text-primary-600 hover:text-primary-700 font-medium"
                    >
                        support@bioshop.com
                    </a>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute inset-0 -z-10 overflow-hidden pointer-events-none">
                <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-primary-200/30 rounded-full blur-3xl"></div>
                <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-orange-200/30 rounded-full blur-3xl"></div>
            </div>
        </div>
    </div>
</template>
