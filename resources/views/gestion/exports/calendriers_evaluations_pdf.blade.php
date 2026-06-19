<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Calendriers d'évaluations</title>
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
    <h1>Calendriers d'évaluations</h1>
    <p>Généré le {{ date('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Intitulé</th>
                <th>Filière</th>
                <th>Niveau</th>
                <th>Type</th>
                <th>Début</th>
                <th>Fin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($calendriers ?? [] as $index => $calendrier)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $calendrier->intitule }}</td>
                    <td>{{ $calendrier->filiere->nom_filiere ?? '—' }}</td>
                    <td>{{ $calendrier->niveau->code_niveau ?? '—' }}</td>
                    <td>{{ $calendrier->type }}</td>
                    <td>{{ $calendrier->date_debut?->format('d/m/Y') }}</td>
                    <td>{{ $calendrier->date_fin?->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Aucun calendrier trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
