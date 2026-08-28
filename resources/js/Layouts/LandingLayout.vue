<script setup lang="ts">
import Navbar from '@/Components/landing/Navbar.vue'
import Footer from '@/Components/landing/Footer.vue'
</script>

<template>
    <div class="min-h-screen flex flex-col bg-white">
        <Navbar />
        <main class="flex-1 pt-16">
            <slot />
        </main>
        <Footer />
    </div>
</template>
