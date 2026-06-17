<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matricule_fonctionnaire',
        'grade',
        'telephone',
        'generated_password',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
