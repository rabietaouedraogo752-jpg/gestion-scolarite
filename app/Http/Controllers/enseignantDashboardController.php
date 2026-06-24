<?php

namespace App\Http\Controllers;

use App\Models\Information;
use App\Models\EmploiDuTemps;
use App\Models\Filiere;          
use App\Models\Niveau;           
use App\Models\Ressource;
use App\Models\Vacation;
use App\Models\Enseignant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

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

    public function showRessources()
    {
        $filieres = Filiere::all();
        $niveaux = Niveau::all();

        // Récupérer toutes les ressources avec leurs relations
        $ressources = Ressource::with(['filiere', 'niveau'])->latest()->get();
        
        // Grouper les ressources par nom de filière pour l'affichage en cartes
        $ressourcesParGroupe = $ressources->groupBy(function($item) {
            return $item->filiere->nom_filiere ?? 'Général / Toutes Filières';
        });

        return view('enseignant.resource', compact('filieres', 'niveaux', 'ressourcesParGroupe', 'ressources'));
    }

    public function storeRessource(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'fichier' => 'required|file|mimes:pdf,doc,docx,zip,png,jpg,jpeg,xls,xlsx|max:10240', // Max 10 Mo
            'filiere_id' => 'nullable|exists:filieres,id',
            'niveau_id' => 'nullable|exists:niveaux,id',
        ]);

        if ($request->hasFile('fichier')) {
            // Sauvegarde physique dans storage/app/ressources_pedagogiques
            $path = $request->file('fichier')->store('ressources_pedagogiques');

            // Insertion en base de données
            Ressource::create([
                'titre' => $request->titre,
                'chemin_fichier' => $path,
                'filiere_id' => $request->filiere_id,
                'niveau_id' => $request->niveau_id,
            ]);

            return redirect()->back()->with('success', 'La ressource a été ajoutée avec succès !');
        }

        return redirect()->back()->with('error', 'Une erreur est survenue lors du transfert.');
    }

    /**
     * Modification d'une ressource existante
     */
    public function updateRessource(Request $request, $id)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'fichier' => 'nullable|file|mimes:pdf,doc,docx,zip,png,jpg,jpeg,xls,xlsx|max:10240',
            'filiere_id' => 'nullable|exists:filieres,id',
            'niveau_id' => 'nullable|exists:niveaux,id',
        ]);

        $ressource = Ressource::findOrFail($id);

        $ressource->titre = $request->titre;
        $ressource->filiere_id = $request->filiere_id;
        $ressource->niveau_id = $request->niveau_id;

        if ($request->hasFile('fichier')) {
            // Supprimer l'ancien fichier s'il existe
            if (Storage::exists($ressource->chemin_fichier)) {
                Storage::delete($ressource->chemin_fichier);
            }
            // Enregistrer le nouveau fichier
            $path = $request->file('fichier')->store('ressources_pedagogiques');
            $ressource->chemin_fichier = $path;
        }

        $ressource->save();

        return redirect()->route('enseignant.tableau_bord', ['tab' => 'ressources'])->with('success', 'La ressource a été modifiée avec succès !');
    }

    /**
     * Suppression d'une ressource
     */
    public function destroyRessource($id)
    {
        $ressource = Ressource::findOrFail($id);

        // Supprimer le fichier physique du stockage
        if (Storage::exists($ressource->chemin_fichier)) {
            Storage::delete($ressource->chemin_fichier);
        }

        $ressource->delete();

        return redirect()->route('enseignant.tableau_bord', ['tab' => 'ressources'])->with('success', 'La ressource a été supprimée avec succès !');
    }

    /**
     * Téléchargement sécurisé pour l'administrateur ou enseignant
     */
    public function downloadRessource($id)
    {
        $ressource = Ressource::findOrFail($id);

        if (Storage::exists($ressource->chemin_fichier)) {
            return Storage::download(
                $ressource->chemin_fichier, 
                $ressource->titre . '.' . pathinfo($ressource->chemin_fichier, PATHINFO_EXTENSION)
            );
        }

        return redirect()->back()->with('error', 'Le fichier physique n\'existe plus sur le serveur.');
    }

    /**
     * Gestion centralisée des onglets du tableau de bord.
     */
    public function tableau_bord(Request $request)
    {
        // 1. On récupère le paramètre ?tab=... (par défaut 'emploi')
        $tab = $request->get('tab', 'emploi');
        
        // 2. On récupère l'enseignant connecté
        $user = auth()->user();
        $enseignant = \App\Models\Enseignant::where('user_id', $user->id)->first(); 

        // 3. INITIALISATION de toutes les variables pour éviter les erreurs Blade
        $informations = [];
        $filieres = [];
        $niveaux = [];
        $ressourcesParGroupe = [];
        $etudiants = [];
        
        // Variables spécifiques requis par enseignant.emploi_du_temps
        $emplois = collect(); 
        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];

        // 4. CHARGEMENT DES DONNÉES SELON L'ONGLET ACTIF
        if ($tab === 'emploi') {
            if ($enseignant) {
                // On récupère les cours de cet enseignant et on les groupe par jour (en minuscules)
                $emploisRaw = \App\Models\EmploiDuTemps::where('enseignant_id', $enseignant->id)
                    ->orderBy('heure_debut')
                    ->get();
                
                $emplois = $emploisRaw->groupBy(function($item) {
                    return strtolower($item->jour);
                });
            }
        } 
        elseif ($tab === 'infos') {
            $informations = Information::latest()->get();
        } 
        elseif ($tab === 'ressources') {
            $filieres = Filiere::all(); 
            $niveaux = Niveau::all();
            
            $ressources = Ressource::with('niveau', 'filiere')->get();
            $ressourcesParGroupe = $ressources->groupBy(function($item) {
                return $item->filiere->nom_filiere ?? 'Général';
            });
        }
        elseif ($tab === 'notes') {
            // Logique future pour l'onglet des notes
        }

        // 5. ENVOI DE TOUTES LES VARIABLES À LA VUE
        return view('enseignant.tableau_bord', compact(
            'enseignant', 
            'tab', 
            'informations', 
            'filieres', 
            'niveaux', 
            'ressourcesParGroupe',
            'etudiants',
            'emplois',
            'jours'
        ))->with('activeTab', $tab);
    }
}