<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use App\Models\Information;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class EnseignantEmploiDuTempsController extends Controller
{
    /**
     * Affiche le tableau de bord complet de l'enseignant connecté
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        // Récupérer le profil enseignant via la relation ou via query
        $enseignant = $user->enseignant ?? Enseignant::where('user_id', $user->id)->first();

        if (!$enseignant) {
            return redirect()->route('login')->with('error', 'Accès réservé aux enseignants.');
        }

        // 1. Récupération de l'emploi du temps
        $emplois = $enseignant->emploisDuTemps()
            ->with(['filiere', 'niveau'])
            ->orderBy('jour')
            ->orderBy('heure_debut')
            ->get()
            ->groupBy('jour');

        $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'];

        // 2. RÉCUPÉRATION DES ANNONCES DU CHEF DE DÉPARTEMENT
                // 2. RÉCUPÉRATION DES ANNONCES DU CHEF DE DÉPARTEMENT
        $informations = Schema::hasTable('informations')
            ? Information::whereIn('visibilite', ['public', 'enseignant'])
                ->latest()
                ->get()
            : collect();

        // 3. Renvoi vers la vue principale du Tableau de bord
        return view('enseignant.tableau_bord', compact('enseignant', 'emplois', 'jours', 'informations', 'user'));
    }
}
