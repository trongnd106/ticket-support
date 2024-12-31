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
        $users = User::role('user');

        if ($request->has('search') && !empty($request->search)) {
            $users->where('email', 'like', '%' . $request->search . '%');
        }
    
        $users = $users->paginate(10);
    
        $usersWithTicketCount = $users->map(function ($user) {
            $ticketCount = Ticket::where('user_id', $user->id)->count();
            $unresolved = Ticket::where('user_id', $user->id)
                    ->where('status', '!=', 'resolved')
                    ->count();
            $user->ticket_count = $ticketCount;
            $user->unresolved_count = $unresolved;
            return $user;
        });

        return view('users.index', compact('usersWithTicketCount', 'users'));
    }

    public function destroy($id){
        $user = User::find($id)->delete();
        return redirect(route('users.index'));
    }
}
