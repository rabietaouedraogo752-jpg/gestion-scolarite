<?php

namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InformationController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:200',
            'contenu' => 'required|string|max:5000',
            'visibilite' => 'required|in:public,prive',
            'categorie' => 'nullable|string|max:50',
            'fichier' => 'nullable|file|max:10240',
        ]);

        $information = new Information([
            'titre' => $data['titre'],
            'contenu' => $data['contenu'],
            'visibilite' => $data['visibilite'],
            'categorie' => $data['categorie'] ?? 'annonce',
        ]);
        $information->user_id = Auth::id();

        if ($request->hasFile('fichier')) {
            $information->fichier = $request->file('fichier')->store('informations', 'public');
        }

        $information->save();

        return back()->with('success', 'Information publiée dans l’espace info.');
    }

    public function destroy(Information $information)
    {
        if ($information->user_id !== null && $information->user_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez supprimer que vos propres informations.');
        }

        $information->delete();

        return back()->with('success', 'Information supprimée.');
    }
}
