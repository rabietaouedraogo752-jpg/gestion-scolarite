<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Etudiant;
use App\Models\Enseignant;
use App\Models\EmploiDuTemps;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un étudiant de test
        if (!User::where('email', 'etudiant@test.com')->exists()) {
            $userEtudiant = User::create([
                'name' => 'Etudiant Test',
                'username' => 'etudiant_test',
                'email' => 'etudiant@test.com',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
            ]);

            Etudiant::create([
                'user_id' => $userEtudiant->id,
                'matricule' => 'ETU001',
                'date_naissance' => '2004-01-15',
                'lieu_naissance' => 'Ouagadougou',
                'genre' => 'M',
                'filiere_id' => 1, // Informatique
                'niveau_id' => 1,  // Licence 1
            ]);
        }

        // Créer un enseignant de test
        if (!User::where('email', 'enseignant@test.com')->exists()) {
            $userEnseignant = User::create([
                'name' => 'Pr. Enseignant Test',
                'username' => 'enseignant_test',
                'email' => 'enseignant@test.com',
                'password' => Hash::make('password'),
                'role' => 'enseignant',
            ]);

            $enseignant = Enseignant::create([
                'user_id' => $userEnseignant->id,
                'matricule_fonctionnaire' => 'ENS001',
                'grade' => 'MC',
                'telephone' => '+226 70 00 00 00',
            ]);

            // Créer des emplois du temps de test pour le lundi
            EmploiDuTemps::create([
                'filiere_id' => 1,        // Informatique
                'niveau_id' => 1,         // Licence 1
                'enseignant_id' => $enseignant->id,
                'jour' => 'lundi',
                'heure_debut' => '08:00:00',
                'heure_fin' => '10:00:00',
                'matiere' => 'Algorithmes et Structures de Données',
                'salle' => 'A101',
                'enseignant' => $userEnseignant->name,
            ]);

            EmploiDuTemps::create([
                'filiere_id' => 1,
                'niveau_id' => 1,
                'enseignant_id' => $enseignant->id,
                'jour' => 'lundi',
                'heure_debut' => '10:00:00',
                'heure_fin' => '12:00:00',
                'matiere' => 'Programmation en Python',
                'salle' => 'A102',
                'enseignant' => $userEnseignant->name,
            ]);

            // Emploi du temps pour mardi
            EmploiDuTemps::create([
                'filiere_id' => 1,
                'niveau_id' => 1,
                'enseignant_id' => $enseignant->id,
                'jour' => 'mardi',
                'heure_debut' => '14:00:00',
                'heure_fin' => '16:00:00',
                'matiere' => 'Mathématiques Discrètes',
                'salle' => 'A103',
                'enseignant' => $userEnseignant->name,
            ]);

            // Emploi du temps pour mercredi
            EmploiDuTemps::create([
                'filiere_id' => 1,
                'niveau_id' => 1,
                'enseignant_id' => $enseignant->id,
                'jour' => 'mercredi',
                'heure_debut' => '09:00:00',
                'heure_fin' => '11:00:00',
                'matiere' => 'Bases de Données',
                'salle' => 'A104',
                'enseignant' => $userEnseignant->name,
            ]);
        }
    }
}
