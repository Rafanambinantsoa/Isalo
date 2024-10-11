<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class typeConger extends Model
{
    use HasFactory;

    // Les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'nom',
        'description',
        'libelle'
    ];

    /**
     * Relation avec le modèle Congé
     * 
     * Un type de congé peut être associé à plusieurs congés.
     */
    public function conges()
    {
        return $this->hasMany(Conger::class, 'type_conges_id');
    }
}
