<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieChambre extends Model
{
    /** @use HasFactory<\Database\Factories\CategorieChambreFactory> */
    use HasFactory;
    protected $fillable = [
        'type',
    ];
}
