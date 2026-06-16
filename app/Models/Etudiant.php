<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matricule',
        'date_naissance',
        'lieu_naissance',
        'genre',
        'filiere_id',
        'niveau_id',
        'annee_debut',
        'annee_fin',
        'generated_password',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function filiere()
    {
        return $this->belongsTo(\App\Models\Filiere::class);
    }

    public function niveau()
    {
        return $this->belongsTo(\App\Models\Niveau::class);
    }
}
