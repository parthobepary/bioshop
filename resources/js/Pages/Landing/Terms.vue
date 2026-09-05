<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import LandingLayout from '@/Layouts/LandingLayout.vue'

defineOptions({
    layout: LandingLayout,
})

const lastUpdated = 'January 1, 2024'
</script>

<template>
    <Head title="Terms of Service - BioShop">
        <meta name="description" content="BioShop Terms of Service - Read our terms and conditions for using the platform." />
    </Head>

    <div class="py-14 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12">
                <h1 class="text-4xl font-bold text-slate-900 mb-4">Terms of Service</h1>
                <p class="text-slate-600">Last updated: {{ lastUpdated }}</p>
            </div>

            <div class="prose prose-slate max-w-none">
                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">1. Acceptance of Terms</h2>
                    <p class="text-slate-600 mb-4">
                        By accessing or using BioShop ("Service"), you agree to be bound by these Terms of Service ("Terms"). If you disagree with any part of these terms, you may not access the Service.
                    </p>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">2. Description of Service</h2>
                    <p class="text-slate-600 mb-4">
                        BioShop is a link-in-bio platform that allows users to create online storefronts, showcase products, and manage their online presence. The Service includes:
                    </p>
                    <ul class="list-disc pl-6 text-slate-600 space-y-2">
                        <li>Customizable profile pages</li>
                        <li>Product showcase features</li>
                        <li>Payment method integration</li>
                        <li>Analytics and tracking</li>
                        <li>WhatsApp integration</li>
                    </ul>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">3. User Accounts</h2>
                    <p class="text-slate-600 mb-4">
                        When you create an account with us, you must provide accurate, complete, and current information. You are responsible for:
                    </p>
                    <ul class="list-disc pl-6 text-slate-600 space-y-2">
                        <li>Maintaining the confidentiality of your account credentials</li>
                        <li>All activities that occur under your account</li>
                        <li>Notifying us immediately of any unauthorized use</li>
                    </ul>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">4. Acceptable Use</h2>
                    <p class="text-slate-600 mb-4">
                        You agree not to use the Service to:
                    </p>
                    <ul class="list-disc pl-6 text-slate-600 space-y-2">
                        <li>Violate any laws or regulations</li>
                        <li>Infringe on intellectual property rights</li>
                        <li>Distribute malware or harmful content</li>
                        <li>Engage in fraudulent activities</li>
                        <li>Harass, abuse, or harm others</li>
                        <li>Sell illegal or prohibited items</li>
                        <li>Spam or send unsolicited communications</li>
                    </ul>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">5. Payment Terms</h2>
                    <p class="text-slate-600 mb-4">
                        For paid subscription plans:
                    </p>
                    <ul class="list-disc pl-6 text-slate-600 space-y-2">
                        <li>All fees are quoted in Bangladeshi Taka (BDT)</li>
                        <li>Payments are processed through bKash, Nagad, Rocket, or bank transfer</li>
                        <li>Subscriptions are billed monthly</li>
                        <li>Refunds are available within 7 days of purchase</li>
                        <li>We reserve the right to change pricing with 30 days notice</li>
                    </ul>
                </section>

                <section id="refunds" class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">6. Refund Policy</h2>
                    <p class="text-slate-600 mb-4">
                        We offer a 7-day money-back guarantee for all paid plans. To request a refund:
                    </p>
                    <ul class="list-disc pl-6 text-slate-600 space-y-2">
                        <li>Contact us within 7 days of your purchase</li>
                        <li>Provide your account email and transaction details</li>
                        <li>Refunds will be processed within 5-7 business days</li>
                        <li>Refunds will be credited to your original payment method</li>
                    </ul>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">7. Content Ownership</h2>
                    <p class="text-slate-600 mb-4">
                        You retain ownership of all content you upload to BioShop. By using our Service, you grant us a non-exclusive license to display and distribute your content solely for the purpose of providing the Service.
                    </p>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">8. Termination</h2>
                    <p class="text-slate-600 mb-4">
                        We may terminate or suspend your account immediately, without prior notice, for:
                    </p>
                    <ul class="list-disc pl-6 text-slate-600 space-y-2">
                        <li>Violation of these Terms</li>
                        <li>Fraudulent or illegal activity</li>
                        <li>Non-payment of fees</li>
                        <li>At our sole discretion for any reason</li>
                    </ul>
                    <p class="text-slate-600 mt-4">
                        You may cancel your account at any time through your account settings.
                    </p>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">9. Disclaimer of Warranties</h2>
                    <p class="text-slate-600 mb-4">
                        The Service is provided "as is" without warranties of any kind. We do not guarantee that the Service will be uninterrupted, secure, or error-free.
                    </p>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">10. Limitation of Liability</h2>
                    <p class="text-slate-600 mb-4">
                        In no event shall BioShop be liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of the Service.
                    </p>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">11. Changes to Terms</h2>
                    <p class="text-slate-600 mb-4">
                        We reserve the right to modify these Terms at any time. We will notify users of significant changes via email or through the Service. Continued use after changes constitutes acceptance.
                    </p>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">12. Governing Law</h2>
                    <p class="text-slate-600 mb-4">
                        These Terms shall be governed by the laws of Bangladesh. Any disputes shall be resolved in the courts of Dhaka, Bangladesh.
                    </p>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">13. Contact Us</h2>
                    <p class="text-slate-600 mb-4">
                        If you have any questions about these Terms, please contact us at:
                    </p>
                    <ul class="list-none text-slate-600 space-y-1">
                        <li>Email: <a href="mailto:legal@bioshop.com" class="text-orange-600 hover:text-orange-700">legal@bioshop.com</a></li>
                        <li>Address: Dhaka, Bangladesh</li>
                    </ul>
                </section>
            </div>

            <div class="mt-12 p-6 bg-slate-50 rounded-xl">
                <p class="text-slate-600 text-center">
                    By using BioShop, you acknowledge that you have read and understood these Terms of Service.
                </p>
            </div>
        </div>
    </div>
</template>
