<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un département</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2>Modifier un département</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('gestion.editer_departement.update', $departement) }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Code département</label>
                <input type="text" name="code" class="form-control" value="{{ old('code', $departement->code) }}" required>
            </div>
            <div class="col-md-8 mb-3">
                <label class="form-label">Nom département</label>
                <input type="text" name="nom" class="form-control" value="{{ old('nom', $departement->nom) }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">Chef de département</label>
                <input type="text" name="chef_nom" class="form-control" value="{{ old('chef_nom', $departement->chef_nom) }}" placeholder="Nom et prénom du chef de département">
            </div>
        </div>

        <h5 class="mt-3">Université</h5>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">Code université</label>
                <input type="text" name="code_univ" class="form-control" value="{{ old('code_univ', $departement->universite->code_univ ?? '') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Nom université</label>
                <input type="text" name="nom_universite" class="form-control" value="{{ old('nom_universite', $departement->universite->nom_universite ?? '') }}" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Ville</label>
                <input type="text" name="ville" class="form-control" value="{{ old('ville', $departement->universite->ville ?? '') }}" required>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">Enregistrer</button>
            <a href="{{ route('gestion.liste_departement') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>