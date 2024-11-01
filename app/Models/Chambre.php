<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chambre extends Model
{
    /** @use HasFactory<\Database\Factories\ChambreFactory> */
    use HasFactory;

    protected $fillable = [
        'numero_chambre',
        'id_categorie_chambre',
        'nombre_lits',
        'type_lits',
        'prix_nuitee',
        'etat_chambre',
    ];
}
