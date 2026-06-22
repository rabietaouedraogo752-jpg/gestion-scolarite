<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UfrInstitut;
use App\Models\Filiere;
use App\Models\Information;
use App\Models\PendingInscription;
use App\Models\CalendrierEvaluation;
use App\Models\Enseignant;
use App\Models\EmploiDuTemps;
use App\Models\Vacation;

class DepartementDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function getChefDepartement()
    {
        $user = Auth::user();
        return UfrInstitut::where('user_id', $user->id)->first() 
            ?? UfrInstitut::where('chef_nom', $user->name)->first();
    }
    public function index()
    {
        $user = Auth::user();
        $departement = $this->getChefDepartement();

        if (!$departement) {
            return redirect()->route('login')->withErrors(['username' => "Aucun département lié à votre compte."]);
        }

        $filieres = Filiere::where('ufr_id', $departement->id)->get();
        $filiereIds = $filieres->pluck('id')->toArray();

        // Initialisation sécurisée de toutes les variables utilisées dans la vue
        $calendriersEvaluations = CalendrierEvaluation::whereIn('filiere_id', $filiereIds)->get();
        $inscriptionsEnAttente = 0; 
        $informations = Information::where('user_id', $user->id)->latest()->get();
        $vacations = Vacation::all();
        $enseignants = Enseignant::where('ufr_institut_id', $departement->id)->with('user')->get();
        
        // Variables de contrôle pour la vue Blade
        $vacationsEnabled = true; 

        $stats = [
            'enseignants' => Enseignant::where('ufr_institut_id', $departement->id)->count(),
            'cours'       => EmploiDuTemps::whereIn('filiere_id', $filiereIds)->count(),
            'vacations'   => 0,
            'alertes'     => $inscriptionsEnAttente,
        ];

        return view('departement.tableau_bord', compact(
            'departement', 'filieres', 'calendriersEvaluations', 
            'inscriptionsEnAttente', 'informations', 'vacations', 
            'enseignants', 'stats', 'vacationsEnabled'
        ));
    }

}