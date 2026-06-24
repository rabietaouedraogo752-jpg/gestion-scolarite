<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ressource extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont autorisés pour l'assignation massive.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titre',
        'chemin_fichier',
        'filiere_id',
        'niveau_id',
    ];

    /**
     * Relation avec la Filière
     */
    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'filiere_id');
    }

    /**
     * Relation avec le Niveau
     */
    public function niveau()
    {
        return $this->belongsTo(Niveau::class, 'niveau_id');
    }
}