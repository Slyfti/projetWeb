<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'sport_event_id',
        'ticket_type',
        'price',
        'quantity_available',
        'quantity_sold',
        'benefits'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'benefits' => 'array'
    ];

    public function sportEvent()
    {
        return $this->belongsTo(SportEvent::class);
    }
} 