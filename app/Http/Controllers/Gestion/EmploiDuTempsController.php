<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use Illuminate\Http\Request;

class EmploiDuTempsController extends Controller
{
    /**
     * Affiche la liste globale ou l'index des emplois du temps
     */
    public function index()
    {
        $filieres = Filiere::all();
        return view('gestion.emploi_du_temps.index', compact('filieres'));
    }

    /**
     * Affiche le formulaire de création d'un cours
     */
    /**
     * Affiche le formulaire de création d'un cours
     */
    public function create($filiere_id, $niveau_id)
    {
        // 1. On récupère la filière concernée (ex : Informatique)
        $filiere = Filiere::findOrFail($filiere_id);

        // 2. CORRECTION : On récupère TOUS les niveaux de la base de données
        // Assure-toi d'avoir importé le modèle en haut du fichier : use App\Models\Niveau;
        // Si ton modèle s'appelle autrement (ex: NiveauScolaire), ajuste le nom ici.
        $niveaux = \App\Models\Niveau::all(); 

        // 3. On envoie tout à la vue
        return view('gestion.emploi_du_temps.create', compact('filiere', 'niveaux', 'niveau_id'));
    }

    /**
     * Enregistre le cours dans la base de données
     * Le paramètre $filiere_id est injecté automatiquement depuis l'URL {filiere}
     */
    public function store(Request $request, $filiere_id)
    {
        // 1. Validation de tous les champs obligatoires du formulaire
        $request->validate([
            'niveau_id'   => 'required',
            'jour'        => 'required',
            'heure_debut' => 'required',
            'heure_fin'   => 'required',
            'matiere'     => 'required|string|max:255',
            'salle'       => 'required|string|max:255',
        ]);

        // 2. Traitement d'insertion en base de données
        // (Décommente et adapte les champs selon ton modèle de cours, ex: Cours ou EmploiDuTemps)
        /*
        \App\Models\Cours::create([
            'filiere_id'  => $filiere_id,
            'niveau_id'   => $request->niveau_id,
            'jour'        => $request->jour,
            'heure_debut' => $request->heure_debut,
            'heure_fin'   => $request->heure_fin,
            'matiere'     => $request->matiere,
            'salle'       => $request->salle,
        ]);
        */

        // 3. Redirection vers la vue du tableau (show) avec un message de succès
        return redirect()->route('gestion.emploi_du_temps.show', $filiere_id)
                         ->with('success', 'Le cours a été ajouté avec succès !');
    }

    /**
     * Affiche le tableau de l'emploi du temps d'une filière spécifique
     */
    public function show($id)
    {
        $filiere = Filiere::findOrFail($id);
        
        // On charge également les niveaux pour le filtre dynamique dans show.blade.php
        $niveaux = $filiere->niveaux;
        
        return view('gestion.emploi_du_temps.show', compact('filiere', 'niveaux'));
    }
}