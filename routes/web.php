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


Route::get('/admin/tableau_bord', function () {
    return view('admin.tableau_bord');
})->name('admin.tableau_bord');

Route::get('/etudiant/tableau_bord', function () {
    return view('etudiant.tableau_bord');
})->name('etudiant.tableau_bord');

Route::get('/enseignant/tableau_bord', function () { return view('enseignant.tableau_bord'); });
Route::get('/departement/tableau_bord', function () { return view('departement.tableau_bord'); });
Route::get('/admin/compte', function () { return view('admin.compte'); });
use App\Http\Controllers\Gestion\EtudiantController;
use App\Http\Controllers\Gestion\EnseignantController;
use App\Http\Controllers\Gestion\DepartementController;

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
