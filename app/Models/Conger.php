<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conger extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type_conge_id', 'date_debut', 'date_fin', 'nombre_jours', 'statut', 'motif', 'commentaire_admin'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function typeConge()
    {
        return $this->belongsTo(TypeConge::class);
    }
}
