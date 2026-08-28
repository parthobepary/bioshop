<script setup lang="ts">
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import LandingLayout from '@/Layouts/LandingLayout.vue'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import {
    Mail,
    Phone,
    MapPin,
    MessageCircle,
    Clock,
    Send,
    Facebook,
    Instagram,
    CheckCircle,
} from 'lucide-vue-next'

defineOptions({
    layout: LandingLayout,
})

const form = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
})

const submitted = ref(false)

const submit = () => {
    form.post('/contact', {
        onSuccess: () => {
            submitted.value = true
            form.reset()
        },
    })
}

const contactMethods = [
    {
        icon: Mail,
        title: 'Email',
        value: 'hello@bioshop.com',
        href: 'mailto:hello@bioshop.com',
        description: 'Send us an email anytime',
    },
    {
        icon: MessageCircle,
        title: 'WhatsApp',
        value: '+880 1700-000000',
        href: 'https://wa.me/8801700000000',
        description: 'Chat with us on WhatsApp',
    },
    {
        icon: Phone,
        title: 'Phone',
        value: '+880 1700-000000',
        href: 'tel:+8801700000000',
        description: 'Call us during business hours',
    },
    {
        icon: MapPin,
        title: 'Office',
        value: 'Dhaka, Bangladesh',
        href: null,
        description: 'Our headquarters location',
    },
]

const faqs = [
    {
        question: 'How quickly do you respond?',
        answer: 'We typically respond to all inquiries within 24 hours during business days.',
    },
    {
        question: 'Do you offer phone support?',
        answer: 'Yes, phone support is available for paid plan users during business hours (9 AM - 6 PM BST).',
    },
    {
        question: 'Can I request a feature?',
        answer: 'Absolutely! We love hearing from our users. Use the contact form to share your ideas.',
    },
]
</script>

<template>
    <Head title="Contact Us - BioShop">
        <meta name="description" content="Get in touch with the BioShop team. We're here to help with any questions about our platform." />
    </Head>

    <!-- Hero -->
    <section class="py-20 bg-gradient-to-br from-slate-50 to-orange-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold text-slate-900 mb-6">
                Get in Touch
            </h1>
            <p class="text-xl text-slate-600 max-w-2xl mx-auto">
                Have a question or need help? We're here for you. Reach out and we'll get back to you as soon as possible.
            </p>
        </div>
    </section>

    <!-- Contact Methods -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a
                    v-for="method in contactMethods"
                    :key="method.title"
                    :href="method.href || '#'"
                    :class="[
                        'bg-slate-50 rounded-2xl p-6 text-center transition-all duration-300',
                        method.href ? 'hover:bg-orange-50 hover:shadow-lg cursor-pointer' : ''
                    ]"
                >
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-pink-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <component :is="method.icon" class="w-7 h-7 text-white" />
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-1">{{ method.title }}</h3>
                    <p class="text-orange-600 font-medium mb-1">{{ method.value }}</p>
                    <p class="text-sm text-slate-500">{{ method.description }}</p>
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Form & Info -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Form -->
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-slate-200">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Send us a Message</h2>

                    <div v-if="submitted" class="text-center py-12">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <CheckCircle class="w-8 h-8 text-green-500" />
                        </div>
                        <h3 class="text-xl font-semibold text-slate-900 mb-2">Message Sent!</h3>
                        <p class="text-slate-600 mb-4">Thank you for reaching out. We'll get back to you soon.</p>
                        <Button @click="submitted = false" variant="outline">
                            Send Another Message
                        </Button>
                    </div>

                    <form v-else @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Your Name
                                </label>
                                <Input
                                    v-model="form.name"
                                    type="text"
                                    placeholder="John Doe"
                                    required
                                />
                                <p v-if="form.errors.name" class="text-sm text-red-500 mt-1">
                                    {{ form.errors.name }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Email Address
                                </label>
                                <Input
                                    v-model="form.email"
                                    type="email"
                                    placeholder="john@example.com"
                                    required
                                />
                                <p v-if="form.errors.email" class="text-sm text-red-500 mt-1">
                                    {{ form.errors.email }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Subject
                            </label>
                            <Input
                                v-model="form.subject"
                                type="text"
                                placeholder="How can we help?"
                                required
                            />
                            <p v-if="form.errors.subject" class="text-sm text-red-500 mt-1">
                                {{ form.errors.subject }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Message
                            </label>
                            <textarea
                                v-model="form.message"
                                rows="5"
                                placeholder="Tell us more about your inquiry..."
                                required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:border-orange-500 focus:ring-1 focus:ring-orange-500 resize-none"
                            ></textarea>
                            <p v-if="form.errors.message" class="text-sm text-red-500 mt-1">
                                {{ form.errors.message }}
                            </p>
                        </div>

                        <Button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white"
                            size="lg"
                        >
                            <Send class="w-5 h-5 mr-2" />
                            {{ form.processing ? 'Sending...' : 'Send Message' }}
                        </Button>
                    </form>
                </div>

                <!-- Info -->
                <div class="space-y-8">
                    <!-- Business Hours -->
                    <div class="bg-white rounded-2xl p-6 border border-slate-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <Clock class="w-5 h-5 text-orange-600" />
                            </div>
                            <h3 class="text-lg font-semibold text-slate-900">Business Hours</h3>
                        </div>
                        <div class="space-y-2 text-slate-600">
                            <div class="flex justify-between">
                                <span>Saturday - Thursday</span>
                                <span class="font-medium">9:00 AM - 6:00 PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Friday</span>
                                <span class="text-slate-400">Closed</span>
                            </div>
                        </div>
                        <p class="text-sm text-slate-500 mt-4">
                            * All times are in Bangladesh Standard Time (BST)
                        </p>
                    </div>

                    <!-- FAQ -->
                    <div class="bg-white rounded-2xl p-6 border border-slate-200">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">Quick Answers</h3>
                        <div class="space-y-4">
                            <div v-for="faq in faqs" :key="faq.question">
                                <h4 class="font-medium text-slate-900 mb-1">{{ faq.question }}</h4>
                                <p class="text-sm text-slate-600">{{ faq.answer }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Links -->
                    <div class="bg-white rounded-2xl p-6 border border-slate-200">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">Follow Us</h3>
                        <div class="flex gap-4">
                            <a
                                href="https://facebook.com/bioshop"
                                target="_blank"
                                class="w-12 h-12 bg-blue-600 hover:bg-blue-700 rounded-xl flex items-center justify-center text-white transition-colors"
                            >
                                <Facebook class="w-6 h-6" />
                            </a>
                            <a
                                href="https://instagram.com/bioshop"
                                target="_blank"
                                class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 rounded-xl flex items-center justify-center text-white transition-colors"
                            >
                                <Instagram class="w-6 h-6" />
                            </a>
                            <a
                                href="https://wa.me/8801700000000"
                                target="_blank"
                                class="w-12 h-12 bg-green-500 hover:bg-green-600 rounded-xl flex items-center justify-center text-white transition-colors"
                            >
                                <MessageCircle class="w-6 h-6" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
