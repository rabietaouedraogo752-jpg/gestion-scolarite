<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UfrInstitut;
use App\Models\Filiere;

use App\Models\Information;
use App\Models\PendingInscription;
use App\Models\CalendrierEvaluation;

class DepartementDashboardController extends Controller
{
    public function __construct()
    {
        // Sécurité : obliger la connexion pour toutes les actions
        $this->middleware('auth');
    }

    /**
     * Affichage du tableau de bord personnel filtré
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Trouver le département UNIQUE de ce chef connecté
        $departement = UfrInstitut::where('user_id', $user->id)->first();

        // Secours pour les anciens départements
        if (!$departement) {
            $departement = UfrInstitut::where('chef_nom', $user->name)->first();
        }

        // Si aucun département n'est trouvé, on évite le crash
        if (!$departement) {
            return redirect()->route('login')->withErrors([
                'username' => "Aucun département n'est lié à votre compte d'utilisateur."
            ]);
        }

        // 2. FILTRER LES DONNÉES
        // On récupère uniquement les filières de ce département (clé : ufr_id)
        $filieres = Filiere::where('ufr_id', $departement->id)->get();
        
        // On isole les identifiants de ces filières
        $filiereIds = $filieres->pluck('id');

        // Évaluations liées uniquement aux filières de ce département
        $calendriersEvaluations = CalendrierEvaluation::whereIn('filiere_id', $filiereIds)->get();

        // Inscriptions en attente sur ces filières uniquement
        $inscriptionsEnAttente = PendingInscription::whereIn('filiere_id', $filiereIds)
            ->where('status', 'en_attente')
            ->count();

        // Annonces publiées par ce chef
        $informations = Information::where('user_id', $user->id)->latest()->get();

        // 3. RECUPERATION DES VACATIONS LIEES (Requis pour la ligne 501 de la vue)
        // Récupère les vacations liées aux filières de ce département
        $vacations = \App\Models\Vacation::whereIn('filiere_id', $filiereIds)->get();

        // Variables de configuration pour la vue Blade
        $vacationsEnabled = true; // Active les fonctionnalités de vacations sur le template

        // Statistiques globales du tableau de bord
        $stats = [
            'enseignants' => \App\Models\Enseignant::count(), 
            'cours'       => 0,
            'vacations'   => $vacations->count(), // Utilise le décompte de notre collection filtrée
            'alertes'     => $informations->count(), 
        ];

        // 4. Envoi de l'intégralité des variables à la vue
        return view('departement.tableau_bord', compact(
            'departement', 
            'filieres', 
            'calendriersEvaluations', 
            'inscriptionsEnAttente', 
            'informations',
            'vacations',         // <-- Ajouté pour corriger la ligne 501
            'vacationsEnabled',  // <-- Ajouté pour corriger la ligne 500
            'stats'
        ));
    }
    /**
     * Enregistrer une nouvelle filière liée automatiquement à ce département
     */
    public function storeFiliere(Request $request)
    {
        $data = $request->validate([
            'nom_filiere' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $departement = UfrInstitut::where('user_id', $user->id)->first() 
            ?? UfrInstitut::where('chef_nom', $user->name)->first();

        if (!$departement) {
            return redirect()->back()->with('error', 'Action impossible : Aucun département lié.');
        }

        Filiere::create([
            'nom_filiere' => $data['nom_filiere'],
            'ufr_id'      => $departement->id, // Liaison automatique et sécurisée
        ]);

        return redirect()->back()->with('success', 'La filière a été ajoutée à votre département avec succès !');
    }

    /**
     * Enregistrer un calendrier d'évaluation
     */
    public function storeCalendrierEvaluation(Request $request)
    {
        // Adaptez les règles de validation aux colonnes réelles de votre table 'calendriers_evaluations'
        $data = $request->validate([
            'filiere_id' => 'required|exists:filieres,id',
            'titre'      => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin'   => 'required|date|after_or_equal:date_debut',
        ]);

       CalendrierEvaluation::create($data);

        return redirect()->back()->with('success', 'Le calendrier d\'évaluation a été programmé.');
    }

    /**
     * Publier une annonce depuis le tableau de bord du département
     */
    public function storeInformation(Request $request)
    {
        $data = $request->validate([
            'titre'      => 'required|string|max:255',
            'contenu'    => 'required|string',
            'visibilite' => 'required|in:public,etudiant,enseignant,administration',
        ]);

        Information::create([
            'titre'      => $data['titre'],
            'contenu'    => $data['contenu'],
            'visibilite' => $data['visibilite'],
            'user_id'    => Auth::id(), // Lie l'annonce à l'utilisateur connecté
        ]);

        return redirect()->back()->with('success', 'Votre annonce a été publiée.');
    }
}