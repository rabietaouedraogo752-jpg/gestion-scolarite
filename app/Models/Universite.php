<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Universite extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_univ',
        'nom_universite',
        'ville',
    ];

    public function ufrInstituts()
    {
        return $this->hasMany(UfrInstitut::class);
    }
}
