<?php

// app/Models/ConnexionUtilisateur.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnexionUtilisateur extends Model
{
    use HasFactory;

    protected $table = 'connexionsUtilisateurs';
    protected $primaryKey = 'idConnexionsUtilisateurs';
    public $timestamps = false;

    protected $fillable = [
        'idUtilisateur',
        'dateConnexion',
        'pointsGagne'
    ];

    protected $casts = [
        'dateConnexion' => 'datetime'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'idUtilisateur');
    }
}