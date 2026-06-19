<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;

    protected $fillable = ['ufr_id', 'nom_filiere'];

    public function etudiants()
    {
        return $this->hasMany(Etudiant::class);
    }

    public function emploisDuTemps()
    {
        return $this->hasMany(EmploiDuTemps::class);
    }

    public function niveaux()
    {
        return $this->belongsToMany(Niveau::class, 'filiere_niveau')->withTimestamps();
    }
}
