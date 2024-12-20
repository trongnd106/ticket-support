<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'message',
        'user_id',
        'ticket_id',
    ];
    
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
