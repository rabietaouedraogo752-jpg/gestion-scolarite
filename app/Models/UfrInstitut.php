<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UfrInstitut extends Model
{
    use HasFactory;

    protected $fillable = [
        'universite_id',
        'code',
        'nom',
    ];

    public function universite()
    {
        return $this->belongsTo(Universite::class);
    }

    public function filieres()
    {
        return $this->hasMany(Filiere::class, 'ufr_id');
    }
}
