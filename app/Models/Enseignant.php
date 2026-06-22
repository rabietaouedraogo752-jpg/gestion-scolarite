<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UfrInstitut;
class Enseignant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ufr_institut_id',
        'matricule_fonctionnaire',
        'grade',
        'telephone',
        'domaine_enseignement',
        'generated_password',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function emploisDuTemps()
    {
        return $this->hasMany(EmploiDuTemps::class);
    }
    /**
 * Obtenir le département (UFR) associé à l'enseignant.
 */
public function ufrInstitut()
{
    return $this->belongsTo(UfrInstitut::class, 'ufr_institut_id');
}
}
