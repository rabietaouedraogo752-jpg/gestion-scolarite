<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Étudiants</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #1e293b; color: white; padding: 10px; text-align: left; }
        td { border: 1px solid #ddd; padding: 8px; }
        tr:nth-child(even) { background-color: #f4f6f9; }
    </style>
</head>
<body>
    <h1>Liste des Étudiants</h1>
    <p>Généré le {{ date('d/m/Y H:i') }}</p>

    <table>
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
                    <td colspan="9" style="text-align: center;">Aucun étudiant trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
