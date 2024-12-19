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

class TicketController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();
        $tickets = Ticket::with('user', 'categories', 'labels', 'assignedToUser')
            ->when($request->has('status'), function (Builder $query) use ($request) {
                return $query->where('status', $request->input('status'));
            })
            ->when($request->has('priority'), function (Builder $query) use ($request) {
                return $query->withPriority($request->input('priority'));
            })
            ->when($request->has('category'), function (Builder $query) use ($request) {
                return $query->whereRelation('categories', 'id', $request->input('category'));
            })
            ->when($user->role == 'agent', function (Builder $query) {
                $query->where('assigned_to', Auth::user()->id);
            })
            ->when($user->role == 'user', callback: function (Builder $query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->latest()
            ->paginate();

        return view('tickets.index', compact('tickets'));
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

    // todo: update with policies
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }

    // todo: update with policies
    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('tickets.edit', compact('ticket'));
    }

    // todo: update
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->update($request->all());

        return redirect()->route('tickets.index');
    }

    // todo: update 
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('tickets.index');
    }
}
