<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use Illuminate\Support\Facades\Auth;

class EnseignantEmploiDuTempsController extends Controller
{
    /**
     * Affiche l'emploi du temps de l'enseignant connecté
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $enseignant = $user->enseignant;

        if (!$enseignant) {
            return redirect()->route('login')->with('error', 'Accès réservé aux enseignants.');
        }

        $emplois = $enseignant->emploisDuTemps()
            ->with(['filiere', 'niveau'])
            ->orderBy('jour')
            ->orderBy('heure_debut')
            ->get()
            ->groupBy('jour');

        $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'];
        
        return view('enseignant.emploi_du_temps', compact('enseignant', 'emplois', 'jours'));
    }
}
