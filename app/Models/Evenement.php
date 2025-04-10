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
        'Disponiblilite',
        'lieu',
        'meteo',
        'ligue',
        'consignes_securite',
        'activites_autour',
        'logo_equipe_domicile',
        'logo_equipe_exterieur',
        'resultat'
    ];

    protected $casts = [
        'dateEvenements' => 'datetime',
        'prix' => 'float',
        'Disponiblilite' => 'integer'
    ];
}