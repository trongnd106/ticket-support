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
use App\Notifications\AssignedTicketNotification;

class TicketController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $tickets = Ticket::with('creator', 'categories', 'labels', 'assignee')
            ->when($request->has('status'), function (Builder $query) use ($request) {
                return $query->where('status', $request->input('status'));
            })
            ->when($request->has('priority'), function (Builder $query) use ($request) {
                return $query->where('priority', $request->input('priority'));
            })
            ->when($request->has('category'), function (Builder $query) use ($request) {
                return $query->whereHas('categories', function ($query) use ($request) {
                    $query->where('categories.name', $request->input('category'));
                });
            })
            ->when($request->has('label'), function (Builder $query) use ($request) {
                return $query->whereHas('labels', function ($query) use ($request) {
                    $query->where('labels.name', $request->input('label'));
                });
            })
            ->when($user->hasRole('user'), function (Builder $query) use ($user) {
                return $query->where('user_id', $user->id);
            })  
            ->when($user->hasRole('admin'), function (Builder $query) {
                return $query;
            })
            ->latest()
            ->paginate(10);
        
        return view('tickets.index', compact('tickets'));
    }

    public function agent(Request $request)
    {
        $user = Auth::user();
        
        $tickets = Ticket::with('creator', 'categories', 'labels', 'assignee')
            ->when($request->has('status'), function (Builder $query) use ($request) {
                return $query->where('status', $request->input('status'));
            })
            ->when($request->has('priority'), function (Builder $query) use ($request) {
                return $query->where('priority', $request->input('priority'));
            })
            ->when($request->has('category'), function (Builder $query) use ($request) {
                return $query->whereHas('categories', function ($query) use ($request) {
                    $query->where('categories.name', $request->input('category'));
                });
            })
            ->when($request->has('label'), function (Builder $query) use ($request) {
                return $query->whereHas('labels', function ($query) use ($request) {
                    $query->where('labels.name', $request->input('label'));
                });
            })
            ->when($user->hasRole('agent'), function (Builder $query) use ($user) {
                return $query->where('assigned_to', $user->id);
            })      
            ->latest()
            ->paginate(10);
        
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
        $user = Auth::user(); 
        
        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'pending',
            'user_id' => $user->id,
        ]);
        if ($request->has('categories')) {
            $ticket->categories()->sync($request->input('categories'));
        }
        if ($request->has('labels')) {
            $ticket->labels()->sync($request->input('labels'));
        }
        return redirect()->route('tickets.index');
    }

    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }

    public function edit($id)
    {
        $users = User::role('agent')->orderBy('name')->pluck('name','id');
        $ticket = Ticket::findOrFail($id);
        $labels = Label::pluck('name', 'id');
        $categories = Category::pluck('name', 'id');
        return view('tickets.edit', compact('ticket','labels','categories','users'));
    }

    public function update(TicketUpdateRequest $request, Ticket $ticket)
    {   
        $ticket->update($request->only('title', 'message', 'status', 'priority', 'assigned_to'));

        $ticket->categories()->sync($request->input('categories'));
        $ticket->labels()->sync($request->input('labels'));

        if ($ticket->wasChanged('assigned_to')) {
            User::find($request->input('assigned_to'))->notify(new AssignedTicketNotification($ticket));
        }

        if (!is_null($request->input('attachments')[0])) {
            foreach ($request->input('attachments') as $file) {
                $ticket->addMediaFromDisk($file, 'public')->toMediaCollection('tickets_attachments');
            }
        }

        return to_route('tickets.index');
    }


    // todo: update 
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('tickets.index');
    }
}
