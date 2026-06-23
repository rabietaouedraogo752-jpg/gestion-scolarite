<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des départements</title>
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
            <a class="navbar-brand fw-bold" href="{{ route('admin.tableau_bord') }}">
                <i class="bi bi-arrow-left-circle me-2"></i> Retour au tableau de bord
            </a>
            <div class="collapse navbar-collapse justify-content-end">
                <span class="navbar-text text-white">Gestion des départements</span>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card table-card bg-white p-4 mb-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="fw-bold mb-0">Liste des départements</h2>
                    <p class="text-muted">Tous les départements, UFR et instituts enregistrés.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('gestion.creer_departement') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i> Ajouter un département
                    </a>
                    <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#importDepartmentModal">
                        <i class="bi bi-upload me-2"></i> Importer
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-download me-2"></i> Exporter
                        </button>
                        <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="{{ route('gestion.export.departements.excel') }}">Excel</a></li>
    <li><a class="dropdown-item" href="{{ route('gestion.export.departements.pdf') }}">PDF</a></li>
    <li><a class="dropdown-item" href="{{ route('gestion.export.departements.word') }}">Word</a></li>
    <li><a class="dropdown-item" href="{{ route('gestion.export.departements.html') }}">HTML</a></li>
</ul>
                    </div>
                </div>
            </div>

            <form id="departmentFilters" method="GET" action="{{ route('gestion.liste_departement') }}" class="row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="form-label small text-muted">Filtrer par université</label>
                    <select id="filterUniversite" name="universite_id" class="form-select">
                        <option value="">Toutes les universités</option>
                        @foreach($universites ?? [] as $universite)
                            <option value="{{ $universite->id }}" @selected(isset($universiteId) && (string) $universiteId === (string) $universite->id)>
                                {{ $universite->nom_universite }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th><th>Code</th><th>Nom</th><th>Chef</th><th>Accès</th><th>Université</th><th>Statut</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departements ?? [] as $index => $departement)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $departement->code ?? '—' }}</td>
                                <td>{{ $departement->nom ?? '—' }}</td>
                                <td>{{ $departement->chef_nom ?? 'Non assigné' }}</td>
                                <td>
                                    <div><strong>Login:</strong> {{ strtolower(Str::slug($departement->code ?? '')) }}.chef</div>
                                </td>
                                <td>{{ $departement->universite->nom_universite ?? '—' }}</td>
                                <td><span class="badge bg-success">Actif</span></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('gestion.editer_departement', $departement) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil-square"></i> Modifier
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $departement->id }}">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="deleteModal{{ $departement->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-danger">Confirmer la suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Voulez-vous vraiment supprimer <strong>{{ $departement->nom }}</strong> ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <form action="{{ route('gestion.supprimer_departement', $departement) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr><td colspan="8" class="text-center">Aucun département trouvé.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="importDepartmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Importer un fichier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('gestion.import.departements') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Importer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>