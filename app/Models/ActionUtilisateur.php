<?php

// app/Models/ActionUtilisateur.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionUtilisateur extends Model
{
    protected $table = 'actionsUtilisateurs';
    protected $primaryKey = 'idActionsUtilisateurs';
    public $timestamps = false;

    protected $fillable = [
        'idUtilisateur',
        'typeAction',
        'entiteCible',
        'idCible',
        'pointsGagne'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'idUtilisateur');
    }
}