<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\EmploiDuTemps;
use App\Models\Filiere;
use Illuminate\Http\Request;

class EmploiDuTempsController extends Controller
{
    /**
     * Affiche la liste des emplois du temps pour une filière
     */
    public function index()
    {
        $filieres = Filiere::all();
        return view('gestion.emploi_du_temps.index', compact('filieres'));
    }

    /**
     * Affiche les détails de l'emploi du temps d'une filière
     */
    public function show(Filiere $filiere)
    {
        $emplois = $filiere->emploisDuTemps()->orderBy('jour')->orderBy('heure_debut')->get();
        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
        
        return view('gestion.emploi_du_temps.show', compact('filiere', 'emplois', 'jours'));
    }

    /**
     * Affiche le formulaire pour créer un nouvel emploi du temps
     */
    public function create(Filiere $filiere)
    {
        $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'];
        return view('gestion.emploi_du_temps.create', compact('filiere', 'jours'));
    }

    /**
     * Sauvegarde un nouvel emploi du temps
     */
    public function store(Request $request, Filiere $filiere)
    {
        $validated = $request->validate([
            'jour' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'matiere' => 'required|string|max:100',
            'salle' => 'required|string|max:50',
            'enseignant' => 'required|string|max:100',
        ]);

        $validated['filiere_id'] = $filiere->id;

        EmploiDuTemps::create($validated);

        return redirect()->route('gestion.emploi_du_temps.show', $filiere)->with('success', 'Cours ajouté avec succès!');
    }

    /**
     * Affiche le formulaire pour éditer un emploi du temps
     */
    public function edit(EmploiDuTemps $emploi)
    {
        $filiere = $emploi->filiere;
        $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'];
        return view('gestion.emploi_du_temps.edit', compact('emploi', 'filiere', 'jours'));
    }

    /**
     * Met à jour un emploi du temps
     */
    public function update(Request $request, EmploiDuTemps $emploi)
    {
        $validated = $request->validate([
            'jour' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'matiere' => 'required|string|max:100',
            'salle' => 'required|string|max:50',
            'enseignant' => 'required|string|max:100',
        ]);

        $emploi->update($validated);

        return redirect()->route('gestion.emploi_du_temps.show', $emploi->filiere)->with('success', 'Cours mis à jour avec succès!');
    }

    /**
     * Supprime un emploi du temps
     */
    public function destroy(EmploiDuTemps $emploi)
    {
        $filiere = $emploi->filiere;
        $emploi->delete();

        return redirect()->route('gestion.emploi_du_temps.show', $filiere)->with('success', 'Cours supprimé avec succès!');
    }
}
