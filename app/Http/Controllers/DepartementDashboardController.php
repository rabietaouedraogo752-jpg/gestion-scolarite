<?php

namespace App\Http\Controllers;

use App\Models\CalendrierEvaluation;
use App\Models\EmploiDuTemps;
use App\Models\Enseignant;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\UfrInstitut;
use App\Models\Vacation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DepartementDashboardController extends Controller
{
    public function index()
    {
        $vacationsEnabled = Schema::hasTable('vacations');
        $calendriersEnabled = Schema::hasTable('calendriers_evaluations');

        $stats = [
            'enseignants' => Enseignant::count(),
            'cours' => EmploiDuTemps::count(),
            'vacations' => $vacationsEnabled ? Vacation::count() : 0,
            'alertes' => EmploiDuTemps::whereNull('enseignant_id')->count()
                + ($vacationsEnabled ? Vacation::where('statut', 'en_attente')->count() : 0),
        ];

        return view('departement.tableau_bord', [
            'stats' => $stats,
            'enseignants' => Enseignant::with('user')->orderBy('id', 'desc')->get(),
            'filieres' => Filiere::with('niveaux')->withCount(['etudiants', 'emploisDuTemps'])->orderBy('nom_filiere')->get(),
            'niveaux' => Niveau::orderBy('code_niveau')->get(),
            'departements' => UfrInstitut::orderBy('nom')->get(),
            'vacations' => $vacationsEnabled
                ? Vacation::with('enseignant.user')->orderBy('created_at', 'desc')->get()
                : collect(),
            'calendriers' => $calendriersEnabled
                ? CalendrierEvaluation::with(['filiere', 'niveau'])->orderBy('date_debut', 'desc')->get()
                : collect(),
            'vacationsEnabled' => $vacationsEnabled,
            'calendriersEnabled' => $calendriersEnabled,
        ]);
    }

    public function storeFiliere(Request $request)
    {
        $data = $request->validate([
            'nom_filiere' => 'required|string|max:255',
            'ufr_id' => 'nullable|exists:ufr_instituts,id',
            'niveaux' => 'nullable|array',
            'niveaux.*' => 'exists:niveaux,id',
        ]);

        $departementId = $data['ufr_id'] ?? UfrInstitut::query()->value('id');

        $filiere = Filiere::create([
            'nom_filiere' => $data['nom_filiere'],
            'ufr_id' => $departementId,
        ]);

        $filiere->niveaux()->sync($data['niveaux'] ?? []);

        return redirect()
            ->route('departement.tableau_bord', ['tab' => 'enseignements'])
            ->with('success', 'Filière créée avec succès.');
    }

    public function storeCalendrierEvaluation(Request $request)
    {
        $data = $request->validate([
            'filiere_id' => 'required|exists:filieres,id',
            'niveau_id' => 'required|exists:niveaux,id',
            'intitule' => 'required|string|max:150',
            'type' => 'required|string|max:50',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'description' => 'nullable|string|max:1000',
        ]);

        CalendrierEvaluation::create($data);

        return redirect()
            ->route('departement.tableau_bord', ['tab' => 'evaluations'])
            ->with('success', 'Calendrier d’évaluations créé avec succès.');
    }
}
