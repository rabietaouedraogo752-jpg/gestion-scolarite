<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Étudiants</title>
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
                <h2 class="mb-0">Liste des Étudiants</h2>
                <small>Généré le {{ date('d/m/Y H:i') }}</small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>INE</th>
                                <th>Email</th>
                                <th>Nom d'utilisateur</th>
                                <th>Filière</th>
                                <th>Niveau</th>
                                <th>Année</th>
                                <th>Genre</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($etudiants ?? [] as $index => $et)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $et->user->name ?? '—' }}</td>
                                    <td>{{ $et->matricule ?? '—' }}</td>
                                    <td>{{ $et->user->email ?? '—' }}</td>
                                    <td>{{ $et->user->username ?? '—' }}</td>
                                    <td>{{ $et->filiere->nom_filiere ?? '—' }}</td>
                                    <td>{{ $et->niveau->code_niveau ?? '—' }}</td>
                                    <td>{{ $et->annee_debut ? date('Y', strtotime($et->annee_debut)).'-'.date('Y', strtotime($et->annee_fin)) : '—' }}</td>
                                    <td>{{ $et->genre ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Aucun étudiant trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-muted">
                <small>Total : {{ count($etudiants ?? []) }} étudiant(s)</small>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
