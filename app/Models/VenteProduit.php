<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenteProduit extends Model
{
    use HasFactory;

    protected $fillable = [
        'vente_id',
        'produit_id',
        'quantite',
    ];

    public function vente()
    {
        return $this->belongsTo(Vente::class);
    }
}
