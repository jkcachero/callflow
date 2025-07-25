<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Inertia } from '@inertiajs/inertia';
import { Head, usePage } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Reports',
        href: '',
    },
];

interface TicketsByAgent {
    name: string;
    active: number;
    completed: number;
    forwarded: number;
    escalated: number;
    total: number;
    route: string;
}

interface EscalationRecord {
    date: string;
    count: number;
}

const page = usePage<{
    statusCounts: Record<string, number>;
    ticketsByAgent: TicketsByAgent[];
    escalationsOverTime: EscalationRecord[];
}>();

const statusCounts = page.props.statusCounts;
const ticketsByAgent = page.props.ticketsByAgent || [];
const escalationsOverTime = page.props.escalationsOverTime || [];

function showagentTickets(url: string | null) {
    if (!url) return;
    Inertia.visit(url, { preserveState: true, preserveScroll: true });
}
</script>

<template>
    <Head title="Call log" />
    <AppLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-4xl mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Call Tickets Status Report</h1>
        <table class="w-full border border-gray-800 rounded mb-8">
            <thead>
                <tr class="bg-gray-900">
                    <th class="border border-gray-800 px-4 py-2 text-left">Status</th>
                    <th class="border border-gray-800 px-4 py-2 text-right">Count</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(count, status) in statusCounts" :key="status" class="hover:bg-gray-950">
                    <td class="border border-gray-800 px-4 py-2">{{ status }}</td>
                    <td class="border border-gray-800 px-4 py-2 text-right">{{ count }}</td>
                </tr>
            </tbody>
        </table>

        <h2 class="text-xl font-semibold mb-4">Tickets by Agent</h2>
        <table class="w-full border border-gray-800 rounded mb-8">
            <thead>
                <tr class="bg-gray-900">
                    <th class="border border-gray-800 px-4 py-2 text-left">Agent Name</th>
                    <th class="border border-gray-800 px-4 py-2 text-right">Active</th>
                    <th class="border border-gray-800 px-4 py-2 text-right">Completed</th>
                    <th class="border border-gray-800 px-4 py-2 text-right">Forwarded</th>
                    <th class="border border-gray-800 px-4 py-2 text-right">Escalated</th>
                    <th class="border border-gray-800 px-4 py-2 text-right">Total</th>
                    <th class="border border-gray-800 px-4 py-2 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="agent in ticketsByAgent" :key="agent.name" class="hover:bg-gray-950">
                    <td class="border border-gray-800 px-4 py-2">{{ agent.name }}</td>
                    <td class="border border-gray-800 px-4 py-2 text-right">{{ agent.active }}</td>
                    <td class="border border-gray-800 px-4 py-2 text-right">{{ agent.completed }}</td>
                    <td class="border border-gray-800 px-4 py-2 text-right">{{ agent.forwarded }}</td>
                    <td class="border border-gray-800 px-4 py-2 text-right">{{ agent.escalated }}</td>
                    <td class="border border-gray-800 px-4 py-2 text-right">{{ agent.total }}</td>
                    <td class="border border-gray-800 px-4 py-2 text-right">
                        <button class="px-2 py-1 bg-green-800 text-xs font-bold uppercase border rounded" @click.prevent="showagentTickets(agent.route)">Show</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <h2 class="text-xl font-semibold mb-4">Escalations Over Time</h2>
        <table class="w-full border border-gray-800 rounded">
            <thead>
                <tr class="bg-gray-900">
                    <th class="border border-gray-800 px-4 py-2 text-left">Date</th>
                    <th class="border border-gray-800 px-4 py-2 text-right">Escalated Tickets</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="record in escalationsOverTime" :key="record.date" class="hover:bg-gray-950">
                    <td class="border border-gray-800 px-4 py-2">{{ record.date }}</td>
                    <td class="border border-gray-800 px-4 py-2 text-right">{{ record.count }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    </AppLayout>
</template>

