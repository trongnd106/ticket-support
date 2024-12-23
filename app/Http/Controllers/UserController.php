<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ticket;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function index(Request $request)
    {
        $usersWithTicketCount = $this->userService->getUsersWithTicketCounts();

        return view('users.index', compact('usersWithTicketCount'));
    }
}
