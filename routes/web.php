<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AgentController;


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

    Route::post('/message/{ticket}', [MessageController::class,'store'])->name('message.store');

    Route::get('/agents', [AgentController::class,'index'])->name('agents.index');
});

require __DIR__.'/auth.php';
