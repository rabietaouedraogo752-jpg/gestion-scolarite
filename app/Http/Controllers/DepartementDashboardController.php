<?php

namespace App\Http\Controllers;

use App\Exports\CalendriersEvaluationsExport;
use App\Models\CalendrierEvaluation;
use App\Models\EmploiDuTemps;
use App\Models\Enseignant;
use App\Models\Filiere;
use App\Models\Information;
use App\Models\Niveau;
use App\Models\UfrInstitut;
use App\Models\Vacation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

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

    public function updateFiliere(Request $request, Filiere $filiere)
    {
        $data = $request->validate([
            'nom_filiere' => 'required|string|max:255',
            'ufr_id' => 'nullable|exists:ufr_instituts,id',
            'niveaux' => 'nullable|array',
            'niveaux.*' => 'exists:niveaux,id',
        ]);

        $filiere->update([
            'nom_filiere' => $data['nom_filiere'],
            'ufr_id' => $data['ufr_id'] ?? $filiere->ufr_id,
        ]);

        $filiere->niveaux()->sync($data['niveaux'] ?? []);

        return redirect()
            ->route('departement.tableau_bord', ['tab' => 'enseignements'])
            ->with('success', 'Filière mise à jour avec succès.');
    }

    public function destroyFiliere(Filiere $filiere)
    {
        $filiere->niveaux()->detach();
        $filiere->delete();

        return redirect()
            ->route('departement.tableau_bord', ['tab' => 'enseignements'])
            ->with('success', 'Filière supprimée avec succès.');
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

    public function storeVacation(Request $request)
    {
        $data = $request->validate([
            'enseignant_id' => 'nullable|exists:enseignants,id',
            'matiere' => 'required|string|max:150',
            'nombre_heures' => 'required|integer|min:1',
            'periode' => 'nullable|string|max:100',
        ]);

        $data['statut'] = 'en_attente';
        Vacation::create($data);

        return redirect()
            ->route('departement.tableau_bord', ['tab' => 'vacations'])
            ->with('success', 'Demande de vacation enregistrée.');
    }

    public function validerVacation(Vacation $vacation)
    {
        $vacation->update(['statut' => 'validee']);

        return redirect()
            ->route('departement.tableau_bord', ['tab' => 'vacations'])
            ->with('success', 'Demande de vacation validée.');
    }

    public function rejeterVacation(Vacation $vacation)
    {
        $vacation->update(['statut' => 'rejetee']);

        return redirect()
            ->route('departement.tableau_bord', ['tab' => 'vacations'])
            ->with('success', 'Demande de vacation rejetée.');
    }

    public function exportCalendriersExcel()
    {
        return Excel::download(new CalendriersEvaluationsExport, 'calendriers_evaluations_'.date('Y-m-d').'.xlsx');
    }

    public function exportCalendriersPdf()
    {
        $calendriers = $this->calendriersQuery();
        $pdf = PDF::loadView('gestion.exports.calendriers_evaluations_pdf', compact('calendriers'));

        return $pdf->download('calendriers_evaluations_'.date('Y-m-d').'.pdf');
    }

    public function exportCalendriersWord()
    {
        $calendriers = $this->calendriersQuery();
        $phpWord = new PhpWord;
        $section = $phpWord->addSection();

        $section->addTitle('Calendriers d\'évaluations', 1);
        $section->addTextBreak();

        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
        $table->addRow();
        foreach (['Intitulé', 'Filière', 'Niveau', 'Type', 'Début', 'Fin'] as $heading) {
            $table->addCell()->addText($heading, ['bold' => true]);
        }

        foreach ($calendriers as $calendrier) {
            $table->addRow();
            $table->addCell()->addText($calendrier->intitule);
            $table->addCell()->addText($calendrier->filiere->nom_filiere ?? '—');
            $table->addCell()->addText($calendrier->niveau->code_niveau ?? '—');
            $table->addCell()->addText($calendrier->type);
            $table->addCell()->addText($calendrier->date_debut?->format('d/m/Y') ?? '—');
            $table->addCell()->addText($calendrier->date_fin?->format('d/m/Y') ?? '—');
        }

        return response()->streamDownload(function () use ($phpWord) {
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save('php://output');
        }, 'calendriers_evaluations_'.date('Y-m-d').'.docx');
    }

    public function exportCalendriersHtml()
    {
        $calendriers = $this->calendriersQuery();

        return response()
            ->view('gestion.exports.calendriers_evaluations_pdf', compact('calendriers'))
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="calendriers_evaluations_'.date('Y-m-d').'.html"');
    }

    public function partagerCalendrier(Request $request, CalendrierEvaluation $calendrier)
    {
        $request->validate([
            'visibilite' => 'nullable|in:public,prive',
        ]);

        $calendrier->loadMissing(['filiere', 'niveau']);

        $contenu = sprintf(
            "Calendrier d'évaluations « %s » (%s)\nFilière : %s — Niveau : %s\nDu %s au %s.%s",
            $calendrier->intitule,
            $calendrier->type,
            $calendrier->filiere->nom_filiere ?? '—',
            $calendrier->niveau->code_niveau ?? '—',
            $calendrier->date_debut?->format('d/m/Y') ?? '—',
            $calendrier->date_fin?->format('d/m/Y') ?? '—',
            $calendrier->description ? "\n".$calendrier->description : ''
        );

        Information::create([
            'user_id' => Auth::id(),
            'titre' => 'Évaluations : '.$calendrier->intitule,
            'contenu' => $contenu,
            'visibilite' => $request->input('visibilite', 'public'),
            'categorie' => 'calendrier',
        ]);

        return redirect()
            ->route('departement.tableau_bord', ['tab' => 'evaluations'])
            ->with('success', 'Calendrier partagé dans l’espace info.');
    }

    private function calendriersQuery()
    {
        return CalendrierEvaluation::with(['filiere', 'niveau'])->orderBy('date_debut', 'desc')->get();
    }
}
