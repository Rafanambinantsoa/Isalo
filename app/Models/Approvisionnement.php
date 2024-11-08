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

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }
}
