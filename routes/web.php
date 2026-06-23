<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/* --- MODÈLES ET CONTROLLERS --- */
use App\Models\Etudiant;
use App\Models\Enseignant;
use App\Models\UfrInstitut;
use App\Models\PendingInscription;
use App\Models\Information;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\Gestion\EtudiantController;
use App\Http\Controllers\Gestion\EnseignantController;
use App\Http\Controllers\Gestion\DepartementController;
use App\Http\Controllers\Gestion\PendingInscriptionController;
use App\Http\Controllers\Auth\LoginController;

/* --- PAGES PUBLIQUES ET TABLEAUX DE BORD --- */
Route::get('/informations', [InformationController::class, 'index'])->name('informations.index');
Route::get('/admin/tableau_bord', function () {
    return view('admin.tableau_bord', [
        'etudiants' => Etudiant::count(),
        'enseignants' => Enseignant::count(),
        'departements' => UfrInstitut::count(),
        'inscriptions_en_attente' => PendingInscription::where('status', 'en_attente')->count(),
        'latestInscription' => PendingInscription::orderBy('created_at', 'desc')->first(),
        'informations' => Information::with('auteur')->whereIn('visibilite', ['public', 'administration'])->latest()->get(),
    ]);
})->name('admin.tableau_bord');

Route::get('/etudiant/tableau_bord', function () {
    $user = auth()->user();
    $etudiant = $user ? $user->etudiant : null;
    $emplois = $etudiant && $etudiant->filiere ? $etudiant->filiere->emploisDuTemps()
        ->where('niveau_id', $etudiant->niveau_id)
        ->orderBy('jour')->orderBy('heure_debut')->get() : [];
    $informations = Information::with('auteur')->whereIn('visibilite', ['public', 'etudiant'])->latest()->get();
    return view('etudiant.tableau_bord', compact('emplois', 'etudiant', 'informations'));
})->name('etudiant.tableau_bord');

Route::get('/enseignant/tableau_bord', [\App\Http\Controllers\EnseignantEmploiDuTempsController::class, 'index'])->middleware('auth')->name('enseignant.tableau_bord');
Route::get('/departement/tableau_bord', function () {
    // 1. Récupérer l'utilisateur connecté (le chef de département)
    $user = auth()->user();
    
    // 2. Vous pouvez charger ici les données spécifiques au département si nécessaire
    // Exemple : $departement = $user->departement;

    // 3. Retourner la vue correspondante (assurez-vous que ce fichier blade existe)
    return view('departement.tableau_bord'); 
})->middleware('auth')->name('departement.tableau_bord');
/* --- TABLEAU DE BORD DU CHEF DE DEPARTEMENT (VRAI CODE FONCTIONNEL) --- */
Route::get('/departement/tableau_board', function () {
    // 1. Récupération des statistiques réelles depuis vos modèles
    $total_etudiants = \App\Models\Etudiant::count();
    $total_enseignants = \App\Models\Enseignant::count();
    
    // Si vous avez un modèle Filiere ou Classe, vous pouvez décommenter la ligne suivante :
    // $total_filieres = \App\Models\Filiere::count(); 

    // 2. Envoi des données à votre vue Blade
    return view('departement.tableau_bord', [
        'etudiants' => $total_etudiants,
        'enseignants' => $total_enseignants,
        'activeTab' => 'dashboard' // Permet de gérer l'activation des liens dans votre sidebar
    ]);
})->middleware('auth')->name('departement.tableau_board');


/* --- COUPONS DE SÉCURITÉ POUR LES LIENS DE LA SIDEBAR --- */
// Ces routes évitent que votre tableau de bord ne crashe à cause de liens manquants
Route::get('/gestion/emploi-du-temps', function() { 
    return "Page Emploi du temps du département en développement"; 
})->name('gestion.emploi_du_temps.index');

Route::get('/gestion/liste_etudiant', function() { 
    return "Page de gestion des étudiants"; 
})->name('gestion.liste_etudiant');

/* --- ACTIONS POST --- */
Route::post('/admin/informations/store', function (Request $request) {
    $data = $request->validate(['titre' => 'required', 'contenu' => 'required', 'visibilite' => 'required']);
    Information::create(array_merge($data, ['user_id' => auth()->id()]));
    return redirect()->back()->with('success', 'Publié !');
})->name('admin.informations.store');

Route::post('/enseignant/informations/store', function (Request $request) {
    $data = $request->validate(['titre' => 'required', 'contenu' => 'required', 'visibilite' => 'required']);
    Information::create(['titre' => $data['titre'], 'contenu' => $data['contenu'], 'visibilite' => ($data['visibilite'] === 'prive' ? 'enseignant' : 'public'), 'user_id' => auth()->id()]);
    return redirect()->back()->with('success', 'Publié !');
})->name('enseignant.informations.store');

/* --- GESTION ADMINISTRATIVE --- */

// --- Listes ---
Route::get('/gestion/liste_etudiant', [EtudiantController::class, 'index'])->name('gestion.liste_etudiant');
Route::get('/gestion/liste_enseignant', [EnseignantController::class, 'index'])->name('gestion.liste_enseignant');
Route::get('/gestion/liste_departement', [DepartementController::class, 'index'])->name('gestion.liste_departement');

// --- Création ---
Route::get('/gestion/creer_etudiant', [EtudiantController::class, 'create'])->name('gestion.creer_etudiant');
Route::get('/gestion/creer_enseignant', [EnseignantController::class, 'create'])->name('gestion.creer_enseignant');
Route::get('/gestion/creer_departement', [DepartementController::class, 'create'])->name('gestion.creer_departement');
Route::post('/gestion/creer_departement', [DepartementController::class, 'store'])->name('gestion.creer_departement.store');

// --- Edition / Suppression ---
Route::get('/gestion/editer_enseignant/{enseignant}', [EnseignantController::class, 'edit'])->name('gestion.editer_enseignant');
Route::put('/gestion/editer_enseignant/{enseignant}', [EnseignantController::class, 'update'])->name('gestion.editer_enseignant.update');
Route::get('/gestion/editer_etudiant/{etudiant}', [EtudiantController::class, 'edit'])->name('gestion.editer_etudiant');
Route::put('/gestion/editer_etudiant/{etudiant}', [EtudiantController::class, 'update'])->name('gestion.editer_etudiant.update');

Route::get('/gestion/editer_departement/{departement}', [DepartementController::class, 'edit'])->name('gestion.editer_departement');
Route::put('/gestion/editer_departement/{departement}', [DepartementController::class, 'update'])->name('gestion.editer_departement.update');
Route::delete('/gestion/supprimer_departement/{departement}', [DepartementController::class, 'destroy'])->name('gestion.supprimer_departement');

// --- Imports ---
Route::post('/gestion/import/departements', [DepartementController::class, 'import'])->name('gestion.import.departements');
Route::post('/gestion/import/enseignants', [EnseignantController::class, 'import'])->name('gestion.import.enseignants');
Route::post('/gestion/import/etudiants', [EtudiantController::class, 'import'])->name('gestion.import.etudiants'); // Ajouté pour corriger l'import étudiant !

// --- Exports ---

// Enseignants
Route::get('/gestion/export/enseignants/excel', [EnseignantController::class, 'exportExcel'])->name('gestion.export.enseignants.excel');
Route::get('/gestion/export/enseignants/pdf', [EnseignantController::class, 'exportPdf'])->name('gestion.export.enseignants.pdf');
Route::get('/gestion/export/enseignants/word', [EnseignantController::class, 'exportWord'])->name('gestion.export.enseignants.word');
Route::get('/gestion/export/enseignants/html', [EnseignantController::class, 'exportHtml'])->name('gestion.export.enseignants.html');

// --- Exports Étudiants ---
Route::get('/gestion/export/etudiants/excel', [EtudiantController::class, 'exportExcel'])->name('gestion.export.excel');
Route::get('/gestion/export/etudiants/pdf', [EtudiantController::class, 'exportPdf'])->name('gestion.export.pdf');
Route::get('/gestion/export/etudiants/word', [EtudiantController::class, 'exportWord'])->name('gestion.export.word');
Route::get('/gestion/export/etudiants/html', [EtudiantController::class, 'exportHtml'])->name('gestion.export.html');

// --- Exports Départements ---
Route::get('/gestion/export/departements/excel', [DepartementController::class, 'exportExcel'])->name('gestion.export.departements.excel');
Route::get('/gestion/export/departements/pdf', [DepartementController::class, 'exportPdf'])->name('gestion.export.departements.pdf');
Route::get('/gestion/export/departements/word', [DepartementController::class, 'exportWord'])->name('gestion.export.departements.word');
Route::get('/gestion/export/departements/html', [DepartementController::class, 'exportHtml'])->name('gestion.export.departements.html');
Route::get('/gestion/export/departements/all', [DepartementController::class, 'exportExcel'])->name('gestion.export.departements.all'); // Ajouté pour corriger la vue département !

// --- Inscriptions ---
Route::get('/gestion/inscriptions-en-attente', [PendingInscriptionController::class, 'index'])->name('gestion.inscriptions.index');
Route::post('/gestion/inscriptions-en-attente/{inscription}/approver', [PendingInscriptionController::class, 'approve'])->name('gestion.inscriptions.approve');

/* --- AUTHENTIFICATION --- */
Route::get('/connexion', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/connexion', [LoginController::class, 'login'])->name('login.post');
Route::post('/deconnexion', [LoginController::class, 'logout'])->name('logout');

/* --- AUTHENTIFICATION --- */
Route::get('/connexion', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/connexion', [LoginController::class, 'login'])->name('login.post');
Route::post('/deconnexion', [LoginController::class, 'logout'])->name('logout');