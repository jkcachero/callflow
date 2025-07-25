<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PhoneIntegrationController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!($user->isSupervisor() || $user->isAdmin())) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        return Inertia::render('Telephone/Index');
    }

    public function threeCX()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!($user->isSupervisor() || $user->isAdmin())) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        return Inertia::render('Telephone/ThreeCX');
    }

    public function twilio()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!($user->isSupervisor() || $user->isAdmin())) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }

        return Inertia::render('Telephone/Twilio');
    }
}
