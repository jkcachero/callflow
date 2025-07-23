<script setup lang="ts">
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import { route } from 'ziggy-js';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';

interface CallLog {
    id: number;
    note: string;
    log_type: string;
    created_at: string;
    user: {
        id: number;
        name: string;
    }
}

interface CallTicket {
    id: number;
    caller_name: string;
    caller_number: string;
    status: string;
    assigned_agent: {
        id: number;
        name: string;
        role: string;
    };
    call_logs: CallLog[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Call Logs',
        href: '/call-tickets',
    },
];

const page = usePage<{ callTicket: CallTicket }>();
const callTicket = page.props.callTicket;

const statusOptions = ['active', 'completed', 'forwarded', 'escalated'];
const status = ref(callTicket.status);
const updatingStatus = ref(false);
const statusError = ref<string | null>(null);

const newNote = ref('');
const submittingNote = ref(false);
const noteError = ref<string | null>(null);

function addNote() {
    if (!newNote.value.trim()) return;
    submittingNote.value = true;
    noteError.value = null;
    Inertia.post(route('call-tickets.logs.store', callTicket.id), { note: newNote.value }, {
        preserveScroll: true,
        onSuccess: () => {
            newNote.value = '';
        },
        onError: (errors) => {
            noteError.value = errors.note ? errors.note[0] : 'Failed to add note.';
        },
        onFinish: () => {
            submittingNote.value = false;
        },
    });
}

function updateStatus() {
    if (status.value === callTicket.status) return;
    updatingStatus.value = true;
    statusError.value = null;
    Inertia.put(route('call-tickets.update', callTicket.id), { status: status.value }, {
        onSuccess: () => {
            // Optionally update local callTicket.status here if needed
        },
        onError: (errors) => {
            statusError.value = errors.status ? errors.status[0] : 'Failed to update status.';
        },
        onFinish: () => {
            updatingStatus.value = false;
        },
    });
}

function back() {
    Inertia.visit(route('call-tickets.index'));
}
</script>

<template>
    <Head title="Call log" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col rounded-xl overflow-x-auto">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <div class="flex flex-col max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                    <div class="flex flex-col mb-4 items-center">
                        <button class="px-3 py-2 rounded-xl border border-gray-800 self-start" @click="back">Back</button>
                        <h1 class="text-lg font-bold">Call Ticket Details</h1>
                    </div>
                    <p class="text-gray-300"><strong>Caller: </strong> {{ callTicket.caller_name }} ({{ callTicket.caller_number }})</p>
                    <p class="text-gray-300"><strong>Status: </strong> {{ callTicket.status }}</p>
                    <p class="text-gray-300"><strong>Assigned Agent: </strong> {{ callTicket.assigned_agent.name }} ({{ callTicket.assigned_agent.role }})</p>

                    <div class="flex mt-4 items-center justify-start gap-3">
                        <label for="status" class="block font-semibold mb-1">Update Status</label>
                        <select id="status" v-model="status" :disabled="updatingStatus" class="border rounded p-2">
                            <option v-for="option in statusOptions" :key="option" :value="option">{{ option }}</option>
                        </select>
                        <button @click="updateStatus" :disabled="updatingStatus || status === callTicket.status" class="ml-2 px-3 py-1 bg-blue-600 text-white rounded disabled:opacity-50">
                            <span v-if="updatingStatus">Updating...</span>
                            <span v-else>Update</span>
                        </button>
                    </div>
                    <p v-if="statusError" class="text-red-500 mt-1">{{ statusError }}</p>

                    <hr class="my-4">

                    <h2 class="mx-auto mb-4 font-bold">Call Logs</h2>
                    <ul>
                        <li class="border border-gray px-3 py-2 text-gray-300" v-for="log in callTicket.call_logs" :key="log.id">
                            <strong>{{ log.user.name }}:</strong> {{ log.note }} <em>({{ new Date(log.created_at).toLocaleString() }})</em>
                        </li>
                    </ul>

                    <hr class="my-4">

                    <div class="flex flex-col items-center">
                        <h3>Log Note</h3>
                        <textarea class="mt-4 px-3 py-2 w-full border border-gray rounded-xl" v-model="newNote" rows="6" cols="50"></textarea>
                        <button class="w-32 px-3 py-2 mt-4 bg-blue-600 rounded-xl disabled:opacity-50" @click="addNote" :disabled="submittingNote || !newNote.trim()">
                            <span v-if="submittingNote">Submitting...</span>
                            <span v-else>Add Note</span>
                        </button>
                        <p v-if="noteError" class="text-red-500 mt-1">{{ noteError }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
