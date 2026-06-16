<?php

namespace App\Exports;

use App\Models\Etudiant;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EtudiantsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Etudiant::with('user', 'filiere', 'niveau')->orderBy('id', 'desc');
    }

    public function headings(): array
    {
        return [
            'Nom',
            'INE',
            'Email',
            'Nom d\'utilisateur',
            'Filière',
            'Niveau',
            'Année académique',
            'Genre',
        ];
    }

    public function map($etudiant): array
    {
        return [
            $etudiant->user->name ?? '—',
            $etudiant->matricule ?? '—',
            $etudiant->user->email ?? '—',
            $etudiant->user->username ?? '—',
            $etudiant->filiere->nom_filiere ?? '—',
            $etudiant->niveau->code_niveau ?? '—',
            $etudiant->annee_debut ? date('Y', strtotime($etudiant->annee_debut)).'-'.date('Y', strtotime($etudiant->annee_fin)) : '—',
            $etudiant->genre ?? '—',
        ];
    }
}
