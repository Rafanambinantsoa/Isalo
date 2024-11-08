<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historique_approvisionnements_toiles extends Model
{
    use HasFactory;

    protected $fillable = [
        'approvi_id',
        'produit_id',
        'quantite',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
