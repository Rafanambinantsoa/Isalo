<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chambre extends Model
{
    /** @use HasFactory<\Database\Factories\ChambreFactory> */
    use HasFactory;

    protected $fillable = [
        'Numero_chambre',
        'id_categorie_chambre',
        'Nombre_lits',
        'Type_lits',
        'Prix_Nuitée',
        'Etat_chambre',
    ];
}
