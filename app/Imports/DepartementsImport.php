<?php

namespace App\Imports;

use App\Models\UfrInstitut;
use App\Models\Universite;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class DepartementsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $r = array_change_key_case($row, CASE_LOWER);

        $code = $r['code'] ?? null;
        $nom = $r['nom'] ?? null;
        $code_univ = $r['code_univ'] ?? $r['code universite'] ?? null;
        $nom_univ = $r['nom_universite'] ?? $r['nom universite'] ?? $r['universite'] ?? null;
        $ville = $r['ville'] ?? null;

        if (!$code || !$nom || !$code_univ) {
            return null;
        }

        return DB::transaction(function () use ($code, $nom, $code_univ, $nom_univ, $ville) {
            $universite = Universite::updateOrCreate(
                ['code_univ' => $code_univ],
                ['nom_universite' => $nom_univ ?? $code_univ, 'ville' => $ville ?? null]
            );

            $departement = UfrInstitut::updateOrCreate(
                ['code' => $code],
                ['universite_id' => $universite->id, 'nom' => $nom]
            );

            return $departement;
        });
    }
}
