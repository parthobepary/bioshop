<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    email: string;
    token: string;
}>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <AuthLayout
        heading="Choose a new password"
        description="Pick something you have not used before."
        panel-title="One password away from your dashboard."
        panel-text="Once you save the new password you'll be signed straight back in to your shop."
        :points="['Use at least 8 characters', 'A mix of letters and numbers is safest']"
    >
        <Head title="Reset Password" />

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
                />
                <p v-if="form.errors.email" class="field-error">{{ form.errors.email }}</p>
            </div>

            <div>
                <label for="password" class="field-label">New password</label>
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
                <label for="password_confirmation" class="field-label">Confirm new password</label>
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

            <button type="submit" class="btn-brand w-full" :disabled="form.processing">
                {{ form.processing ? 'Saving…' : 'Reset password' }}
            </button>
        </form>
    </AuthLayout>
</template>
