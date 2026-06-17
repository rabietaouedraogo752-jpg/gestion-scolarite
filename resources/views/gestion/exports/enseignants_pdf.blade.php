<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Enseignants</title>
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
    <h1>Liste des Enseignants</h1>
    <p>Généré le {{ date('d/m/Y H:i') }}</p>

    <table>
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
                    <td colspan="7" style="text-align: center;">Aucun enseignant trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
