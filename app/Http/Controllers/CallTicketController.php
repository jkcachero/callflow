<?php

namespace App\Http\Controllers;

use App\Http\Resources\CallTicketResource;
use App\Models\CallTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CallTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = CallTicket::query();

        if ($user->isAgent()) {
            $query->where('assigned_user_id', $user->id);
        }

        $callTickets = $query
            ->where('status', '!=', 'escalated')
            ->with('assignedAgent')
            ->paginate(10);

        return Inertia::render('CallTicket/Index', [
            'callTickets' => $callTickets,
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
    public function show(string $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $callTicket = CallTicket::findOrFail($id);

        if ($user->isSupervisor() || $user->isAdmin()) {
            $callTicket->load('assignedAgent');
        }

        return Inertia::render('CallTicket/Show', [
            'callTicket' => $callTicket,
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
    public function update(Request $request, CallTicket $callTicket)
    {
        $this->authorize('update', $callTicket);

        $data = $request->validate([
            'status' => ['required', Rule::in(CallTicket::STATUS_OPTIONS)],
        ]);

        if ($data['status'] === 'escalated' && !$request->user()->isSupervisor() && !$request->user()->isAdmin()) {
            abort(403, 'You are not authorized to escalate calls.');
        }

        $callTicket->update($data);

        return new CallTicketResource($callTicket);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
