<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    /** @use HasFactory<\Database\Factories\VenteFactory> */
    use HasFactory;

    protected $fillable = [
        'montant',
        'mode_paiment',
        'date_paiement',
    ];

    public function venteProduits()
    {
        return $this->hasMany(VenteProduit::class);
    }
}
