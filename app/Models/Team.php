<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sport_type',
        'description',
        'logo',
        'home_city',
        'founded_year',
        'website',
        'social_media'
    ];

    protected $casts = [
        'social_media' => 'array'
    ];

} 