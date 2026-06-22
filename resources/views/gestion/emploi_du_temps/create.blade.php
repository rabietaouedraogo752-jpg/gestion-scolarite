@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="d-flex align-items-center mb-4">
                <span class="fs-2 me-2">➕</span>
                <h1 class="h2 mb-0">Ajouter un cours - {{ $filiere->nom_filiere ?? $filiere->nom }}</h1>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('gestion.emploi_du_temps.store', ['filiere' => $filiere->id]) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="niveau_id" class="form-label fw-bold">Niveau</label>
                            <select name="niveau_id" id="niveau_id" class="form-control form-select">
                                <option value="">-- Sélectionner un niveau --</option>
                                @foreach($niveaux as $niv)
                                    <option value="{{ $niv->id }}" {{ $niv->id == $niveau_id ? 'selected' : '' }}>
                                        {{ $niv->nom_niveau ?? $niv->intitule ?? $niv->code_niveau }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="jour" class="form-label fw-bold">Jour</label>
                            <select name="jour" id="jour" class="form-control form-select">
                                <option value="">-- Sélectionner un jour --</option>
                                <option value="Lundi">Lundi</option>
                                <option value="Mardi">Mardi</option>
                                <option value="Mercredi">Mercredi</option>
                                <option value="Jeudi">Jeudi</option>
                                <option value="Vendredi">Vendredi</option>
                                <option value="Samedi">Samedi</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="heure_debut" class="form-label fw-bold">Heure de début</label>
                                <input type="time" name="heure_debut" id="heure_debut" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="heure_fin" class="form-label fw-bold">Heure de fin</label>
                                <input type="time" name="heure_fin" id="heure_fin" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="matiere" class="form-label fw-bold">Matière</label>
                            <input type="text" name="matiere" id="matiere" class="form-control" placeholder="Ex: Algèbre, Base de données...">
                        </div>

                        <div class="mb-3">
                            <label for="salle" class="form-label fw-bold">Salle</label>
                            <input type="text" name="salle" id="salle" class="form-control" placeholder="Ex: Salle 102, Amphi A...">
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">Enregistrer le cours</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection