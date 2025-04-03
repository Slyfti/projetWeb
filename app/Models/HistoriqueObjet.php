<?php

// app/Models/HistoriqueObjet.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriqueObjet extends Model
{
    protected $table = 'historiqueObjets';
    protected $primaryKey = 'idHistoriqueObjets';
    public $timestamps = false;

    protected $fillable = [
        'idObjetsConnectes',
        'ancienEtat',
        'nouvelEtat',
        'idUtilisateur'
    ];

    public function objetConnecte()
    {
        return $this->belongsTo(ObjetConnecte::class, 'idObjetsConnectes');
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'idUtilisateur');
    }
}
