<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    protected $table = 'informations';

    protected $fillable = [
        'user_id',
        'titre',
        'contenu',
        'visibilite',
        'categorie',
        'fichier',
    ];

    public function auteur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Informations visibles pour un utilisateur donné :
     * - toutes les informations publiques ;
     * - les informations privées dont l'utilisateur est l'auteur.
     */
    public function scopeVisiblePour(Builder $query, ?User $user): Builder
    {
        return $query->where(function (Builder $q) use ($user) {
            $q->where('visibilite', 'public');

            if ($user) {
                $q->orWhere('user_id', $user->id);
            }
        });
    }
}
