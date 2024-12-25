<?php

namespace App\Services;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Notifications\TicketCreatedNotification;
use App\Notifications\TicketAssignedNotification;
use App\Notifications\TicketStatusUpdatedNotification;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketService {
    public function getAllTickets($request){
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
        
        return $tickets;
    }
    public function getAgentTickets($request){
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
        
        return $tickets;
    }
    public function getDetail($id){
        try {
            $ticket = Ticket::findOrFail($id);
            return $ticket;
        } catch(ModelNotFoundException $e) {
            echo $e->getMessage();
            return null;
        }
    }
    public function create($request){
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
        // Send notification to Admin
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new TicketCreatedNotification($ticket));
        }
    }
    public function update($request, $ticket){
        $originalStatus = $ticket->status;
        $originalAgent = $ticket->assigned_to;  
        $ticket->update($request->only('title', 'message', 'status', 'priority', 'assigned_to'));

        if ($originalStatus !== $ticket->status) {
            $user = User::findOrFail($ticket->user_id);
            $user->notify(new TicketStatusUpdatedNotification($ticket));
        }

        $ticket->categories()->sync($request->input('categories'));
        $ticket->labels()->sync($request->input('labels'));

        if($originalAgent !== $ticket->assigned_to){
            $user = User::findOrFail($ticket->assigned_to);
            $user->notify(new TicketAssignedNotification($ticket));
        }

        if (!is_null($request->input('attachments')[0])) {
            foreach ($request->input('attachments') as $file) {
                $ticket->addMediaFromDisk($file, 'public')->toMediaCollection('tickets_attachments');
            }
        }
    }
    public function delete($id){
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
    }
}