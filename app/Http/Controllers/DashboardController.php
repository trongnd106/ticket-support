<?php

namespace App\Http\Controllers;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Label;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller {
    public function index()
    {
        $user = Auth::user();
        // dd($user);

        $totalTickets = 0;
        
        if ($user->hasRole('user')) {
            $totalTickets = Ticket::where('user_id', $user->id)->count();
        } else if($user->hasRole('admin|agent')){
            $totalTickets = Ticket::count();
        }
        $agents = User::role('agent')->count();
        $users = User::role('user')->count();
        $labels = Label::count();
        $categories = Category::count();

        return view('dashboard', compact('totalTickets', 'agents', 'users','labels','categories'));
    }
}