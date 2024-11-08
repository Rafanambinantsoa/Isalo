<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockToile extends Model
{
    /** @use HasFactory<\Database\Factories\StockToileFactory> */
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'quantite',
    ];
}
