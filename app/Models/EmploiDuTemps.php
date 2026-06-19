<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmploiDuTemps extends Model
{
    use HasFactory;

    protected $table = 'emplois_du_temps';

    protected $fillable = [
        'filiere_id',
        'niveau_id',
        'enseignant_id',
        'jour',
        'heure_debut',
        'heure_fin',
        'matiere',
        'salle',
        'enseignant',
    ];

    // Relations
    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);
    }

    public function enseignantModel()
    {
        return $this->belongsTo(Enseignant::class, 'enseignant_id');
    }
}
