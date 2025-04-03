<?php

// app/Models/ZoneStade.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZoneStade extends Model
{
    protected $table = 'zonesStade';
    protected $primaryKey = 'idZonesStade';
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'descriptionZonesStade',
        'capacite'
    ];

    public function objetsConnectes()
    {
        return $this->hasMany(ObjetConnecte::class, 'idZone');
    }
}