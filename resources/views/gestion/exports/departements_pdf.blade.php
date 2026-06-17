<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Départements</title>
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
    <h1>Liste des Départements</h1>
    <p>Généré le {{ date('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Nom</th>
                <th>Code université</th>
                <th>Université</th>
                <th>Ville</th>
            </tr>
        </thead>
        <tbody>
            @forelse($departements ?? [] as $index => $departement)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $departement->code ?? '—' }}</td>
                    <td>{{ $departement->nom ?? '—' }}</td>
                    <td>{{ $departement->universite->code_univ ?? '—' }}</td>
                    <td>{{ $departement->universite->nom_universite ?? '—' }}</td>
                    <td>{{ $departement->universite->ville ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Aucun département trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
