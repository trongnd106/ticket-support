<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AgentCreateRequest;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        $agents = User::role('agent');
    
        if ($request->has('status') && in_array($request->status, ['busy', 'free'])) {
            $agents->where('status', $request->status);
        }
    
        $agents = $agents->paginate(10);
    
        $agentsWithTicketCount = $agents->map(function ($agent) {
            $ticketCount = Ticket::where('assigned_to', $agent->id)->count();
            $unresolved = Ticket::where('assigned_to', $agent->id)
                    ->where('status', '!=', 'resolved')
                    ->count();
            $agent->ticket_count = $ticketCount;
            $agent->unresolved_count = $unresolved;
            return $agent;
        });
    
        return view('agents.index', compact('agentsWithTicketCount', 'agents'));
    }
    
    public function create(){
        return view('agents.create');
    }

    public function store(AgentCreateRequest $request){
        $agent = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
            'password' => Hash::make($request->password),
        ]);
        $agent->assignRole('agent');

        event(new Registered($agent));

        return redirect(route('agents.index'));
    }

    public function update(){
        // todo: when tickets have't resolved reach to 5, change to busy
    }
}
