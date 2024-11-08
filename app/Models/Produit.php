<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    /** @use HasFactory<\Database\Factories\ProduitFactory> */
    use HasFactory;

    protected $fillable = [
        'nom',
        'categorie_id',
        'prix',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function Historique_approvisionnements_toiles()
    {
        return $this->hasMany(Historique_approvisionnements_toiles::class);
    }
}
