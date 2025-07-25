<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Telephone Integrations',
        href: '/telephone'
    },
    {
        title: 'Twilio',
        href: '/telephone/twilio'
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Twilio Integration" />
        <div class="p-6 max-w-3xl">
            <h1 class="text-2xl font-bold mb-4">Twilio Integration</h1>
            <p>This is a placeholder page for Twilio telephone integration.</p>
        </div>
    </AppLayout>
</template>

