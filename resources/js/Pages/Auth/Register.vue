<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const steps = [
    { title: 'Create your account', description: 'Name, email, password. Nothing else.' },
    { title: 'Claim your link', description: 'Pick bioshop.com/yourshop.' },
    { title: 'Add products & payments', description: 'Go live the same day.' },
];

const points = [
    'Free forever plan — no card required',
    'Unlimited links on every plan',
    'Local payments: bKash, Nagad, Rocket, bank',
    'Orders arrive straight in WhatsApp',
];

const submit = () => {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <AuthLayout
        heading="Create your account"
        description="Your shop link is three minutes away."
        panel-title="Start selling from your bio today."
        panel-text="Join the sellers who replaced a messy DM inbox with one page that takes orders for them."
        :points="points"
    >
        <Head title="Create Account" />

        <!-- What happens next, so the form does not feel like a dead end -->
        <ol class="mb-6 space-y-2.5 rounded-xl border border-line bg-paper-subtle p-4">
            <li v-for="(step, index) in steps" :key="step.title" class="flex gap-3">
                <span
                    :class="[
                        'flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[11px] font-semibold',
                        index === 0 ? 'bg-ink-900 text-paper' : 'bg-white text-ink-400 ring-1 ring-line',
                    ]"
                >
                    {{ index + 1 }}
                </span>
                <span>
                    <span class="block text-[13px] font-medium text-ink-900">{{ step.title }}</span>
                    <span class="block text-[12px] text-ink-500">{{ step.description }}</span>
                </span>
            </li>
        </ol>

        <form class="space-y-4" @submit.prevent="submit">
            <div>
                <label for="name" class="field-label">Full name</label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                    class="field"
                    placeholder="Sarah Rahman"
                />
                <p v-if="form.errors.name" class="field-error">{{ form.errors.name }}</p>
            </div>

            <div>
                <label for="email" class="field-label">Email</label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    autocomplete="username"
                    class="field"
                    placeholder="you@example.com"
                />
                <p v-if="form.errors.email" class="field-error">{{ form.errors.email }}</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="password" class="field-label">Password</label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="field"
                        placeholder="••••••••"
                    />
                    <p v-if="form.errors.password" class="field-error">{{ form.errors.password }}</p>
                </div>

                <div>
                    <label for="password_confirmation" class="field-label">Confirm</label>
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="field"
                        placeholder="••••••••"
                    />
                    <p v-if="form.errors.password_confirmation" class="field-error">
                        {{ form.errors.password_confirmation }}
                    </p>
                </div>
            </div>

            <button type="submit" class="btn-brand w-full" :disabled="form.processing">
                {{ form.processing ? 'Creating account…' : 'Create account' }}
            </button>

            <p class="text-center text-[12px] leading-relaxed text-ink-400">
                By continuing you agree to our
                <Link href="/terms" class="text-ink-600 underline underline-offset-2">Terms</Link>
                and
                <Link href="/privacy" class="text-ink-600 underline underline-offset-2">Privacy Policy</Link>.
            </p>
        </form>

        <p class="mt-6 text-center text-[13px] text-ink-500">
            Already have an account?
            <Link :href="route('login')" class="link-accent">Sign in</Link>
        </p>
    </AuthLayout>
</template>
