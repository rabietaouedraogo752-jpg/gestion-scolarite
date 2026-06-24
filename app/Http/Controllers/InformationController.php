<?php

namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Http\Request;

class InformationController extends Controller
{
   // Affiche la liste des informations avec leurs auteurs.//
    public function index()
    {
        // On récupère toutes les informations en chargeant aussi la relation 'auteur'
        // pour éviter le problème de requêtes multiples (Eager Loading)
        $informations = Information::with('auteur')->latest()->get();

        // On retourne la vue (ajustez le nom 'informations.index' selon votre dossier)
        return view('informations.index', compact('informations'));
    }
}