<template>
    <div class="mt-4 flex flex-col justify-center">
        <a class="mx-auto" :href="route('dashboard')">Dashboard</a>
        <h1 class="mx-auto text-xl font-bold py-4">Call Tickets</h1>
        <table class="w-3/4 mx-auto">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">Caller Name</th>
                    <th class="border border-gray-300 px-4 py-2">Caller Number</th>
                    <th class="border border-gray-300 px-4 py-2">Status</th>
                    <th class="border border-gray-300 px-4 py-2">Assigned Agent</th>
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
                </tr>
            </tbody>
        </table>

        <div class="mt-4 flex justify-center space-x-2">
          <button
            :disabled="!callTickets.prev_page_url"
            @click="fetchPage(callTickets.prev_page_url)"
            class="px-3 py-1 border rounded disabled:opacity-50"
          >
            Previous
          </button>
          <button
            :disabled="!callTickets.next_page_url"
            @click="fetchPage(callTickets.next_page_url)"
            class="px-3 py-1 border rounded disabled:opacity-50"
          >
            Next
          </button>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { route } from 'ziggy-js';
import { usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

const page = usePage();
const callTickets = ref(page.props.callTickets);

// Watch for page prop changes to update callTickets
watch(() => page.props.callTickets, (newVal) => {
  callTickets.value = newVal;
});

function fetchPage(url) {
  if (!url) return;
  Inertia.visit(url, { preserveState: true, preserveScroll: true });
}
</script>

