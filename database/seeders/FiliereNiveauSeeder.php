<?php

namespace Database\Seeders;

use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FiliereNiveauSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les niveaux
        $niveau1 = Niveau::create([
            'code_niveau' => 'L1',
            'intitule' => 'Licence 1'
        ]);

        $niveau2 = Niveau::create([
            'code_niveau' => 'L2',
            'intitule' => 'Licence 2'
        ]);

        $niveau3 = Niveau::create([
            'code_niveau' => 'L3',
            'intitule' => 'Licence 3'
        ]);

        // Créer les filières
        $informatique = Filiere::create([
            'ufr_id' => 1,
            'nom_filiere' => 'Informatique'
        ]);

        $mathematique = Filiere::create([
            'ufr_id' => 1,
            'nom_filiere' => 'Mathématiques'
        ]);

        $physique = Filiere::create([
            'ufr_id' => 1,
            'nom_filiere' => 'Physique'
        ]);

        // Associer les niveaux aux filières
        $informatique->niveaux()->attach([$niveau1->id, $niveau2->id, $niveau3->id]);
        $mathematique->niveaux()->attach([$niveau1->id, $niveau2->id, $niveau3->id]);
        $physique->niveaux()->attach([$niveau1->id, $niveau2->id, $niveau3->id]);
    }
}
