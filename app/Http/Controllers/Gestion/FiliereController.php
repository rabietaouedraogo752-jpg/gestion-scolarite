<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use Illuminate\Http\Request;

class FiliereController extends Controller
{
    // ... tes autres méthodes (index, create, store) ...

    /**
     * Affiche le formulaire de modification d'une filière.
     */
    public function edit(Filiere $filiere)
    {
        return view('gestion.filiere.edit', compact('filiere'));
    }

    /**
     * Enregistre les modifications de la filière.
     */
    public function update(Request $request, Filiere $filiere)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:filieres,code,' . $filiere->id,
            'nom' => 'required|string|max:255',
        ]);

        $filiere->update([
            'code' => $request->code,
            'nom' => $request->nom,
        ]);

        return redirect()->route('filiere.index')->with('success', 'La filière a été modifiée avec succès.');
    }

    //    Supprime une filière de la base de données.//
        public function destroy(Filiere $filiere)
    {
        // Optionnel : Vérifier si la filière est liée à des niveaux ou étudiants avant de supprimer
        $filiere->delete();

        return redirect()->route('filiere.index')->with('success', 'La filière a été supprimée avec succès.');
    }
}