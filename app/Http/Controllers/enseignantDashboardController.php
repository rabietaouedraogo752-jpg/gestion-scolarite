<?php

namespace App\Http\Controllers;

use App\Models\Information;
use App\Models\EmploiDuTemps;
use App\Models\Vacation;
use App\Models\Enseignant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class EnseignantDashboardController extends Controller
{
    /**
     * Affiche le tableau de bord de l'enseignant avec les informations filtrées.
     */
    public function index()
    {
        // 1. Sécurité : Récupérer le profil enseignant de l'utilisateur connecté
        $user = Auth::user();
        $enseignant = Enseignant::where('user_id', $user->id)->first();

        // 2. Calculer des statistiques rapides pour l'accueil de l'enseignant
        $stats = [
            'mes_cours' => $enseignant ? EmploiDuTemps::where('enseignant_id', $enseignant->id)->count() : 0,
            'mes_vacations' => ($enseignant && Schema::hasTable('vacations')) 
                ? Vacation::where('enseignant_id', $enseignant->id)->count() 
                : 0,
        ];

        // 3. RÉCUPÉRATION DES INFORMATIONS DU CHEF DE DÉPARTEMENT
        // On récupère uniquement les infos globales (public) et celles destinées aux profs (enseignant)
        $informations = Schema::hasTable('informations')
            ? Information::whereIn('visibilite', ['public', 'enseignant'])
                ->with('user') // Charge l'expéditeur (le chef de dépt)
                ->orderBy('created_at', 'desc')
                ->get()
            : collect();

        // 4. Renvoi vers la vue avec toutes les données
        return view('enseignant.tableau_bord', [
            'stats' => $stats,
            'informations' => $informations,
            'enseignant' => $enseignant,
            'user' => $user
        ]);
    }
}
