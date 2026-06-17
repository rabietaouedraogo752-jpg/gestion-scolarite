<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingInscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'ine',
        'date_naissance',
        'telephone',
        'status',
        'raison_rejet',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];
}
