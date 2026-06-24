<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card p-4">
        <h2>Créer un nouvel étudiant</h2>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('gestion.creer_etudiant.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nom complet</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Matricule</label>
                <input type="text" name="matricule" class="form-control" value="{{ old('matricule') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Genre</label>
                <select name="genre" class="form-select">
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Filière</label>
                    <input type="text" name="filiere" class="form-control" value="{{ old('filiere') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Niveau</label>
                    <input type="text" name="niveau" class="form-control" value="{{ old('niveau') }}" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Année académique</label>
                <input type="text" name="annee_academique" class="form-control" placeholder="2025-2026" value="{{ old('annee_academique') }}" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date de naissance</label>
                    <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Lieu de naissance</label>
                    <input type="text" name="lieu_naissance" class="form-control" value="{{ old('lieu_naissance') }}" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
</div>

</body>
</html>