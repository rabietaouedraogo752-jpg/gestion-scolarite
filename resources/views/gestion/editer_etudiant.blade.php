<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2>Modifier un étudiant</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('gestion.editer_etudiant.update', $etudiant) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nom complet</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $etudiant->user->name ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $etudiant->user->email ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Matricule (INE)</label>
            <input type="text" name="matricule" class="form-control" value="{{ old('matricule', $etudiant->matricule) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Genre</label>
            <select name="genre" class="form-select" required>
                <option value="">-- Choisir --</option>
                <option value="M" {{ old('genre', $etudiant->genre) == 'M' ? 'selected' : '' }}>Masculin</option>
                <option value="F" {{ old('genre', $etudiant->genre) == 'F' ? 'selected' : '' }}>Féminin</option>
            </select>
        </div>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">Filière</label>
                <input type="text" name="filiere" class="form-control" value="{{ old('filiere', $etudiant->filiere->nom_filiere ?? '') }}" placeholder="Entrez la filière" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Niveau</label>
                <input type="text" name="niveau" class="form-control" value="{{ old('niveau', $etudiant->niveau->code_niveau ?? '') }}" placeholder="Entrez le niveau" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Année académique</label>
                <input type="text" name="annee_academique" class="form-control" value="{{ old('annee_academique', optional($etudiant)->annee_debut ? date('Y', strtotime($etudiant->annee_debut)).'-'.date('Y', strtotime($etudiant->annee_fin)) : '') }}" placeholder="Ex: 2025-2026" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Date de naissance</label>
                <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance', $etudiant->date_naissance) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Lieu de naissance</label>
                <input type="text" name="lieu_naissance" class="form-control" value="{{ old('lieu_naissance', $etudiant->lieu_naissance) }}" required>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">Enregistrer</button>
            <a href="{{ route('gestion.liste_etudiant') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
