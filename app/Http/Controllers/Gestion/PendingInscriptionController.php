<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\PendingInscription;
use App\Models\User;
use App\Models\Etudiant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PendingInscriptionController extends Controller
{
    /**
     * Liste les inscriptions en attente
     */
    public function index()
    {
        $inscriptions = PendingInscription::where('status', 'en_attente')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('gestion.inscriptions_en_attente', compact('inscriptions'));
    }

    /**
     * Affiche les détails d'une inscription
     */
    public function show(PendingInscription $inscription)
    {
        return view('gestion.inscription_details', compact('inscription'));
    }

    /**
     * Approuve une inscription et crée le compte utilisateur
     */
    public function approve(Request $request, PendingInscription $inscription)
    {
        // Vérification que l'inscription est en attente
        if ($inscription->status !== 'en_attente') {
            return back()->with('error', 'Cette inscription a déjà été traitée.');
        }

        try {
            // Vérifier qu'aucun utilisateur n'existe déjà avec ce courriel
            if (User::where('email', $inscription->email)->exists()) {
                return back()->with('error', 'Un utilisateur existe déjà avec cette adresse email. Veuillez vérifier l’inscription avant de l’approuver.');
            }

            // Génération des identifiants
            $username = $this->generateUsername($inscription->email);
            $password = Str::random(10);

            // Création du compte utilisateur
            $user = User::create([
                'name' => $inscription->prenom . ' ' . $inscription->nom,
                'email' => $inscription->email,
                'username' => $username,
                'password' => Hash::make($password),
                'role' => 'etudiant',
            ]);

            // Création du profil étudiant
            Etudiant::create([
                'user_id' => $user->id,
                'nom_prenom' => trim($inscription->prenom . ' ' . $inscription->nom),
                'nom' => $inscription->nom,
                'prenom' => $inscription->prenom,
                'ine' => $this->generateINE(),
                'date_naissance' => $inscription->date_naissance,
                'telephone' => $inscription->telephone,
                'generated_password' => $password,
            ]);

            // Mise à jour du statut de l'inscription
            $inscription->update([
                'status' => 'approuvee',
            ]);

            // Redirection avec les identifiants
            return back()->with('success', 'Inscription approuvée avec succès!')
                        ->with('new_credentials', [
                            'email' => $user->email,
                            'username' => $username,
                            'password' => $password,
                        ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création du compte: ' . $e->getMessage());
        }
    }

    /**
     * Rejette une inscription
     */
    public function reject(Request $request, PendingInscription $inscription)
    {
        $request->validate([
            'raison_rejet' => 'required|string|max:500',
        ]);

        // Vérification que l'inscription est en attente
        if ($inscription->status !== 'en_attente') {
            return back()->with('error', 'Cette inscription a déjà été traitée.');
        }

        // Mise à jour du statut
        $inscription->update([
            'status' => 'rejetee',
            'raison_rejet' => $request->raison_rejet,
        ]);

        return back()->with('success', 'Inscription rejetée avec succès!');
    }

    /**
     * Génère un nom d'utilisateur unique
     */
    private function generateUsername($email)
    {
        $baseUsername = strtok($email, '@');
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * Génère un INE unique
     */
    private function generateINE()
    {
        do {
            $ine = 'INE' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (Etudiant::where('ine', $ine)->exists());

        return $ine;
    }
}
