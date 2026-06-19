<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use App\Models\Etudiant;
use App\Models\Enseignant;
use App\Models\UfrInstitut;
use App\Models\PendingInscription;
use App\Models\EmploiDuTemps;

Route::get('/admin/tableau_bord', function () {
    return view('admin.tableau_bord', [
        'etudiants' => Etudiant::count(),
        'enseignants' => Enseignant::count(),
        'departements' => UfrInstitut::count(),
        'inscriptions_en_attente' => PendingInscription::where('status', 'en_attente')->count(),
        'latestInscription' => PendingInscription::orderBy('created_at', 'desc')->first(),
    ]);
})->name('admin.tableau_bord');

Route::get('/etudiant/tableau_bord', function () {
    $user = auth()->user();
    $etudiant = $user ? $user->etudiant : null;
    $emplois = $etudiant && $etudiant->filiere ? $etudiant->filiere->emploisDuTemps()
        ->where('niveau_id', $etudiant->niveau_id)
        ->orderBy('jour')
        ->orderBy('heure_debut')
        ->get() : [];
    return view('etudiant.tableau_bord', compact('emplois', 'etudiant'));
})->name('etudiant.tableau_bord');

Route::get('/enseignant/tableau_bord', [\App\Http\Controllers\EnseignantEmploiDuTempsController::class, 'index'])
    ->middleware('auth')
    ->name('enseignant.tableau_bord');
Route::get('/departement/tableau_bord', [\App\Http\Controllers\DepartementDashboardController::class, 'index'])->name('departement.tableau_bord');
Route::post('/departement/filieres', [\App\Http\Controllers\DepartementDashboardController::class, 'storeFiliere'])->name('departement.filieres.store');
Route::put('/departement/filieres/{filiere}', [\App\Http\Controllers\DepartementDashboardController::class, 'updateFiliere'])->name('departement.filieres.update');
Route::delete('/departement/filieres/{filiere}', [\App\Http\Controllers\DepartementDashboardController::class, 'destroyFiliere'])->name('departement.filieres.destroy');
Route::post('/departement/calendriers-evaluations', [\App\Http\Controllers\DepartementDashboardController::class, 'storeCalendrierEvaluation'])->name('departement.calendriers_evaluations.store');

// Vacations (Chef de département) : demande, validation, rejet
Route::post('/departement/vacations', [\App\Http\Controllers\DepartementDashboardController::class, 'storeVacation'])->name('departement.vacations.store');
Route::post('/departement/vacations/{vacation}/valider', [\App\Http\Controllers\DepartementDashboardController::class, 'validerVacation'])->name('departement.vacations.valider');
Route::post('/departement/vacations/{vacation}/rejeter', [\App\Http\Controllers\DepartementDashboardController::class, 'rejeterVacation'])->name('departement.vacations.rejeter');

// Exports des calendriers d'évaluations + partage dans l'espace info
Route::get('/departement/calendriers-evaluations/export/excel', [\App\Http\Controllers\DepartementDashboardController::class, 'exportCalendriersExcel'])->name('departement.calendriers_evaluations.export.excel');
Route::get('/departement/calendriers-evaluations/export/pdf', [\App\Http\Controllers\DepartementDashboardController::class, 'exportCalendriersPdf'])->name('departement.calendriers_evaluations.export.pdf');
Route::get('/departement/calendriers-evaluations/export/word', [\App\Http\Controllers\DepartementDashboardController::class, 'exportCalendriersWord'])->name('departement.calendriers_evaluations.export.word');
Route::get('/departement/calendriers-evaluations/export/html', [\App\Http\Controllers\DepartementDashboardController::class, 'exportCalendriersHtml'])->name('departement.calendriers_evaluations.export.html');
Route::post('/departement/calendriers-evaluations/{calendrier}/partager', [\App\Http\Controllers\DepartementDashboardController::class, 'partagerCalendrier'])->name('departement.calendriers_evaluations.partager');

// Espace info (partagé par tous les tableaux de bord)
Route::middleware('auth')->group(function () {
    Route::post('/informations', [\App\Http\Controllers\InformationController::class, 'store'])->name('informations.store');
    Route::delete('/informations/{information}', [\App\Http\Controllers\InformationController::class, 'destroy'])->name('informations.destroy');
});

Route::get('/admin/compte', function () { return view('admin.compte'); });
use App\Http\Controllers\Gestion\EtudiantController;
use App\Http\Controllers\Gestion\EnseignantController;
use App\Http\Controllers\Gestion\DepartementController;
use App\Http\Controllers\Gestion\PendingInscriptionController;
use App\Http\Controllers\Gestion\EmploiDuTempsController;
use App\Http\Controllers\Auth\InscriptionController;
use App\Http\Controllers\Auth\LoginController;

// Routes d'authentification
Route::get('/connexion', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', function () {
    return redirect()->route('login');
});
Route::post('/connexion', [LoginController::class, 'login'])->name('login.post');
Route::post('/deconnexion', [LoginController::class, 'logout'])->name('logout');

// Routes d'inscription publique
Route::get('/gestion/inscription', [InscriptionController::class, 'show'])->name('inscription');
Route::post('/gestion/inscription', [InscriptionController::class, 'store'])->name('inscription.store');

// Routes de gestion des inscriptions en attente (admin)
Route::get('/gestion/inscriptions-en-attente', [PendingInscriptionController::class, 'index'])->name('gestion.inscriptions.index');
Route::get('/gestion/inscriptions-en-attente/{inscription}', [PendingInscriptionController::class, 'show'])->name('gestion.inscriptions.show');
Route::post('/gestion/inscriptions-en-attente/{inscription}/approver', [PendingInscriptionController::class, 'approve'])->name('gestion.inscriptions.approve');
Route::post('/gestion/inscriptions-en-attente/{inscription}/rejeter', [PendingInscriptionController::class, 'reject'])->name('gestion.inscriptions.reject');

Route::get('/gestion/inscription_details/{inscription?}', function (?PendingInscription $inscription = null) {
    if (!$inscription) {
        return redirect()->route('gestion.inscriptions.index');
    }
    return app(PendingInscriptionController::class)->show($inscription);
})->name('gestion.inscription_details');

Route::get('/gestion/liste_etudiant', [EtudiantController::class, 'index'])->name('gestion.liste_etudiant');
Route::get('/gestion/liste_enseignant', [EnseignantController::class, 'index'])->name('gestion.liste_enseignant');
Route::get('/gestion/liste_departement', [DepartementController::class, 'index'])->name('gestion.liste_departement');

Route::get('/gestion/creer_etudiant', [EtudiantController::class, 'create'])->name('gestion.creer_etudiant');
Route::post('/gestion/creer_etudiant', [EtudiantController::class, 'store'])->name('gestion.creer_etudiant.store');
Route::get('/gestion/creer_enseignant', [EnseignantController::class, 'create'])->name('gestion.creer_enseignant');
Route::post('/gestion/creer_enseignant', [EnseignantController::class, 'store'])->name('gestion.creer_enseignant.store');
Route::get('/gestion/creer_departement', [DepartementController::class, 'create'])->name('gestion.creer_departement');
Route::post('/gestion/creer_departement', [DepartementController::class, 'store'])->name('gestion.creer_departement.store');
Route::get('/gestion/editer_enseignant/{enseignant}', [EnseignantController::class, 'edit'])->name('gestion.editer_enseignant');
Route::put('/gestion/editer_enseignant/{enseignant}', [EnseignantController::class, 'update'])->name('gestion.editer_enseignant.update');
Route::get('/gestion/editer_departement/{departement}', [DepartementController::class, 'edit'])->name('gestion.editer_departement');
Route::put('/gestion/editer_departement/{departement}', [DepartementController::class, 'update'])->name('gestion.editer_departement.update');
Route::get('/gestion/editer_etudiant/{etudiant}', [EtudiantController::class, 'edit'])->name('gestion.editer_etudiant');
Route::put('/gestion/editer_etudiant/{etudiant}', [EtudiantController::class, 'update'])->name('gestion.editer_etudiant.update');
// Exports
Route::get('/gestion/export/excel', [EtudiantController::class, 'exportExcel'])->name('gestion.export.excel');
Route::get('/gestion/export/pdf', [EtudiantController::class, 'exportPdf'])->name('gestion.export.pdf');
Route::get('/gestion/export/word', [EtudiantController::class, 'exportWord'])->name('gestion.export.word');
Route::get('/gestion/export/html', [EtudiantController::class, 'exportHtml'])->name('gestion.export.html');
Route::get('/gestion/export/enseignants/excel', [EnseignantController::class, 'exportExcel'])->name('gestion.export.enseignants.excel');
Route::get('/gestion/export/enseignants/pdf', [EnseignantController::class, 'exportPdf'])->name('gestion.export.enseignants.pdf');
Route::get('/gestion/export/enseignants/word', [EnseignantController::class, 'exportWord'])->name('gestion.export.enseignants.word');
Route::get('/gestion/export/enseignants/html', [EnseignantController::class, 'exportHtml'])->name('gestion.export.enseignants.html');
Route::get('/gestion/export/departements/excel', [DepartementController::class, 'exportExcel'])->name('gestion.export.departements.excel');
Route::get('/gestion/export/departements/pdf', [DepartementController::class, 'exportPdf'])->name('gestion.export.departements.pdf');
Route::get('/gestion/export/departements/word', [DepartementController::class, 'exportWord'])->name('gestion.export.departements.word');
Route::get('/gestion/export/departements/html', [DepartementController::class, 'exportHtml'])->name('gestion.export.departements.html');
// Imports
Route::post('/gestion/import/etudiants', [EtudiantController::class, 'import'])->name('gestion.import.etudiants');
Route::post('/gestion/import/enseignants', [EnseignantController::class, 'import'])->name('gestion.import.enseignants');
Route::post('/gestion/import/departements', [DepartementController::class, 'import'])->name('gestion.import.departements');

// Routes d'emploi du temps (Chef de département)
Route::get('/gestion/emploi-du-temps', [EmploiDuTempsController::class, 'index'])->name('gestion.emploi_du_temps.index');
// Routes avec 2 paramètres AVANT celles avec 1 seul paramètre
Route::get('/gestion/emploi-du-temps/{filiere}/{niveau}/creer', [EmploiDuTempsController::class, 'create'])->name('gestion.emploi_du_temps.create_niveau');
Route::get('/gestion/emploi-du-temps/{filiere}/{niveau}', [EmploiDuTempsController::class, 'show'])->name('gestion.emploi_du_temps.show_niveau');
// Puis les routes avec 1 paramètre
Route::get('/gestion/emploi-du-temps/{filiere}/creer', [EmploiDuTempsController::class, 'create'])->name('gestion.emploi_du_temps.create');
Route::get('/gestion/emploi-du-temps/{filiere}', [EmploiDuTempsController::class, 'show'])->name('gestion.emploi_du_temps.show');
Route::post('/gestion/emploi-du-temps/{filiere}', [EmploiDuTempsController::class, 'store'])->name('gestion.emploi_du_temps.store');
Route::get('/gestion/emploi-du-temps/{emploi}/editer', [EmploiDuTempsController::class, 'edit'])->name('gestion.emploi_du_temps.edit');
Route::put('/gestion/emploi-du-temps/{emploi}', [EmploiDuTempsController::class, 'update'])->name('gestion.emploi_du_temps.update');
Route::delete('/gestion/emploi-du-temps/{emploi}', [EmploiDuTempsController::class, 'destroy'])->name('gestion.emploi_du_temps.destroy');
