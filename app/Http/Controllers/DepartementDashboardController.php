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
     * Méthode d'aide privée pour récupérer le département du chef connecté
     * Évite la duplication de code et centralise la logique de secours.
     */
    private function getChefDepartement()
    {
        $user = Auth::user();
        
        return UfrInstitut::where('user_id', $user->id)->first() 
            ?? UfrInstitut::where('chef_nom', $user->name)->first();
    }

    /**
     * Affichage du tableau de bord personnel filtré
     */
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

        // 3. RECUPERATION DES VACATIONS LIEES 
        $vacations = \App\Models\Vacation::whereIn('filiere_id', $filiereIds)->get();

        // Variables de configuration pour la vue Blade
        $vacationsEnabled = true; 

        // 4. RÉCUPÉRER LES ENSEIGNANTS DE CE DÉPARTEMENT UNIQUEMENT
        // Requis pour l'onglet "Enseignants" pour qu'il n'affiche pas la liste globale
        $enseignants = \App\Models\Enseignant::where('ufr_institut_id', $departement->id)->with('user')->get();

        // 5. STATISTIQUES FILTRÉES SECTORIELLES
        $stats = [
            'enseignants' => $enseignants->count(), // <-- CORRIGÉ : Ne compte que les enseignants de SON département
            'cours'       => 0, // À brancher sur votre modèle de cours filtré par filiereIds si disponible
            'vacations'   => $vacations->count(), 
            'alertes'     => $inscriptionsEnAttente, // <-- OPTIMISÉ : Affiche le nombre de vraies inscriptions en attente à traiter
        ];

        // 6. Envoi de l'intégralité des variables filtrées à la vue
        return view('departement.tableau_bord', compact(
            'departement', 
            'filieres', 
            'calendriersEvaluations', 
            'inscriptionsEnAttente', 
            'informations',
            'vacations',         
            'vacationsEnabled',  
            'enseignants',       // <-- AJOUTÉ : Pour alimenter la liste de l'onglet Enseignants
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

        $departement = $this->getChefDepartement();

        if (!$departement) {
            return redirect()->back()->with('error', 'Action impossible : Aucun département lié.');
        }

        Filiere::create([
            'nom_filiere' => $data['nom_filiere'],
            'ufr_id'      => $departement->id, 
        ]);

        return redirect()->back()->with('success', 'La filière a été ajoutée à votre département avec succès !');
    }

    /**
     * Enregistrer un calendrier d'évaluation (Sécurisé)
     */
    public function storeCalendrierEvaluation(Request $request)
    {
        $departement = $this->getChefDepartement();

        if (!$departement) {
            return redirect()->back()->with('error', 'Action impossible : Aucun département lié.');
        }

        // Récupérer les IDs des filières autorisées pour ce chef de département
        $filiereIds = Filiere::where('ufr_id', $departement->id)->pluck('id')->toArray();

        // Validation stricte : la filière doit exister ET appartenir au département du chef
        $data = $request->validate([
            'filiere_id' => 'required|in:' . implode(',', $filiereIds),
            'titre'      => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin'   => 'required|date|after_or_equal:date_debut',
        ], [
            'filiere_id.in' => 'La filière sélectionnée ne fait pas partie de votre département.',
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
            'user_id'    => Auth::id(), 
        ]);

        return redirect()->back()->with('success', 'Votre annonce a été publiée.');
    }
}