<?php

namespace App\Policies;

use App\Models\CallTicket;
use App\Models\User;

class CallTicketPolicy
{
    public function update(User $user, CallTicket $callTicket): bool
    {
        if ($user->isAdmin() || $user->isSupervisor()) {
            return true;
        }

        if ($user->isAgent() && $callTicket->assigned_user_id === $user->id) {
            return true;
        }

        return false;
    }

    public function reassign(User $user, CallTicket $callTicket): bool
    {
        return $user->isSupervisor() || $user->isAdmin();
    }
}
