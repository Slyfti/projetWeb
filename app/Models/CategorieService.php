<?php

// app/Models/CategorieService.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorieService extends Model
{
    protected $table = 'categoriesServices';
    protected $primaryKey = 'idCategoriesServices';
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'descriptionCategoriesServices'
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'idCategorie');
    }
}