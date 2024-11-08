<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approvisionnement extends Model
{
    use HasFactory;

    protected $fillable = [
        'fournisseur_id',
        'montant_approvisionnement',
    ];

    public function approvisionnement_produits()
    {
        return $this->hasMany(Approvisionnement_produit::class, 'approvisionnement_id');
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }
}
