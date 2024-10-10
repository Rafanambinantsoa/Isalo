<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'date_naiss',
        'num_cin',
        'email',
        'contact',
        'situation_mat',
        'nombre_enf',
        'date_embauche',
        'numero_cnaps',
        'numero_omsi',
        'banque',
        'num_compte_bancaire',
        'salaires_brut',
        'photo',
        'is_employe',
        'poste_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function fichiers(){
        return $this->hasMany(Fichier::class);
    }

    public function postes(){
        return $this->belongsTo(Poste::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
