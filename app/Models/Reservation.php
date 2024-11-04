<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory;

    protected $fillable = [
        'Chambre_id',
        'Client_id',
        'date_arrive',
        'date_depart',
        'statut',
        'avance_requise',
        'is_avance_paid',
        'prix_total',
    ];
}
