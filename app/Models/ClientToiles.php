<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientToiles extends Model
{
    /** @use HasFactory<\Database\Factories\ClientToilesFactory> */
    use HasFactory;

    protected $fillable = [
        'nom',
        'address',
        'cin',
        'preferences',
    ];
}
