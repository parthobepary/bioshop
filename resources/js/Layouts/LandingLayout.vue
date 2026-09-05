<script setup lang="ts">
import Navbar from '@/Components/landing/Navbar.vue'
import Footer from '@/Components/landing/Footer.vue'
</script>

<template>
    <div class="flex min-h-screen flex-col bg-paper">
        <Navbar />
        <main class="flex-1 pt-14">
            <slot />
        </main>
        <Footer />
    </div>
</template>
