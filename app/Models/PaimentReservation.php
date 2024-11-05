<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaimentReservation extends Model
{
    /** @use HasFactory<\Database\Factories\PaimentReservationFactory> */
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'client_id',
        'montant_restant',
        'montant',
        'type',
    ];

    //Type  = [ penalite ou reservation ]
}
