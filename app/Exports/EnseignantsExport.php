<?php

namespace App\Exports;

use App\Models\Enseignant;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EnseignantsExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private ?string $grade = null)
    {
    }

    public function query()
    {
        return Enseignant::with('user')
            ->when($this->grade, function ($query) {
                $query->where('grade', $this->grade);
            })
            ->orderBy('id', 'desc');
    }

    public function headings(): array
    {
        return [
            'Nom',
            'Email',
            'Nom d\'utilisateur',
            'Mot de passe',
            'Matricule',
            'Grade',
            'Téléphone',
        ];
    }

    public function map($enseignant): array
    {
        return [
            $enseignant->user->name ?? '—',
            $enseignant->user->email ?? '—',
            $enseignant->user->username ?? '—',
            $enseignant->generated_password ?? '—',
            $enseignant->matricule_fonctionnaire ?? '—',
            $enseignant->grade ?? '—',
            $enseignant->telephone ?? '—',
        ];
    }
}
