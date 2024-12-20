<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ticket;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        $agents = User::role('agent');
    
        if ($request->has('status') && in_array($request->status, ['busy', 'free'])) {
            $agents->where('status', $request->status);
        }
    
        $agents = $agents->get();
    
        $agentsWithTicketCount = $agents->map(function ($agent) {
            $ticketCount = Ticket::where('assigned_to', $agent->id)->count();
            $agent->ticket_count = $ticketCount;
            return $agent;
        });
    
        return view('agents.index', compact('agentsWithTicketCount'));
    }
    
    
}
