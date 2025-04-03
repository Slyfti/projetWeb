<?php

// app/Models/Evenement.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    protected $table = 'evenements';
    protected $primaryKey = 'idEvenements';

    protected $fillable = [
        'nom',
        'dateEvenements',
        'descriptionEvenements',
        'typeEvents',
        'equipeDomicile',
        'equipeExterieur',
        'prix',
        'Disponiblilite'
    ];
}