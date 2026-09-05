<script setup lang="ts">
import { computed } from 'vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    status?: string;
}>();

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <AuthLayout
        heading="Verify your email"
        description="We sent a link to the address you signed up with."
        panel-title="One click and your shop is live."
        panel-text="Verifying your email keeps your account secure and lets us send order notifications to the right place."
        :points="['Check your spam folder if it has not arrived', 'The link works on any device']"
    >
        <Head title="Email Verification" />

        <p
            v-if="verificationLinkSent"
            class="mb-5 rounded-lg border border-success-100 bg-success-50 px-4 py-3 text-[13px] text-success-600"
        >
            A new verification link has been sent to your email address.
        </p>

        <p class="text-[14px] leading-relaxed text-ink-600">
            Click the link in the email to finish setting up your account. If it never arrived, we
            can send another one.
        </p>

        <form class="mt-6 space-y-3" @submit.prevent="submit">
            <button type="submit" class="btn-brand w-full" :disabled="form.processing">
                {{ form.processing ? 'Sending…' : 'Resend verification email' }}
            </button>

            <Link :href="route('logout')" method="post" as="button" class="btn-secondary w-full">
                Log out
            </Link>
        </form>
    </AuthLayout>
</template>
