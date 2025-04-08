<?php
// app/Models/ObjetConnecte.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    protected $casts = [
        'niveauBatterie' => 'float',
        'puissance' => 'float',
        'consommationActuelle' => 'float',
        'dureeVieEstimee' => 'float',
        'derniereInteraction' => 'datetime',
        'dateInstallation' => 'date',
        'derniereMaintenance' => 'date'
    ];

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(CategorieObjet::class, 'idCategorie', 'idCategoriesObjets');
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(ZoneStade::class, 'idZone', 'idZonesStade');
    }

    public function historiques()
    {
        return $this->hasMany(HistoriqueObjet::class, 'idObjetsConnectes');
    }
}