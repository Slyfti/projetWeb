<?php
// app/Models/ObjetConnecte.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObjetConnecte extends Model
{
    protected $table = 'objetsConnectes';
    protected $primaryKey = 'idObjetsConnectes';
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'idCategorie',
        'descriptionObjetsConnectes',
        'etat',
        'mode',
        'connectivite',
        'niveauBatterie',
        'derniereInteraction',
        'puissance',
        'consommationActuelle',
        'dureeVieEstimee',
        'dateInstallation',
        'derniereMaintenance',
        'idZone'
    ];

    public function categorie()
    {
        return $this->belongsTo(CategorieObjet::class, 'idCategorie');
    }

    public function zone()
    {
        return $this->belongsTo(ZoneStade::class, 'idZone');
    }

    public function historiques()
    {
        return $this->hasMany(HistoriqueObjet::class, 'idObjetsConnectes');
    }
}