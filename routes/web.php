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
Route::get('/admin/compte', function () { return view('admin.compte'); 
});