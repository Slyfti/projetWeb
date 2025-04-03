<?php

// app/Models/ConnexionUtilisateur.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConnexionUtilisateur extends Model
{
    protected $table = 'connexionsUtilisateurs';
    protected $primaryKey = 'idConnexionsUtilisateurs';
    public $timestamps = false;

    protected $fillable = [
        'idUtilisateur',
        'pointsGagne'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'idUtilisateur');
    }
}