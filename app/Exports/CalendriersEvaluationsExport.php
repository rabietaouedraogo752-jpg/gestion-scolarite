<?php

namespace App\Exports;

use App\Models\CalendrierEvaluation;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CalendriersEvaluationsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return CalendrierEvaluation::with(['filiere', 'niveau'])->orderBy('date_debut', 'desc');
    }

    public function headings(): array
    {
        return [
            'Intitulé',
            'Filière',
            'Niveau',
            'Type',
            'Début',
            'Fin',
            'Description',
        ];
    }

    public function map($calendrier): array
    {
        return [
            $calendrier->intitule,
            $calendrier->filiere->nom_filiere ?? '—',
            $calendrier->niveau->code_niveau ?? '—',
            $calendrier->type,
            $calendrier->date_debut?->format('d/m/Y'),
            $calendrier->date_fin?->format('d/m/Y'),
            $calendrier->description ?? '',
        ];
    }
}
