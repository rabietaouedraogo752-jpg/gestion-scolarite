<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    // Indique explicitement le nom de la table plurielle gérée par votre migration
    protected $table = 'informations';

    // Autorise le remplissage de ces colonnes lors de la création
    protected $fillable = [
        'titre', 
        'contenu', 
        'visibilite', 
        'cible', 
        'user_id'
    ];

    /**
     * Relation pour récupérer l'auteur de l'annonce
     */
    public function auteur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
