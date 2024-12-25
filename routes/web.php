<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;


Route::get('/', function () {
    return view(view: 'welcome');
});

Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('tickets', TicketController::class);
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/message/{ticket}', [MessageController::class, 'store'])->name('message.store');

    Route::middleware(\App\Http\Middleware\AdminAgentMiddleware::class)->group(function(){
        Route::get('/agents', [AgentController::class, 'index'])->name('agents.index');
    });

    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        // Route::resource('/agents', AgentController::class);
        Route::post('/agents', [AgentController::class,'store'])->name('agents.store');
        Route::delete('/agents/{agent}', [AgentController::class,'destroy'])->name('agents.destroy');
        Route::get('/agents/create', [AgentController::class, 'create'])->name('agents.create');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::resource('/labels', LabelController::class);
        Route::resource('/categories', CategoryController::class);
    });

    Route::middleware(\App\Http\Middleware\AgentMiddleware::class)->group(function () {
        // Route::get('/agents', [AgentController::class, 'index'])->name('agents.index');
        Route::get('/tickets-agents', [TicketController::class, 'agent'])->name('tickets.agent');
    });
    
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
});


require __DIR__.'/auth.php';
