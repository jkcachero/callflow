<?php

namespace App\Http\Controllers;

use App\Models\CallTicket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!($user->isSupervisor() || $user->isAdmin())) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        // Status counts
        $statusCounts = CallTicket::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Tickets by agent
        $ticketsByAgent = CallTicket::selectRaw('assigned_user_id, status, COUNT(*) as count')
            ->groupBy('assigned_user_id', 'status')
            ->get()
            ->groupBy('assigned_user_id')
            ->map(function ($group) {
                $agentName = $group->first()->assignedAgent->name ?? 'Unknown';
                $counts = $group->pluck('count', 'status')->toArray();

                return [
                    'name' => $agentName,
                    'active' => $counts['active'] ?? 0,
                    'completed' => $counts['completed'] ?? 0,
                    'forwarded' => $counts['forwarded'] ?? 0,
                    'escalated' => $counts['escalated'] ?? 0,
                    'total' => array_sum($counts),
                ];
            })->values();

        // Escalations over time (last 30 days)
        // TODO: Should be a graph
        $escalationsOverTime = CallTicket::selectRaw('DATE(updated_at) as date, COUNT(*) as count')
            ->where('status', 'escalated')
            ->where('updated_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return Inertia::render('Reports/Index', [
            'statusCounts' => $statusCounts,
            'ticketsByAgent' => $ticketsByAgent,
            'escalationsOverTime' => $escalationsOverTime,
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
        //
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
