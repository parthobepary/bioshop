<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <AuthLayout
        heading="Confirm your password"
        description="This part of the app is protected."
        panel-title="A quick check before you continue."
        panel-text="You are about to enter a secure area. Confirming your password keeps your shop and payout details safe."
        :points="['We ask again after a period of inactivity', 'Your password is never shown to anyone']"
    >
        <Head title="Confirm Password" />

        <form class="space-y-4" @submit.prevent="submit">
            <div>
                <label for="password" class="field-label">Password</label>
                <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    required
                    autofocus
                    autocomplete="current-password"
                    class="field"
                    placeholder="••••••••"
                />
                <p v-if="form.errors.password" class="field-error">{{ form.errors.password }}</p>
            </div>

            <button type="submit" class="btn-brand w-full" :disabled="form.processing">
                {{ form.processing ? 'Confirming…' : 'Confirm' }}
            </button>
        </form>
    </AuthLayout>
</template>
