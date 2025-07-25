<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Telephone Integrations',
        href: '/telephone',
    },
]
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Telephone" />
        <div class="p-6 max-w-3xl">
            <h1 class="text-2xl font-bold mb-3">Telephone Integrations</h1>
            <ul class="list-disc list-inside space-y-2">
                <li>
                    <a href="/telephone/3cx">3CX Integration</a>
                </li>
                <li>
                    <a href="/telephone/twilio">Twilio Integration</a>
                </li>
            </ul>
        </div>
    </AppLayout>
</template>
