<?php

namespace App\Imports;

use App\Models\Enseignant;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class EnseignantsImport implements ToModel, WithHeadingRow
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
        $matricule = $r['matricule_fonctionnaire'] ?? $r['matricule'] ?? null;
        $grade = $r['grade'] ?? null;
        $telephone = $r['telephone'] ?? $r['tel'] ?? null;

        if (!$name || !$email) {
            return null;
        }

        return DB::transaction(function () use ($name, $email, $matricule, $grade, $telephone) {
            $user = User::firstWhere('email', $email);
            if (!$user) {
                $username = strtolower(str_replace(' ', '.', $name)) . '.' . Str::random(3);
                $plainPassword = Str::random(10);
                $user = User::create([
                    'name' => $name,
                    'username' => $username,
                    'email' => $email,
                    'password' => Hash::make($plainPassword),
                    'role' => 'enseignant',
                ]);
                $generatedPassword = $plainPassword;
                $this->created[] = ['email' => $email, 'username' => $username, 'password' => $plainPassword];
            } else {
                $generatedPassword = null;
            }

            $enseignant = Enseignant::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'matricule_fonctionnaire' => $matricule ?? null,
                    'grade' => $grade ?? null,
                    'telephone' => $telephone ?? null,
                    'generated_password' => $generatedPassword ?? null,
                ]
            );

            return $enseignant;
        });
    }
}
