<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un enseignant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2>Modifier un enseignant</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('gestion.editer_enseignant.update', $enseignant) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nom complet</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $enseignant->user->name ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $enseignant->user->email ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Matricule fonctionnaire</label>
            <input type="text" name="matricule_fonctionnaire" class="form-control" value="{{ old('matricule_fonctionnaire', $enseignant->matricule_fonctionnaire) }}">
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Grade</label>
                <select name="grade" class="form-select" required>
                    <option value="">-- Choisir --</option>
                    <option value="MA" {{ old('grade', $enseignant->grade) === 'MA' ? 'selected' : '' }}>MA</option>
                    <option value="MC" {{ old('grade', $enseignant->grade) === 'MC' ? 'selected' : '' }}>MC</option>
                    <option value="PT" {{ old('grade', $enseignant->grade) === 'PT' ? 'selected' : '' }}>PT</option>
                    <option value="Vacataire" {{ old('grade', $enseignant->grade) === 'Vacataire' ? 'selected' : '' }}>Vacataire</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Téléphone</label>
                <input type="text" name="telephone" class="form-control" value="{{ old('telephone', $enseignant->telephone) }}">
            </div>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">Enregistrer</button>
            <a href="{{ route('gestion.liste_enseignant') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
