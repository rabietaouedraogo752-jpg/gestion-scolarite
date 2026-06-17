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
        'jour',
        'heure_debut',
        'heure_fin',
        'matiere',
        'salle',
        'enseignant',
    ];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }
}
