<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <AuthLayout
        heading="Reset your password"
        description="We'll email you a link to choose a new one."
        panel-title="Locked out? It happens."
        panel-text="Enter the email you signed up with and we'll send a reset link straight away. The link expires in 60 minutes."
        :points="['Your shop stays online while you reset', 'Nothing changes until you set a new password']"
    >
        <Head title="Forgot Password" />

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

            <button type="submit" class="btn-brand w-full" :disabled="form.processing">
                {{ form.processing ? 'Sending…' : 'Email me a reset link' }}
            </button>
        </form>

        <p class="mt-6 text-center text-[13px] text-ink-500">
            Remembered it?
            <Link :href="route('login')" class="link-accent">Back to sign in</Link>
        </p>
    </AuthLayout>
</template>
