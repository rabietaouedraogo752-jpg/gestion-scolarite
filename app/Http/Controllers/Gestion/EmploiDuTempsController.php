<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\EmploiDuTemps;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Enseignant;
use Illuminate\Http\Request;

class EmploiDuTempsController extends Controller
{
    /**
     * Affiche la liste des filières et niveaux
     */
    public function index()
    {
        $filieres = Filiere::with('niveaux')->get();
        return view('gestion.emploi_du_temps.index', compact('filieres'));
    }

    /**
     * Affiche les emplois du temps d'une filière filtrés par niveau
     */
    public function show(Request $request, Filiere $filiere, Niveau $niveau = null)
    {
        $niveaux = $filiere->niveaux()->orderBy('code_niveau')->get();

        if (!$niveau && $request->filled('niveau')) {
            $niveau = $niveaux->firstWhere('id', (int) $request->query('niveau'));
        }

        $query = $filiere->emploisDuTemps()
            ->with(['niveau', 'enseignantModel.user'])
            ->orderBy('jour')
            ->orderBy('heure_debut');

        if ($niveau) {
            $query->where('niveau_id', $niveau->id);
        }

        $emplois = $query->get();

        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
        return view('gestion.emploi_du_temps.show', compact('filiere', 'niveau', 'niveaux', 'emplois', 'jours'));
    }

    /**
     * Affiche le formulaire pour créer un nouvel emploi du temps
     */
    public function create(Filiere $filiere, Niveau $niveau = null)
    {
        $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'];
        $niveaux = $filiere->niveaux()->orderBy('code_niveau')->get();
        if ($niveaux->isEmpty()) {
            $niveaux = Niveau::orderBy('code_niveau')->get();
        }
        $enseignants = Enseignant::with('user')->get();
        
        return view('gestion.emploi_du_temps.create', compact('filiere', 'niveau', 'jours', 'niveaux', 'enseignants'));
    }

    /**
     * Sauvegarde un nouvel emploi du temps
     */
    public function store(Request $request, Filiere $filiere)
    {
        $validated = $request->validate([
            'niveau_id' => 'required|exists:niveaux,id',
            'enseignant_id' => 'nullable|exists:enseignants,id',
            'jour' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'matiere' => 'required|string|max:100',
            'salle' => 'required|string|max:50',
            'enseignant' => 'nullable|string|max:100',
        ]);

        $validated['filiere_id'] = $filiere->id;

        EmploiDuTemps::create($validated);

        return redirect()
            ->route('gestion.emploi_du_temps.show_niveau', ['filiere' => $filiere, 'niveau' => $validated['niveau_id']])
            ->with('success', 'Cours ajouté avec succès!');
    }

    /**
     * Affiche le formulaire pour éditer un emploi du temps
     */
    public function edit(EmploiDuTemps $emploi)
    {
        $filiere = $emploi->filiere;
        $niveau = $emploi->niveau;
        $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'];
        $niveaux = $filiere->niveaux()->orderBy('code_niveau')->get();
        if ($niveaux->isEmpty()) {
            $niveaux = Niveau::orderBy('code_niveau')->get();
        }
        $enseignants = Enseignant::with('user')->get();
        
        return view('gestion.emploi_du_temps.edit', compact('emploi', 'filiere', 'niveau', 'jours', 'niveaux', 'enseignants'));
    }

    /**
     * Met à jour un emploi du temps
     */
    public function update(Request $request, EmploiDuTemps $emploi)
    {
        $validated = $request->validate([
            'niveau_id' => 'required|exists:niveaux,id',
            'enseignant_id' => 'nullable|exists:enseignants,id',
            'jour' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'matiere' => 'required|string|max:100',
            'salle' => 'required|string|max:50',
            'enseignant' => 'nullable|string|max:100',
        ]);

        $emploi->update($validated);

        return redirect()
            ->route('gestion.emploi_du_temps.show_niveau', ['filiere' => $emploi->filiere, 'niveau' => $emploi->niveau])
            ->with('success', 'Cours mis à jour avec succès!');
    }

    /**
     * Supprime un emploi du temps
     */
    public function destroy(EmploiDuTemps $emploi)
    {
        $filiere = $emploi->filiere;
        $niveau = $emploi->niveau;
        $emploi->delete();

        return redirect()
            ->route('gestion.emploi_du_temps.show_niveau', ['filiere' => $filiere, 'niveau' => $niveau])
            ->with('success', 'Cours supprimé avec succès!');
    }
}
