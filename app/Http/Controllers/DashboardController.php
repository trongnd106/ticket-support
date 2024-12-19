<?php

namespace App\Http\Controllers;
use App\Models\Ticket;

class DashboardController extends Controller {
    public function index()
    {
        $totalTickets = Ticket::count();
        // $openTickets = Ticket::opened()->count();
        // $closedTickets = Ticket::closed()->count();

        return view('dashboard', compact('totalTickets'));
    }
}