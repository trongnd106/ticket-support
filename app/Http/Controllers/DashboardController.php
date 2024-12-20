<?php

namespace App\Http\Controllers;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller {
    public function index()
    {
        $user = Auth::user();
        // dd($user);

        $totalTickets = 0;
        
        if ($user->hasRole('user')) {
            $totalTickets = Ticket::where('user_id', $user->id)->count();
        } else if($user->hasRole('admin')){
            $totalTickets = Ticket::count();
        }
        
        return view('dashboard', compact('totalTickets'));
    }
}