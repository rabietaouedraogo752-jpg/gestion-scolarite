<?php

namespace App\Imports;

use App\Models\Etudiant;
use App\Models\User;
use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class EtudiantsImport implements ToModel, WithHeadingRow
{
    private $created = [];

    public function getCreatedCredentials()
    {
        return $this->created;
    }

    public function model(array $row)
    {
        $r = array_change_key_case($row, CASE_LOWER);

        $name = $r['name'] ?? $r['nom'] ?? null;
        $email = $r['email'] ?? null;
        $matricule = $r['matricule'] ?? $r['ine'] ?? null;
        $date_naissance = $r['date_naissance'] ?? $r['date_de_naissance'] ?? $r['date de naissance'] ?? null;
        $lieu_naissance = $r['lieu_naissance'] ?? $r['lieu_de_naissance'] ?? $r['lieu de naissance'] ?? null;
        $genre = strtoupper($r['genre'] ?? $r['sex'] ?? null);
        $filiereName = $r['filiere'] ?? $r['filière'] ?? null;
        $niveauCode = $r['niveau'] ?? $r['code_niveau'] ?? null;
        $annee = $r['annee_academique'] ?? $r['annee'] ?? null;

        if (!$name || !$email) {
            return null;
        }

        return DB::transaction(function () use ($name, $email, $matricule, $date_naissance, $lieu_naissance, $genre, $filiereName, $niveauCode, $annee) {
            $user = User::firstWhere('email', $email);
            if (!$user) {
                $username = strtolower(str_replace(' ', '.', $name)) . '.' . Str::random(3);
                $plainPassword = Str::random(10);
                $user = User::create([
                    'name' => $name,
                    'username' => $username,
                    'email' => $email,
                    'password' => Hash::make($plainPassword),
                    'role' => 'etudiant',
                ]);
                $generatedPassword = $plainPassword;
                $this->created[] = ['email' => $email, 'username' => $username, 'password' => $plainPassword];
            } else {
                $generatedPassword = null;
            }

            $filiere = null;
            if ($filiereName) {
                $filiere = Filiere::firstOrCreate(['nom_filiere' => $filiereName]);
            }

            $niveau = null;
            if ($niveauCode) {
                $niveau = Niveau::updateOrCreate(['code_niveau' => $niveauCode], ['intitule' => $niveauCode]);
            }

            [$anneeDebut, $anneeFin] = ['',''];
            if ($annee && strpos($annee, '-') !== false) {
                [$anneeDebut, $anneeFin] = explode('-', $annee);
            }

            $etudiant = Etudiant::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'matricule' => $matricule ?? null,
                    'date_naissance' => $date_naissance ?? null,
                    'lieu_naissance' => $lieu_naissance ?? null,
                    'genre' => in_array($genre, ['M','F']) ? $genre : null,
                    'filiere_id' => $filiere ? $filiere->id : null,
                    'niveau_id' => $niveau ? $niveau->id : null,
                    'annee_debut' => $anneeDebut ? ($anneeDebut.'-09-01') : null,
                    'annee_fin' => $anneeFin ? ($anneeFin.'-08-31') : null,
                    'generated_password' => $generatedPassword ?? null,
                ]
            );

            return $etudiant;
        });
    }
}
