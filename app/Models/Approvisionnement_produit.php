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
}