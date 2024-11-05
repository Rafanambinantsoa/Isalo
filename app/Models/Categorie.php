<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    /** @use HasFactory<\Database\Factories\CategorieFactory> */
    use HasFactory;

    protected $fillable = ['nom'];

    public function produits()
    {
        return $this->hasMany(Produit::class, 'categorie_id');
    }
}
