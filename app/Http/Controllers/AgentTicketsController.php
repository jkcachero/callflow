<?php

namespace App\Http\Controllers;

use App\Models\CallTicket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AgentTicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $agent)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isSupervisor() && !$user->isAdmin()) {
            return redirect()
                ->route('call-tickets.index')
                ->with('error', 'You are not authorized to view this page.');
        }

        $callTickets = $agent
            ->callTickets()
            ->with(['callLogs.user', 'assignedAgent'])
            ->paginate(10);

        return Inertia::render('AgentTickets/Index', [
            'tickets' => $callTickets,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $agent, CallTicket $ticket)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isSupervisor() && !$user->isAdmin()) {
            return redirect()
                ->route('call-tickets.index')
                ->with('error', 'You are not authorized to view this page.');
        }

        // Ensure the ticket belongs to the agent
        if ($ticket->assigned_user_id !== $agent->id) {
            abort(404);
        }

        $ticket->load('callLogs.user', 'assignedAgent');

        return Inertia::render('AgentTickets/Show', [
            'callTicket' => $ticket,
            'agent' => $agent,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
