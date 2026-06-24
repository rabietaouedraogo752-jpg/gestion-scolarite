<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Str;          

/*
|--------------------------------------------------------------------------
| 1. IMPORTATIONS DES MODÈLES ET CONTRÔLEURS
|--------------------------------------------------------------------------
*/
use App\Models\User;
use App\Models\Etudiant;
use App\Models\Enseignant;
use App\Models\UfrInstitut;
use App\Models\PendingInscription;
use App\Models\Information;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\DepartementDashboardController;
use App\Http\Controllers\EnseignantDashboardController;

use App\Http\Controllers\Gestion\EtudiantController;
use App\Http\Controllers\Gestion\EnseignantController;
use App\Http\Controllers\Gestion\DepartementController;
use App\Http\Controllers\Gestion\PendingInscriptionController;

/*
|--------------------------------------------------------------------------
| 2. PAGES PUBLIQUES ET AUTHENTIFICATION
|--------------------------------------------------------------------------
*/
Route::get('/informations', [InformationController::class, 'index'])->name('informations.index');

Route::get('/connexion', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/connexion', [LoginController::class, 'login'])->name('login.post');
Route::post('/deconnexion', [LoginController::class, 'logout'])->name('logout');
Route::post('/gestion/creer_departement_filiere', [DepartementController::class, 'storeDepartementFiliere'])->name('gestion.creer_departement_filiere.store');

/*
|--------------------------------------------------------------------------
| 3. ZONE SÉCURISÉE (UTILISATEURS CONNECTÉS)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /* --- TABLEAU DE BORD ADMIN --- */
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

    Route::get('/admin/compte', function () { 
        return view('admin.compte'); 
    });

    /* --- ACTIONS DE PUBLICATION D'INFORMATIONS --- */
    Route::post('/admin/informations/store', function (Request $request) {
        $data = $request->validate(['titre' => 'required', 'contenu' => 'required', 'visibilite' => 'required']);
        Information::create(array_merge($data, ['user_id' => auth()->id()]));
        return redirect()->back()->with('success', 'Information publiée avec succès !');
    })->name('admin.informations.store');

    Route::post('/enseignant/informations/store', function (Request $request) {
        $data = $request->validate(['titre' => 'required', 'contenu' => 'required', 'visibilite' => 'required']);
        Information::create([
            'titre' => $data['titre'], 
            'contenu' => $data['contenu'], 
            'visibilite' => ($data['visibilite'] === 'prive' ? 'enseignant' : 'public'), 
            'user_id' => auth()->id()
        ]);
        return redirect()->back()->with('success', 'Information publiée avec succès !');
    })->name('enseignant.informations.store');


    /*
    |--------------------------------------------------------------------------
    | GESTION ADMINISTRATIVE (ADMINS / SCOLARITÉ)
    |--------------------------------------------------------------------------
    |*/
    // --- Listes ---
    Route::get('/gestion/liste_etudiant', [EtudiantController::class, 'index'])->name('gestion.liste_etudiant');
    Route::get('/gestion/liste_enseignant', [EnseignantController::class, 'index'])->name('gestion.liste_enseignant');
    Route::get('/gestion/liste_departement', [DepartementController::class, 'index'])->name('gestion.liste_departement');
    
    // LA ROUTE COMMANDEE POUR LE BOUTON DEPARTEMENT VERS FILIÈRES :
    Route::get('/gestion/liste_filiere', [DepartementController::class, 'listeFiliere'])->name('gestion.liste_filiere');
    Route::delete('/gestion/filiere/{id}', [DepartementController::class, 'destroyFiliere'])->name('gestion.filiere.destroy');

    // --- Formulaires de Création (Affichage) ---
    Route::get('/gestion/creer_etudiant', [EtudiantController::class, 'create'])->name('gestion.creer_etudiant');
    Route::get('/gestion/creer_enseignant', [EnseignantController::class, 'create'])->name('gestion.creer_enseignant');
    Route::get('/gestion/creer_departement', [DepartementController::class, 'create'])->name('gestion.creer_departement');

    // --- Traitements de Création (POST) ---
    Route::post('/gestion/creer_etudiant', function (Request $request) {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'matricule'        => 'required|string|max:50',
            'genre'            => 'required|string|max:1',
            'filiere'          => 'required|integer',
            'niveau'           => 'required|integer',
            'date_naissance'   => 'required|date',
            'lieu_naissance'   => 'required|string|max:255',
            'annee_academique' => 'required|string|max:20',
        ]);

        $password = Str::random(8);
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($password),
        ]);

        $annees = explode('-', $validated['annee_academique']);
        $anneeDebut = ($annees[0] ?? date('Y')) . '-09-01';
        $anneeFin   = ($annees[1] ?? (date('Y') + 1)) . '-08-31';

        Etudiant::create([
            'user_id'        => $user->id,
            'nom_prenom'     => $validated['name'],
            'matricule'      => $validated['matricule'],
            'genre'          => $validated['genre'],
            'date_naissance' => $validated['date_naissance'],
            'lieu_naissance' => $validated['lieu_naissance'],
            'filiere_id'     => $validated['filiere'],
            'niveau_id'      => $validated['niveau'],
            'annee_debut'    => $anneeDebut,
            'annee_fin'      => $anneeFin,
        ]);

        return back()->with('success', "Étudiant créé avec succès ! Mot de passe : " . $password);
    })->name('gestion.creer_etudiant.store');

    Route::post('/gestion/creer_enseignant', function (Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'matricule' => 'required|string|unique:enseignants,matricule',
        ]);
        $password = Str::random(8);
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
        ]);
        Enseignant::create([
            'user_id' => $user->id,
            'nom_prenom' => $validated['name'],
            'matricule' => $validated['matricule']
        ]);
        return back()->with('success', "Enseignant créé ! Mot de passe : " . $password);
    })->name('gestion.creer_enseignant.store');

    // CORRECTION ICI : La route pointe maintenant sur la méthode store() du contrôleur
    Route::post('/gestion/creer_departement', [DepartementController::class, 'store'])->name('gestion.creer_departement.store');

    // --- Édition / Modification ---
    Route::get('/gestion/editer_enseignant/{enseignant}', [EnseignantController::class, 'edit'])->name('gestion.editer_enseignant');
    Route::put('/gestion/editer_enseignant/{enseignant}', [EnseignantController::class, 'update'])->name('gestion.editer_enseignant.update');
    
    Route::get('/gestion/editer_etudiant/{etudiant}', [EtudiantController::class, 'edit'])->name('gestion.editer_etudiant');
    Route::put('/gestion/editer_etudiant/{etudiant}', [EtudiantController::class, 'update'])->name('gestion.editer_etudiant.update');

    Route::get('/gestion/editer_departement/{departement}', [DepartementController::class, 'edit'])->name('gestion.editer_departement');
    Route::put('/gestion/editer_departement/{departement}', [DepartementController::class, 'update'])->name('gestion.editer_departement.update');
    Route::delete('/gestion/supprimer_departement/{departement}', [DepartementController::class, 'destroy'])->name('gestion.supprimer_departement');

    // --- Imports de fichiers ---
    Route::post('/gestion/import/departements', [DepartementController::class, 'import'])->name('gestion.import.departements');
    Route::post('/gestion/import/enseignants', [EnseignantController::class, 'import'])->name('gestion.import.enseignants');
    Route::post('/gestion/import/etudiants', [EtudiantController::class, 'import'])->name('gestion.import.etudiants');

    // --- Exports (Enseignants, Étudiants, Départements) ---
    Route::get('/gestion/export/enseignants/excel', [EnseignantController::class, 'exportExcel'])->name('gestion.export.enseignants.excel');
    Route::get('/gestion/export/enseignants/pdf', [EnseignantController::class, 'exportPdf'])->name('gestion.export.enseignants.pdf');
    Route::get('/gestion/export/enseignants/word', [EnseignantController::class, 'exportWord'])->name('gestion.export.enseignants.word');
    Route::get('/gestion/export/enseignants/html', [EnseignantController::class, 'exportHtml'])->name('gestion.export.enseignants.html');

    Route::get('/gestion/export/etudiants/excel', [EtudiantController::class, 'exportExcel'])->name('gestion.export.excel');
    Route::get('/gestion/export/etudiants/pdf', [EtudiantController::class, 'exportPdf'])->name('gestion.export.pdf');
    Route::get('/gestion/export/etudiants/word', [EtudiantController::class, 'exportWord'])->name('gestion.export.word');
    Route::get('/gestion/export/etudiants/html', [EtudiantController::class, 'exportHtml'])->name('gestion.export.html');

    Route::get('/gestion/export/departements/excel', [DepartementController::class, 'exportExcel'])->name('gestion.export.departements.excel');
    Route::get('/gestion/export/departements/pdf', [DepartementController::class, 'exportPdf'])->name('gestion.export.departements.pdf');
    Route::get('/gestion/export/departements/word', [DepartementController::class, 'exportWord'])->name('gestion.export.departements.word');
    Route::get('/gestion/export/departements/html', [DepartementController::class, 'exportHtml'])->name('gestion.export.departements.html');
    Route::get('/gestion/export/departements/all', [DepartementController::class, 'exportExcel'])->name('gestion.export.departements.all');

    // --- Gestion des Inscriptions en attente ---
    Route::get('/gestion/inscriptions-en-attente', [PendingInscriptionController::class, 'index'])->name('gestion.inscriptions.index');
    Route::post('/gestion/inscriptions-en-attente/{inscription}/approver', [PendingInscriptionController::class, 'approve'])->name('gestion.inscriptions.approve');


    /*
    |--------------------------------------------------------------------------
    | ESPACE FONCTIONNALITÉS ÉTUDIANT
    |--------------------------------------------------------------------------
    */
    Route::prefix('etudiant')->name('etudiant.')->group(function () {
        
        Route::get('/tableau_bord', function () {
            $user = auth()->user();
            $etudiant = $user ? $user->etudiant : null;
            $emplois = $etudiant && $etudiant->filiere ? $etudiant->filiere->emploisDuTemps()
                ->where('niveau_id', $etudiant->niveau_id)
                ->orderBy('jour')->orderBy('heure_debut')->get() : [];
            $informations = Information::with('auteur')->whereIn('visibilite', ['public', 'etudiant'])->latest()->get();
            return view('etudiant.tableau_bord', compact('emplois', 'etudiant', 'informations'));
        })->name('tableau_bord');

        Route::get('/profil', function () {
            return view('etudiant.profil', ['etudiant' => auth()->user()->etudiant]);
        })->name('profil');

        Route::put('/profil/update', function (Request $request) {
            $user = auth()->user();
            $etudiant = $user ? $user->etudiant : null;

            if (!$user || !$etudiant) {
                return back()->with('error', 'Utilisateur non trouvé.');
            }

            $request->validate([
                'username' => 'required|string|max:255|unique:users,name,' . $user->id,
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'whatsapp' => 'required|string|max:20',
                'password' => 'nullable|string|min:6|confirmed',
            ]);

            $user->name = $request->username;
            $user->email = $request->email;
            
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $etudiant->update(['telephone' => $request->whatsapp]);

            return back()->with('success', 'Votre profil a été mis à jour avec succès !');
        })->name('profil.update');

        Route::get('/notes', function () { return view('etudiant.notes'); })->name('notes');
        Route::get('/reclamations', function () { return "Page des réclamations en cours de développement"; })->name('reclamations.index');
    });


    /*
    |--------------------------------------------------------------------------
    | ESPACE FONCTIONNALITÉS ENSEIGNANT
    |--------------------------------------------------------------------------
    */
    Route::prefix('enseignant')->name('enseignant.')->group(function () {
        
        Route::get('/tableau_bord', [EnseignantDashboardController::class, 'tableau_bord'])->name('tableau_bord');
        
        Route::get('/profil', function () {
            return view('enseignant.profil', ['enseignant' => auth()->user()->enseignant]);
        })->name('profil');
        
        Route::put('/profil/update', function (Request $request) {
            return back()->with('success', 'Informations personnelles mises à jour !');
        })->name('profil.update');

        // Ressources partagées
        Route::get('/ressources', [EnseignantDashboardController::class, 'showRessources'])->name('ressources.index');
        Route::post('/ressources', [EnseignantDashboardController::class, 'storeRessource'])->name('ressources.store');
        Route::get('/ressources/download/{id}', [EnseignantDashboardController::class, 'downloadRessource'])->name('ressources.download');
        Route::put('/ressources/update/{id}', [EnseignantDashboardController::class, 'updateRessource'])->name('ressources.update');
        Route::delete('/ressources/destroy/{id}', [EnseignantDashboardController::class, 'destroyRessource'])->name('ressources.destroy');
    });


    /*
    |--------------------------------------------------------------------------
    | ESPACE FONCTIONNALITÉS CHEF DE DÉPARTEMENT
    |--------------------------------------------------------------------------
    */
    Route::prefix('departement')->name('departement.')->group(function () {
        
        Route::get('/tableau_bord', [DepartementDashboardController::class, 'index'])->name('tableau_bord');
        Route::post('/filieres', [DepartementDashboardController::class, 'storeFiliere'])->name('filieres.store');
        Route::post('/calendriers-evaluations', [DepartementDashboardController::class, 'storeCalendrierEvaluation'])->name('calendriers_evaluations.store');
        
        // Emploi du temps
        Route::get('/emploi-du-temps/configuration', function () { return view('departement.emploi_du_temps.config'); })->name('emploi_du_temps.config');
        Route::post('/emploi-du-temps/planifier', function (Request $request) {
            return back()->with('success', "Le cours a été planifié avec succès.");
        })->name('emploi_du_temps.planifier');
    });

    Route::get('/departement/emploi-du-temps', function () {
        return "Gestion des emplois du temps";
    })->name('gestion.emploi_du_temps.index');

});