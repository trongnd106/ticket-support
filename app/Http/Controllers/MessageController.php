<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CommentEmailNotification;
use App\Http\Requests\MessageRequest;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(MessageRequest $request, Ticket $ticket){
        $message = $ticket->messages()->create(
            $request->validated() + ['user_id' => Auth::user()->id]
        );

        $users = $ticket->messages()
            ->pluck('user_id')
            ->push($ticket->user_id)
            ->filter(fn ($user) => $user != Auth::user()->id)
            ->unique();

        Notification::send(User::findMany($users), 
                           new CommentEmailNotification($message));

        return to_route('tickets.show', $ticket);
    }
}
