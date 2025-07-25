<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

interface AssignedAgent {
    id: number;
    role: string;
    name: string;
}

interface AgentTicket {
    id: number;
    caller_name: string;
    caller_number: string;
    status: string;
    assigned_agent: AssignedAgent;
}

interface Pagination<T> {
    data: T[];
    prev_page_url: string;
    next_page_url: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Agent Reports',
        href: '/agent-reports',
    },
    {
        title: 'Agent Tickets',
        href: '',
    },
];

const page = usePage<{ tickets: Pagination<AgentTicket> }>();
const tickets = ref<Pagination<AgentTicket>>(page.props.tickets);

watch(() => page.props.tickets, (newVal) => {
    tickets.value = newVal;
});

function fetchPage(url: string | null) {
    if (!url) return;
    Inertia.visit(url, { preserveState: true, preserveScroll: true });
}
</script>

<template>
    <Head title="Agent Tickets" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col rounded-xl overflow-x-auto">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <div class="mt-4 flex flex-col justify-center">
                    <h1 class="mx-auto text-xl font-bold py-4">Agent Tickets</h1>
                    <table class="w-3/4 mx-auto">
                        <thead>
                            <tr>
                                <th class="border border-gray-300 px-4 py-2">Caller Name</th>
                                <th class="border border-gray-300 px-4 py-2">Caller Number</th>
                                <th class="border border-gray-300 px-4 py-2">Status</th>
                                <th class="border border-gray-300 px-4 py-2">Assigned Agent</th>
                                <th class="border border-gray-300 px-4 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="callTicket in tickets.data" :key="callTicket.id" class="hover:bg-gray-900">
                                <td class="border border-gray-300 px-4 py-2">{{ callTicket.caller_name }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ callTicket.caller_number }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ callTicket.status }}</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    {{ callTicket.assigned_agent.role[0].toUpperCase() + callTicket.assigned_agent.role.slice(1) }}: {{ callTicket.assigned_agent.name }}
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <button
                                        class="px-2 py-1 bg-green-800 text-xs font-bold uppercase border rounded"
                                        @click.prevent="() => Inertia.visit(route('agents.tickets.show', [callTicket.assigned_agent.id, callTicket.id]))"
                                    >Show</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4 flex justify-center space-x-2">
                      <button
                          :disabled="!tickets.prev_page_url"
                          @click.prevent="fetchPage(tickets.prev_page_url)"
                          class="px-3 py-1 border rounded disabled:opacity-50"
                      >
                          Previous
                      </button>
                      <button
                          :disabled="!tickets.next_page_url"
                          @click.prevent="fetchPage(tickets.next_page_url)"
                          class="px-3 py-1 border rounded disabled:opacity-50"
                      >
                          Next
                      </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

