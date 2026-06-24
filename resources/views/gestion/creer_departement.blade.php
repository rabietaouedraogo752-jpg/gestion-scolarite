<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un chef de département</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow-sm p-4 bg-white" style="border-radius: 12px;">
        <h2 class="fw-bold mb-4">Créer un chef de département</h2>

        {{-- Affichage des messages d'erreur de Laravel --}}
        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORMULAIRE : L'action pointe vers la méthode store --}}
        <form action="{{ route('gestion.creer_departement.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="form-label fw-bold">Sélectionner le département</label>
                <select name="departement_id" class="form-select form-select-lg" required>
                    <option value="">-- Choisir un département existant --</option>
                    @foreach($departements ?? [] as $dept)
                        <option value="{{ $dept->id }}" @selected(old('departement_id') == $dept->id)>
                            [{{ $dept->code ?? 'N/A' }}] {{ $dept->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">Nom complet du Chef de département</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Nom et prénom" required>
                </div>
                
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">Adresse Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="exemple@univ.bf" required>
                </div>
            </div>

            <h5 class="mt-2 text-muted fw-bold">Université</h5>
            <div class="row bg-light p-3 rounded border mb-4 g-2">
                <div class="col-md-3">
                    <label class="form-label small text-secondary m-0">Code université</label>
                    <input type="text" class="form-control bg-white" value="UVBF" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-secondary m-0">Nom université</label>
                    <input type="text" class="form-control bg-white" value="Université Virtuelle" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-secondary m-0">Ville</label>
                    <input type="text" class="form-control bg-white" value="OUAGADOUGOU" readonly>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-bold">Créer et assigner</button>
                <a href="{{ route('gestion.liste_departement') }}" class="btn btn-secondary px-4">Annuler</a>
            </div>

        </form> {{-- FIN DU FORMULAIRE --}}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>