<?php

namespace App\Exports;

use App\Models\UfrInstitut;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DepartementsExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private ?string $universiteId = null)
    {
    }

    public function query()
    {
        return UfrInstitut::with('universite')
            ->when($this->universiteId, function ($query) {
                $query->where('universite_id', $this->universiteId);
            })
            ->orderBy('id', 'desc');
    }

    public function headings(): array
    {
        return [
            'Code',
            'Nom',
            'Code université',
            'Université',
            'Ville',
        ];
    }

    public function map($departement): array
    {
        return [
            $departement->code ?? '—',
            $departement->nom ?? '—',
            $departement->universite->code_univ ?? '—',
            $departement->universite->nom_universite ?? '—',
            $departement->universite->ville ?? '—',
        ];
    }
}
