<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card p-4 shadow-sm mb-5" style="border-radius: 12px;">
        <h2 class="fw-bold text-dark mb-4">Créer un nouvel étudiant</h2>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('gestion.creer_etudiant.store') }}">
            @csrf
            
            <div class="mb-3">
                <label class="form-label fw-bold">Nom complet</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Matricule</label>
                <input type="text" name="matricule" class="form-control" value="{{ old('matricule') }}" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Genre</label>
                <select name="genre" class="form-select" required>
                    <option value="">-- Choisir le genre --</option>
                    <option value="M" @selected(old('genre') == 'M')>Masculin</option>
                    <option value="F" @selected(old('genre') == 'F')>Féminin</option>
                </select>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Filière</label>
                    <select name="filiere" class="form-select" required>
                        <option value="">-- Sélectionner la filière --</option>
                        @foreach($filieres ?? [] as $filiere)
                            <option value="{{ $filiere->id }}" @selected(old('filiere') == $filiere->id)>
                                {{ $filiere->nom_filiere }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Niveau</label>
                    <input type="text" name="niveau" class="form-control" value="{{ old('niveau') }}" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Année académique</label>
                <input type="text" name="annee_academique" class="form-control" placeholder="2025-2026" value="{{ old('annee_academique') }}" required>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Date de naissance</label>
                    <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Lieu de naissance</label>
                    <input type="text" name="lieu_naissance" class="form-control" value="{{ old('lieu_naissance') }}" required>
                </div>
            </div>
            
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary fw-bold px-4">Enregistrer</button>
                <a href="{{ route('gestion.liste_etudiant') }}" class="btn btn-secondary px-4">Annuler</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>