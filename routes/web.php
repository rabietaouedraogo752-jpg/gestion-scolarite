<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Str;          

/* --- MODÈLES ET CONTROLLERS --- */
use App\Models\User;
use App\Models\Etudiant;
use App\Models\Enseignant;
use App\Models\UfrInstitut;
use App\Models\PendingInscription;
use App\Models\Information;
<<<<<<< HEAD
use App\Http\Controllers\DepartementDashboardController;
use App\Http\Controllers\EnseignantDashboardController;
=======
use App\Http\Controllers\InformationController;
use App\Http\Controllers\Gestion\EtudiantController;
use App\Http\Controllers\Gestion\EnseignantController;
use App\Http\Controllers\Gestion\DepartementController;
use App\Http\Controllers\Gestion\PendingInscriptionController;
use App\Http\Controllers\Auth\LoginController;

/* --- PAGES PUBLIQUES ET TABLEAUX DE BORD --- */
Route::get('/informations', [InformationController::class, 'index'])->name('informations.index');

>>>>>>> a3ed8c694b7b79086ea386a6440a9e3ec1b421bc
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

<<<<<<< HEAD
Route::get('/enseignant/tableau_bord', [EnseignantDashboardController::class, 'tableau_bord'])
    ->middleware('auth')
    ->name('enseignant.tableau_bord');
    
Route::get('/departement/tableau_bord', [\App\Http\Controllers\DepartementDashboardController::class, 'index'])->name('departement.tableau_bord');
Route::post('/departement/filieres', [\App\Http\Controllers\DepartementDashboardController::class, 'storeFiliere'])->name('departement.filieres.store');
Route::post('/departement/calendriers-evaluations', [\App\Http\Controllers\DepartementDashboardController::class, 'storeCalendrierEvaluation'])->name('departement.calendriers_evaluations.store');
Route::get('/admin/compte', function () { return view('admin.compte'); });
use App\Http\Controllers\Gestion\EtudiantController;
use App\Http\Controllers\Gestion\EnseignantController;
use App\Http\Controllers\Gestion\DepartementController;
use App\Http\Controllers\Gestion\PendingInscriptionController;
use App\Http\Controllers\Gestion\EmploiDuTempsController;
use App\Http\Controllers\Auth\InscriptionController;
use App\Http\Controllers\Auth\LoginController;
=======
Route::get('/enseignant/tableau_bord', [\App\Http\Controllers\EnseignantEmploiDuTempsController::class, 'index'])->middleware('auth')->name('enseignant.tableau_bord');
>>>>>>> a3ed8c694b7b79086ea386a6440a9e3ec1b421bc

/* --- TABLEAU DE BORD DU CHEF DE DÉPARTEMENT --- */
Route::get('/departement/tableau_bord', function () {
    $total_etudiants = \App\Models\Etudiant::count();
    $total_enseignants = \App\Models\Enseignant::count();
    Route::get('/departement/tableau_bord', function () {
    return view('departement.tableau_bord');
})->name('departement.tableau_bord');
    
    return view('departement.tableau_bord', [
        'etudiants' => $total_etudiants,
        'enseignants' => $total_enseignants,
        'activeTab' => 'dashboard'
    ]);
})->middleware('auth')->name('departement.tableau_board');


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

// --- Édition / Suppression ---
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
Route::post('/gestion/import/etudiants', [EtudiantController::class, 'import'])->name('gestion.import.etudiants');

// --- Exports Enseignants ---
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
Route::get('/gestion/export/departements/all', [DepartementController::class, 'exportExcel'])->name('gestion.export.departements.all');

// --- Inscriptions ---
Route::get('/gestion/inscriptions-en-attente', [PendingInscriptionController::class, 'index'])->name('gestion.inscriptions.index');
Route::post('/gestion/inscriptions-en-attente/{inscription}/approver', [PendingInscriptionController::class, 'approve'])->name('gestion.inscriptions.approve');

/* --- AUTHENTIFICATION --- */
Route::get('/connexion', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/connexion', [LoginController::class, 'login'])->name('login.post');
Route::post('/deconnexion', [LoginController::class, 'logout'])->name('logout');
/* ==========================================================================
   NOUVELLES FONCTIONNALITÉS : ÉTUDIANT, ENSEIGNANT ET CHEF DE DÉPARTEMENT
   ========================================================================== */

Route::middleware(['auth'])->group(function () {

    /* --- 1. FONCTIONNALITÉS ÉTUDIANT --- */
    Route::prefix('etudiant')->name('etudiant.')->group(function () {

        Route::get('/tableau_bord', function () {
            $user = auth()->user();
            $etudiant = $user ? $user->etudiant : null;
            $emplois = $etudiant && $etudiant->filiere ? $etudiant->filiere->emploisDuTemps()
                ->where('niveau_id', $etudiant->niveau_id)
                ->orderBy('jour')->orderBy('heure_debut')->get() : [];
            $informations = App\Models\Information::with('auteur')->whereIn('visibilite', ['public', 'etudiant'])->latest()->get();
            return view('etudiant.tableau_bord', compact('emplois', 'etudiant', 'informations'));
        })->name('tableau_bord');

        Route::get('/profil', function () {
            $user = auth()->user();
            $etudiant = $user ? $user->etudiant : null;
            return view('etudiant.profil', compact('etudiant'));
        })->name('profil');

        // COLLES UNIQUEMENT CETTE ROUTE ICI :
        Route::put('/profil/update', function (Illuminate\Http\Request $request) {
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
                $user->password = Illuminate\Support\Facades\Hash::make($request->password);
            }
            $user->save();

            $etudiant->update([
                'telephone' => $request->whatsapp,
            ]);

            return back()->with('success', 'Votre profil a été mis à jour avec succès !');
        })->name('profil.update');

        Route::get('/notes', function () {
            return view('etudiant.notes');
        })->name('notes');

        Route::get('/reclamations', function () {
            return "Page des réclamations en cours de développement";
        })->name('reclamations.index');

    });
    /* --- 2. FONCTIONNALITÉS ENSEIGNANT --- */
    Route::prefix('enseignant')->name('enseignant.')->group(function () {
        
        Route::get('/profil', function () {
            return view('enseignant.profil', ['enseignant' => auth()->user()->enseignant]);
        })->name('profil');
        
        Route::put('/profil/update', function (Request $request) {
            return back()->with('success', 'Informations personnelles mises à jour !');
        })->name('profil.update');

        Route::get('/ressources', function () {
            return view('enseignant.ressources.index');
        })->name('ressources.index');
        
        Route::post('/ressources/store', function (Request $request) {
            return back()->with('success', 'Le document a été partagé avec succès.');
        })->name('ressources.store');
    });

    /* --- 3. FONCTIONNALITÉS CHEF DE DÉPARTEMENT --- */
    Route::prefix('departement')->name('departement.')->group(function () {
        
        // Route du tableau de bord du département
        Route::get('/tableau_bord', function () {
            return view('departement.tableau_bord');
        })->name('tableau_bord');

        // Route de configuration
        Route::get('/emploi-du-temps/configuration', function () {
            return view('departement.emploi_du_temps.config');
        })->name('emploi_du_temps.config');
        
        // Route de planification
        Route::post('/emploi-du-temps/planifier', function (Request $request) {
            return back()->with('success', "Le cours a été planifié avec succès dans l'emploi du temps.");
        })->name('emploi_du_temps.planifier');
        
    }); // Fermeture du groupe 'departement.'

    // Route externe (sans le préfixe de nom 'departement.') mais TOUJOURS sous middleware 'auth'
    Route::get('/departement/emploi-du-temps', function () {
        return "Gestion des emplois du temps";
    })->name('gestion.emploi_du_temps.index');
    /* --- 5. ROUTES DE CRÉATION (ÉTUDIANT & ENSEIGNANT) --- */
    
    // Route POST pour enregistrer un nouvel étudiant
    Route::post('/gestion/creer_etudiant', function (Illuminate\Http\Request $request) {
        // C'est ici que se fera l'enregistrement de l'étudiant dans la base de données plus tard
        return back()->with('success', "L'étudiant a été créé avec succès !");
    })->name('gestion.creer_etudiant.store');

    // Route POST pour enregistrer un nouvel enseignant
    Route::post('/gestion/creer_enseignant', function (Illuminate\Http\Request $request) {
        // C'est ici que se fera l'enregistrement de l'enseignant dans la base de données plus tard
        return back()->with('success', "L'enseignant a été créé avec succès !");
    })->name('gestion.creer_enseignant.store');
/* --- 5. ROUTES DE CRÉATION (ÉTUDIANT, ENSEIGNANT, CHEF DE DÉPARTEMENT) --- */
    
    // 1. Création Étudiant
    Route::post('/gestion/creer_etudiant', function (Request $request) {
    // 1. Validation : On s'assure que tout est présent et valide
    $validated = $request->validate([
        'name'             => 'required|string|max:255',
        'email'            => 'required|email|unique:users,email',
        'matricule'        => 'required|string|max:50',
        'genre'            => 'required|string|max:1',
        'filiere'          => 'required|integer', // Doit être un ID valide en base
        'niveau'           => 'required|integer', // Doit être un ID valide en base
        'date_naissance'   => 'required|date',
        'lieu_naissance'   => 'required|string|max:255',
        'annee_academique' => 'required|string|max:20', // Ex: "2025-2026"
    ]);

    // 2. Création de l'utilisateur
    $password = Str::random(8);
    $user = User::create([
        'name'     => $validated['name'],
        'email'    => $validated['email'],
        'password' => Hash::make($password),
    ]);

<<<<<<< HEAD
    return redirect()->back()->with('success', 'Information publiée avec succès !');
})->name('enseignant.informations.store')->middleware('auth');


Route::get('/enseignant/ressources', [EnseignantDashboardController::class, 'showRessources'])->name('ressources.index');
Route::post('/enseignant/ressources', [EnseignantDashboardController::class, 'storeRessource'])->name('ressources.store');
Route::get('/enseignant/ressources/download/{id}', [EnseignantDashboardController::class, 'downloadRessource'])->name('ressources.download');
Route::post('/enseignant/ressources/store', [EnseignantDashboardController::class, 'storeRessource'])->name('enseignant.ressources.store');
Route::put('/enseignant/ressources/update/{id}', [EnseignantDashboardController::class, 'updateRessource'])->name('enseignant.ressources.update');
Route::delete('/enseignant/ressources/destroy/{id}', [EnseignantDashboardController::class, 'destroyRessource'])->name('enseignant.ressources.destroy');
=======
    // 3. Extraction des années à partir du champ "2025-2026"
    // On coupe la chaîne au niveau du tiret
    $annees = explode('-', $validated['annee_academique']);
    $anneeDebut = ($annees[0] ?? date('Y')) . '-09-01'; // Par défaut 1er sept
    $anneeFin   = ($annees[1] ?? (date('Y') + 1)) . '-08-31';

    // 4. Création de l'étudiant
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
    // 2. Création Enseignant
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

    // 3. Création Chef de Département
    Route::post('/gestion/creer_departement', function (Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);
        $password = Str::random(8);
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'role' => 'chef_departement',
        ]);
        return back()->with('success', "Chef de département créé ! Mot de passe : " . $password);
    })->name('gestion.creer_departement.store');
}); // <--- CETTE FERMETURE DOIT RESTER ICI TOUT EN BAS POUR LE MIDDLEWARE AUTH !
>>>>>>> a3ed8c694b7b79086ea386a6440a9e3ec1b421bc
