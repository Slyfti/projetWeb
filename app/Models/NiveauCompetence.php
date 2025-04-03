<?php

// app/Models/NiveauCompetence.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NiveauCompetence extends Model
{
    protected $table = 'niveauxCompetences';
    protected $primaryKey = 'idNiveauxCompetences';
    public $timestamps = false;

    protected $fillable = [
        'niveau',
        'pointsRequis',
        'descriptionNiveauxCompetences'
    ];
}