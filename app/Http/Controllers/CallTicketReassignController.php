<?php

namespace App\Http\Controllers;

use App\Models\CallTicket;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CallTicketReassignController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, CallTicket $callTicket)
    {
        $this->authorize('reassign', $callTicket);

        $data = $request->validate([
            'assigned_user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'agent')),
            ],
        ]);

        $callTicket->update(['assigned_user_id' => $data['assigned_user_id']]);

        return response()->json(['message' => 'Call ticket reassigned successfully.']);
    }
}
