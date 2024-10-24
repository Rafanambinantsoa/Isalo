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
        'quantite',
        'prix',
        'fournisseur_id',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
}
