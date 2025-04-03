<?php

// app/Models/CategorieObjet.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorieObjet extends Model
{
    protected $table = 'categoriesObjets';
    protected $primaryKey = 'idCategoriesObjets';
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'descriptionCategoriesObjets'
    ];

    public function objetsConnectes()
    {
        return $this->hasMany(ObjetConnecte::class, 'idCategorie');
    }
}