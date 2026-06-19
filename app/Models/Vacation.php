<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    use HasFactory;

    protected $fillable = [
        'enseignant_id',
        'matiere',
        'nombre_heures',
        'statut',
        'periode',
    ];

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);
    }
}
