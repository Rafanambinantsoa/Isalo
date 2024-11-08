<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approvisionnement_produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'approvisionnement_id',
        'produit_id',
        'quantite',
    ];

    public function approvisionnement()
    {
        return $this->belongsTo(Approvisionnement::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
