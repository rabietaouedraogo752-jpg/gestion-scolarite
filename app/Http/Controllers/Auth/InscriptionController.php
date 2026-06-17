<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PendingInscription;
use Illuminate\Http\Request;

class InscriptionController extends Controller
{
    /**
     * Affiche le formulaire d'inscription
     */
    public function show()
    {
        return view('inscription');
    }

    /**
     * Enregistre les données d'inscription en attente
     */
    public function store(Request $request)
    {
        // Validation des données
        $data = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:pending_inscriptions,email|unique:users,email',
            'ine' => 'required|string|max:50|unique:pending_inscriptions,ine',
            'date_naissance' => 'required|date',
            'telephone' => 'nullable|string|max:20',
        ], [
            'nom.required' => 'Le nom est requis.',
            'nom.max' => 'Le nom ne doit pas dépasser 100 caractères.',
            'prenom.required' => 'Le prénom est requis.',
            'prenom.max' => 'Le prénom ne doit pas dépasser 100 caractères.',
            'email.required' => 'L\'email est requis.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'email.unique' => 'Cet email est déjà enregistré.',
            'ine.required' => 'Le numéro INE ou matricule est requis.',
            'ine.max' => 'Le numéro INE ou matricule ne doit pas dépasser 50 caractères.',
            'ine.unique' => 'Ce numéro INE ou matricule est déjà enregistré.',
            'date_naissance.required' => 'La date de naissance est requise.',
            'date_naissance.date' => 'Veuillez entrer une date valide.',
            'telephone.max' => 'Le téléphone ne doit pas dépasser 20 caractères.',
        ]);

        // Enregistrement de l'inscription en attente
        PendingInscription::create($data);

        return back()->with('success', 'Votre inscription a été enregistrée avec succès ! Un administrateur examinera votre demande et vous enverra vos identifiants d\'accès par email.');
    }
}
