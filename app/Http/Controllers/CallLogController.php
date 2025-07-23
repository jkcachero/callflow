<?php

namespace App\Http\Controllers;

use App\Models\CallLog;
use App\Models\CallTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CallLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request, CallTicket $callTicket)
    {
        $this->authorize('update', $callTicket);

        $data = $request->validate([
            'note' => ['required', 'string', 'max:1000'],
        ]);

        CallLog::create([
            'call_ticket_id' => $callTicket->id,
            'user_id' => Auth::user()->id,
            'note' => $data['note'],
            'log_type' => 'note',
        ]);

        return redirect()->back()->with('success', 'Note added successfully');
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
