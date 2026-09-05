<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import LandingLayout from '@/Layouts/LandingLayout.vue'

defineOptions({
    layout: LandingLayout,
})

const lastUpdated = 'January 1, 2024'
</script>

<template>
    <Head title="Privacy Policy - BioShop">
        <meta name="description" content="BioShop Privacy Policy - Learn how we collect, use, and protect your personal information." />
    </Head>

    <div class="py-14 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12">
                <h1 class="text-4xl font-bold text-slate-900 mb-4">Privacy Policy</h1>
                <p class="text-slate-600">Last updated: {{ lastUpdated }}</p>
            </div>

            <div class="prose prose-slate max-w-none">
                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">1. Introduction</h2>
                    <p class="text-slate-600 mb-4">
                        BioShop ("we," "our," or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our platform.
                    </p>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">2. Information We Collect</h2>

                    <h3 class="text-lg font-semibold text-slate-900 mt-6 mb-3">2.1 Personal Information</h3>
                    <p class="text-slate-600 mb-4">
                        When you create an account, we collect:
                    </p>
                    <ul class="list-disc pl-6 text-slate-600 space-y-2">
                        <li>Name and email address</li>
                        <li>Phone number (optional)</li>
                        <li>Profile information (bio, photo)</li>
                        <li>Payment method details</li>
                    </ul>

                    <h3 class="text-lg font-semibold text-slate-900 mt-6 mb-3">2.2 Usage Information</h3>
                    <p class="text-slate-600 mb-4">
                        We automatically collect:
                    </p>
                    <ul class="list-disc pl-6 text-slate-600 space-y-2">
                        <li>IP address and device information</li>
                        <li>Browser type and version</li>
                        <li>Pages visited and time spent</li>
                        <li>Click patterns and interactions</li>
                    </ul>

                    <h3 class="text-lg font-semibold text-slate-900 mt-6 mb-3">2.3 Analytics Data</h3>
                    <p class="text-slate-600 mb-4">
                        For shop analytics, we collect visitor data including:
                    </p>
                    <ul class="list-disc pl-6 text-slate-600 space-y-2">
                        <li>Page views and unique visitors</li>
                        <li>Geographic location (country/city level)</li>
                        <li>Device types and browsers</li>
                        <li>Traffic sources</li>
                    </ul>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">3. How We Use Your Information</h2>
                    <p class="text-slate-600 mb-4">
                        We use collected information to:
                    </p>
                    <ul class="list-disc pl-6 text-slate-600 space-y-2">
                        <li>Provide and maintain our Service</li>
                        <li>Process transactions and send related information</li>
                        <li>Send administrative updates and notifications</li>
                        <li>Provide customer support</li>
                        <li>Analyze usage patterns and improve our Service</li>
                        <li>Detect and prevent fraud</li>
                        <li>Comply with legal obligations</li>
                    </ul>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">4. Information Sharing</h2>
                    <p class="text-slate-600 mb-4">
                        We do not sell your personal information. We may share information with:
                    </p>
                    <ul class="list-disc pl-6 text-slate-600 space-y-2">
                        <li><strong>Service Providers:</strong> Third parties that help us operate our Service</li>
                        <li><strong>Legal Requirements:</strong> When required by law or to protect our rights</li>
                        <li><strong>Business Transfers:</strong> In connection with mergers or acquisitions</li>
                    </ul>
                </section>

                <section id="cookies" class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">5. Cookies and Tracking</h2>
                    <p class="text-slate-600 mb-4">
                        We use cookies and similar technologies to:
                    </p>
                    <ul class="list-disc pl-6 text-slate-600 space-y-2">
                        <li>Keep you logged in</li>
                        <li>Remember your preferences</li>
                        <li>Analyze traffic and usage patterns</li>
                        <li>Improve user experience</li>
                    </ul>
                    <p class="text-slate-600 mt-4">
                        You can control cookies through your browser settings. Disabling cookies may affect some features of the Service.
                    </p>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">6. Data Security</h2>
                    <p class="text-slate-600 mb-4">
                        We implement appropriate security measures to protect your information:
                    </p>
                    <ul class="list-disc pl-6 text-slate-600 space-y-2">
                        <li>Encryption of data in transit and at rest</li>
                        <li>Regular security audits</li>
                        <li>Access controls and authentication</li>
                        <li>Secure hosting infrastructure</li>
                    </ul>
                    <p class="text-slate-600 mt-4">
                        However, no method of transmission over the Internet is 100% secure. We cannot guarantee absolute security.
                    </p>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">7. Data Retention</h2>
                    <p class="text-slate-600 mb-4">
                        We retain your information for as long as your account is active or as needed to provide services. After account deletion:
                    </p>
                    <ul class="list-disc pl-6 text-slate-600 space-y-2">
                        <li>Personal data is deleted within 30 days</li>
                        <li>Anonymized analytics may be retained</li>
                        <li>Legal records may be kept as required</li>
                    </ul>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">8. Your Rights</h2>
                    <p class="text-slate-600 mb-4">
                        You have the right to:
                    </p>
                    <ul class="list-disc pl-6 text-slate-600 space-y-2">
                        <li><strong>Access:</strong> Request a copy of your personal data</li>
                        <li><strong>Correction:</strong> Update inaccurate information</li>
                        <li><strong>Deletion:</strong> Request deletion of your data</li>
                        <li><strong>Export:</strong> Download your data in a portable format</li>
                        <li><strong>Opt-out:</strong> Unsubscribe from marketing emails</li>
                    </ul>
                    <p class="text-slate-600 mt-4">
                        To exercise these rights, contact us at <a href="mailto:privacy@bioshop.com" class="text-orange-600 hover:text-orange-700">privacy@bioshop.com</a>.
                    </p>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">9. Children's Privacy</h2>
                    <p class="text-slate-600 mb-4">
                        Our Service is not intended for children under 18. We do not knowingly collect personal information from children. If you believe we have collected such information, please contact us immediately.
                    </p>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">10. Third-Party Links</h2>
                    <p class="text-slate-600 mb-4">
                        Our Service may contain links to third-party websites. We are not responsible for the privacy practices of these sites. We encourage you to read their privacy policies.
                    </p>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">11. Changes to This Policy</h2>
                    <p class="text-slate-600 mb-4">
                        We may update this Privacy Policy from time to time. We will notify you of significant changes via email or through the Service. Your continued use after changes constitutes acceptance.
                    </p>
                </section>

                <section class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4">12. Contact Us</h2>
                    <p class="text-slate-600 mb-4">
                        If you have questions about this Privacy Policy, please contact us:
                    </p>
                    <ul class="list-none text-slate-600 space-y-1">
                        <li>Email: <a href="mailto:privacy@bioshop.com" class="text-orange-600 hover:text-orange-700">privacy@bioshop.com</a></li>
                        <li>Address: Dhaka, Bangladesh</li>
                    </ul>
                </section>
            </div>

            <div class="mt-12 p-6 bg-slate-50 rounded-xl">
                <p class="text-slate-600 text-center">
                    By using BioShop, you acknowledge that you have read and understood this Privacy Policy.
                </p>
            </div>
        </div>
    </div>
</template>
