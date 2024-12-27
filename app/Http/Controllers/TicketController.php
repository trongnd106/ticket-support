<?php

namespace App\Http\Controllers;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\Label;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TicketCreateRequest;
use App\Http\Requests\TicketUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Notifications\TicketCreatedNotification;
use App\Notifications\TicketAssignedNotification;
use App\Notifications\TicketStatusUpdatedNotification;
use App\Services\TicketService;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }
    public function index(Request $request)
    {
        $tickets = $this->ticketService->getAllTickets($request);
        return view('tickets.index', compact('tickets'));
    }

    public function agent(Request $request)
    {
        $tickets = $this->ticketService->getAgentTickets($request);
        return view('tickets.agent', compact('tickets'));
    }
   
    public function create(){
        $labels = Label::pluck('name', 'id');
        $categories = Category::pluck('name', 'id');
        $users = User::role('agent')->orderBy('name')->pluck('name', 'id');
        return view('tickets.create', compact('labels', 'categories', 'users'));
    }
    public function store(TicketCreateRequest $request)
    {
        $this->ticketService->create($request);
        return redirect()->route('tickets.index');
    }

    public function show($id)
    {
        $ticket = $this->ticketService->getDetail($id);
        return view('tickets.show', compact('ticket'));
    }

    public function edit($id)
    {
        $users = User::role('agent')->where('status','free')->orderBy('name')->pluck('name','id');
        $ticket = Ticket::findOrFail($id);
        $labels = Label::pluck('name', 'id');
        $categories = Category::pluck('name', 'id');
        return view('tickets.edit', compact('ticket','labels','categories','users'));
    }

    public function update(TicketUpdateRequest $request, Ticket $ticket)
    {   
        $this->ticketService->update($request, $ticket);
        return to_route('tickets.index');
    }

    public function destroy($id)
    {
        $this->ticketService->delete($id);
        return redirect()->route('tickets.index');
    }
}
