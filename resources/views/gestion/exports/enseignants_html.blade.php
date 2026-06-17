<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Enseignants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; padding: 20px; }
        table { background-color: white; }
        th { background-color: #1e293b; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                <h2 class="mb-0">Liste des Enseignants</h2>
                <small>Généré le {{ date('d/m/Y H:i') }}</small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Nom d'utilisateur</th>
                                <th>Matricule</th>
                                <th>Grade</th>
                                <th>Téléphone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enseignants ?? [] as $index => $enseignant)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $enseignant->user->name ?? '—' }}</td>
                                    <td>{{ $enseignant->user->email ?? '—' }}</td>
                                    <td>{{ $enseignant->user->username ?? '—' }}</td>
                                    <td>{{ $enseignant->matricule_fonctionnaire ?? '—' }}</td>
                                    <td>{{ $enseignant->grade ?? '—' }}</td>
                                    <td>{{ $enseignant->telephone ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Aucun enseignant trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-muted">
                <small>Total : {{ count($enseignants ?? []) }} enseignant(s)</small>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
