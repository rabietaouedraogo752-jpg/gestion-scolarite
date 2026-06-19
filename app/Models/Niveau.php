<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Niveau extends Model
{
    use HasFactory;

    protected $fillable = ['code_niveau', 'intitule'];

    public function etudiants()
    {
        return $this->hasMany(Etudiant::class);
    }

    public function emploisDuTemps()
    {
        return $this->hasMany(EmploiDuTemps::class);
    }

    public function filieres()
    {
        return $this->belongsToMany(Filiere::class, 'filiere_niveau')->withTimestamps();
    }
}
