<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des étudiants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .navbar-custom { background-color: #1e293b; }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link { color: white; }
        .table-card { border-radius: 14px; box-shadow: 0 0 20px rgba(15, 23, 42, 0.08); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/admin/tableau_bord"><i class="bi bi-arrow-left-circle me-2"></i> Retour au tableau de bord</a>
            <div class="collapse navbar-collapse justify-content-end">
                <span class="navbar-text text-white">
                    Gestion des étudiants
                </span>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card table-card bg-white p-4 mb-4">
            @if(session('credentials'))
                @php $c = session('credentials'); @endphp
                <div class="alert alert-info">
                    <strong>Identifiants générés :</strong>
                    <ul class="mb-0">
                        <li>Nom d'utilisateur : <code>{{ $c['username'] }}</code></li>
                        <li>Mot de passe : <code>{{ $c['password'] }}</code></li>
                    </ul>
                    <small class="text-muted">Demande à l'étudiant de changer son mot de passe après première connexion.</small>
                </div>
            @endif
                <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="fw-bold mb-0">Liste des étudiants</h2>
                    <p class="text-muted">Toutes les inscriptions et le suivi des étudiants.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('gestion.creer_etudiant') }}" class="btn btn-primary"><i class="bi bi-person-plus me-2"></i> Ajouter un étudiant</a>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-download me-2"></i> Exporter
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('gestion.export.excel') }}"><i class="bi bi-file-earmark-excel me-2"></i> Excel (.xlsx)</a></li>
                            <li><a class="dropdown-item" href="{{ route('gestion.export.pdf') }}"><i class="bi bi-file-earmark-pdf me-2"></i> PDF</a></li>
                            <li><a class="dropdown-item" href="{{ route('gestion.export.word') }}"><i class="bi bi-file-earmark-word me-2"></i> Word (.docx)</a></li>
                            <li><a class="dropdown-item" href="{{ route('gestion.export.html') }}"><i class="bi bi-file-earmark-code me-2"></i> HTML</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <form id="studentFilters" method="GET" action="{{ route('gestion.liste_etudiant') }}" class="row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="form-label small text-muted">Filtrer par filière</label>
                    <select id="filterFiliere" name="filiere_id" class="form-select">
                        <option value="">Toutes les filières</option>
                        @foreach($filieres as $filiere)
                            <option value="{{ $filiere->id }}" @selected((string) $filiereId === (string) $filiere->id)>
                                {{ $filiere->nom_filiere }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label small text-muted">Filtrer par niveau</label>
                    <select id="filterNiveau" name="niveau_id" class="form-select">
                        <option value="">Tous les niveaux</option>
                        @foreach($niveaux as $niveau)
                            <option value="{{ $niveau->id }}" @selected((string) $niveauId === (string) $niveau->id)>
                                {{ $niveau->code_niveau }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2 mb-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-2"></i> Filtrer
                    </button>
                    <a href="{{ route('gestion.liste_etudiant') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>INE</th>
                            <th>Accès</th>
                            <th>Filière</th>
                            <th>Niveau</th>
                            <th>Année</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($etudiants ?? [] as $index => $et)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $et->user->name ?? '—' }}</td>
                                <td>{{ $et->matricule }}</td>
                                <td>
                                    <div><strong>Nom d'utilisateur:</strong> {{ $et->user->username ?? '—' }}</div>
                                    <div><strong>Mot de passe:</strong> {{ $et->generated_password ?? '—' }}</div>
                                </td>
                                <td>{{ $et->filiere->nom_filiere ?? '—' }}</td>
                                <td>{{ $et->niveau->code_niveau ?? '—' }}</td>
                                <td>{{ $et->annee_debut ? date('Y', strtotime($et->annee_debut)).'-'.date('Y', strtotime($et->annee_fin)) : '—' }}</td>
                                <td><span class="badge bg-success">Actif</span></td>
                                <td>
                                    <a href="{{ route('gestion.editer_etudiant', $et) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i> Modifier
                                    </a>
                                </td>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filtersForm = document.getElementById('studentFilters');
            document.querySelectorAll('#filterFiliere, #filterNiveau').forEach(function (select) {
                select.addEventListener('change', function () {
                    filtersForm.submit();
                });
            });
        });
    </script>
</body>
</html>
