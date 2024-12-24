<?php

namespace App\Services;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class UserService {
    /**
     * Get all users with their ticket count and unresolved ticket count.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getUsersWithTicketCounts()
    {
        $users = User::role('user')->get();
    
        return $users->map(function ($user) {
            $ticketCount = Ticket::where('user_id', $user->id)->count();
            $unresolved = Ticket::where('user_id', $user->id)
                ->where('status', '!=', 'resolved')
                ->count();

            $user->ticket_count = $ticketCount;
            $user->unresolved_count = $unresolved;

            return $user;
        });
    }
}