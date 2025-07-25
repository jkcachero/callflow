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
        title: '3CX',
        href: '/telephone/3cx'
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="3CX Integration" />
        <div class="p-6 max-w-3xl">
            <h1 class="text-2xl font-bold mb-4">3CX Integration</h1>
            <p>This is a placeholder page for 3CX telephone integration.</p>
        </div>
    </AppLayout>
</template>

