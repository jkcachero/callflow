<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { route } from 'ziggy-js';
import { usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

interface AssignedAgent {
    role: string;
    name: string;
}

interface CallTicket {
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
        title: 'Call tickets',
        href: '/call-tickets',
    },
];

const page = usePage<{ callTickets: Pagination<CallTicket> }>();
const callTickets = ref<Pagination<CallTicket>>(page.props.callTickets);

watch(() => page.props.callTickets, (newVal) => {
    callTickets.value = newVal;
});

function fetchPage(url: string | null) {
    if (!url) return;
    Inertia.visit(url, { preserveState: true, preserveScroll: true });
}
</script>

<template>
    <Head title="Call tickets" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col rounded-xl overflow-x-auto">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <div class="mt-4 flex flex-col justify-center">
                    <h1 class="mx-auto text-xl font-bold py-4">Call Tickets</h1>
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
                            <tr v-for="callTicket in callTickets.data" :key="callTicket.id" class="hover:bg-gray-800">
                                <td class="border border-gray-300 px-4 py-2">{{ callTicket.caller_name }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ callTicket.caller_number }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ callTicket.status }}</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    {{ callTicket.assigned_agent.role[0].toUpperCase() + callTicket.assigned_agent.role.slice(1) }}: {{ callTicket.assigned_agent.name }}
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <button class="px-2 py-1 bg-green-800 text-xs font-bold uppercase border rounded" @click.prevent="fetchPage('call-tickets/'+callTicket.id)">Show</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4 flex justify-center space-x-2">
                      <button
                          :disabled="!callTickets.prev_page_url"
                          @click.prevent="fetchPage(callTickets.prev_page_url)"
                          class="px-3 py-1 border rounded disabled:opacity-50"
                      >
                          Previous
                      </button>
                      <button
                          :disabled="!callTickets.next_page_url"
                          @click.prevent="fetchPage(callTickets.next_page_url)"
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
