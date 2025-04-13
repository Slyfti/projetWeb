<?php
// app/Models/Service.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';
    protected $primaryKey = 'idservices';
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'idCategorie',
        'descriptionServices',
        'image',
        'estActif'
    ];

    public function categorie()
    {
        return $this->belongsTo(CategorieService::class, 'idCategorie');
    }
}