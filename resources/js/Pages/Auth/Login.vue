<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const points = [
    'Your shop page, links and products in one place',
    'bKash, Nagad, Rocket and bank payments',
    'WhatsApp orders with the product already filled in',
];

const quote = {
    text: 'I went from DM chaos to organised orders in a week.',
    name: 'Sarah Rahman',
    role: 'Fashion boutique owner',
};

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <AuthLayout
        heading="Welcome back"
        description="Sign in to manage your shop."
        panel-title="Everything your shop needs, behind one link."
        panel-text="Pick up where you left off — products, payments, orders and analytics are all waiting in your dashboard."
        :points="points"
        :quote="quote"
    >
        <Head title="Log in" />

        <p
            v-if="status"
            class="mb-5 rounded-lg border border-success-100 bg-success-50 px-4 py-3 text-[13px] text-success-600"
        >
            {{ status }}
        </p>

        <form class="space-y-4" @submit.prevent="submit">
            <div>
                <label for="email" class="field-label">Email</label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    autofocus
                    autocomplete="username"
                    class="field"
                    placeholder="you@example.com"
                />
                <p v-if="form.errors.email" class="field-error">{{ form.errors.email }}</p>
            </div>

            <div>
                <div class="flex items-baseline justify-between">
                    <label for="password" class="field-label">Password</label>
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="mb-1.5 text-[12px] text-ink-500 transition-colors hover:text-ink-900"
                    >
                        Forgot?
                    </Link>
                </div>
                <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    required
                    autocomplete="current-password"
                    class="field"
                    placeholder="••••••••"
                />
                <p v-if="form.errors.password" class="field-error">{{ form.errors.password }}</p>
            </div>

            <label class="flex cursor-pointer items-center gap-2">
                <input
                    v-model="form.remember"
                    type="checkbox"
                    class="h-4 w-4 rounded border-line text-ink-900 focus:ring-accent-600/30"
                />
                <span class="text-[13px] text-ink-600">Keep me signed in</span>
            </label>

            <button type="submit" class="btn-brand w-full" :disabled="form.processing">
                {{ form.processing ? 'Signing in…' : 'Sign in' }}
            </button>
        </form>

        <p class="mt-6 text-center text-[13px] text-ink-500">
            New to BioShop?
            <Link :href="route('register')" class="link-accent">Create a free shop</Link>
        </p>
    </AuthLayout>
</template>
