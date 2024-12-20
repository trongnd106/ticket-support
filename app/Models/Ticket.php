<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ticket extends Model implements HasMedia 
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use InteractsWithMedia;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'user_id',
        'assigned_to'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function categories(){
        return $this->belongsToMany(Category::class, 'category_ticket');
    }

    public function labels(){
        return $this->belongsToMany(Label::class,'label_ticket');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

}