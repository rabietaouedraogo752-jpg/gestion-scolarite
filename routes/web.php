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

Route::get('/gestion/liste_etudiant', [EtudiantController::class, 'index'])->name('gestion.liste_etudiant');
Route::get('/gestion/liste_enseignant', function () { return view('gestion.liste_enseignant'); })->name('gestion.liste_enseignant');
Route::get('/gestion/liste_departement', function () { return view('gestion.liste_departement'); })->name('gestion.liste_departement');

Route::get('/gestion/creer_etudiant', [EtudiantController::class, 'create'])->name('gestion.creer_etudiant');
Route::post('/gestion/creer_etudiant', [EtudiantController::class, 'store'])->name('gestion.creer_etudiant.store');
Route::get('/gestion/editer_etudiant/{etudiant}', [EtudiantController::class, 'edit'])->name('gestion.editer_etudiant');
Route::put('/gestion/editer_etudiant/{etudiant}', [EtudiantController::class, 'update'])->name('gestion.editer_etudiant.update');
// Exports
Route::get('/gestion/export/excel', [EtudiantController::class, 'exportExcel'])->name('gestion.export.excel');
Route::get('/gestion/export/pdf', [EtudiantController::class, 'exportPdf'])->name('gestion.export.pdf');
Route::get('/gestion/export/word', [EtudiantController::class, 'exportWord'])->name('gestion.export.word');
Route::get('/gestion/export/html', [EtudiantController::class, 'exportHtml'])->name('gestion.export.html');
