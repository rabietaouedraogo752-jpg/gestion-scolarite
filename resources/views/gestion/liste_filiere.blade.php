<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des filières par département</title>
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
                    Gestion des structures académiques
                </span>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card table-card bg-white p-4 mb-4">
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="fw-bold mb-0">Liste des Départements & Filières</h2>
                    <p class="text-muted">Consultez et gérez les filières rattachées à chaque département.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDeptFiliereModal">
                        <i class="bi bi-plus-circle me-2"></i> Nouveau Département & Filières
                    </button>
                    <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#importFiliereModal"><i class="bi bi-upload me-2"></i> Importer</button>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-download me-2"></i> Exporter
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('gestion.export.departements.excel') }}"><i class="bi bi-file-earmark-excel me-2"></i> Excel (.xlsx)</a></li>
                            <li><a class="dropdown-item" href="{{ route('gestion.export.departements.pdf') }}"><i class="bi bi-file-earmark-pdf me-2"></i> PDF</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form id="filiereFilters" method="GET" action="{{ route('gestion.liste_filiere') }}" class="row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="form-label small text-muted">Filtrer par Département</label>
                    <select id="filterDepartement" name="departement_id" class="form-select">
                        <option value="">Tous les départements</option>
                        @foreach($departements ?? [] as $dept)
                            <option value="{{ $dept->id }}" @selected(request('departement_id') == $dept->id)>
                                {{ $dept->nom }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end gap-2 mb-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-2"></i> Filtrer
                    </button>
                    <a href="{{ route('gestion.liste_filiere') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Département</th>
                            <th>Filière</th>
                            <th>Code / Sigle</th>
                            <th>Étudiants</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $dernierDepartementId = null;
                        @endphp

                        @forelse($filieres ?? [] as $index => $fil)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($fil->departement)
                                        @if($fil->departement->id !== $dernierDepartementId)
                                            <strong>{{ $fil->departement->nom }}</strong>
                                            @php 
                                                $dernierDepartementId = $fil->departement->id; 
                                            @endphp
                                        @else
                                            <span class="text-muted ps-2">»</span>
                                        @endif
                                    @else
                                        <span class="text-danger fw-bold">Aucun département</span>
                                    @endif
                                </td>
                                <td>{{ $fil->nom_filiere }}</td>
                                <td><span class="badge bg-secondary">{{ $fil->code_filiere ?? 'N/A' }}</span></td>
                                <td>{{ $fil->etudiants_count ?? ($fil->etudiants() ? $fil->etudiants()->count() : 0) }} étudiant(s)</td>
                                <td>
                                    <form action="{{ route('gestion.filiere.destroy', $fil->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette filière ?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Aucune donnée trouvée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="importFiliereModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Importer des structures</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('gestion.import.departements') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Fichier de structure</label>
                            <input type="file" name="file" class="form-control" accept=".pdf,.xlsx,.xls,.docx,.doc" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Importer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createDeptFiliereModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title"><i class="bi bi-bank me-2"></i> Créer un Département et ses Filières</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('gestion.creer_departement_filiere.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <h5 class="text-primary fw-bold mb-3">1. Informations du Département</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nom du Département / UFR</label>
                                <input type="text" name="nom_ufr" class="form-control" placeholder="Ex: Sciences et Technologies" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Sigle / Code UFR</label>
                                <input type="text" name="code_ufr" class="form-control" placeholder="Ex: ST">
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="text-primary fw-bold mb-0">2. Filières associées</h5>
                            <button type="button" id="add-filiere-btn" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus-lg"></i> Ajouter une autre filière
                            </button>
                        </div>

                        <div id="filieres-container">
                            <div class="row g-3 filiere-row mb-3 pb-3 border-bottom">
                                <div class="col-md-7">
                                    <label class="form-label small text-muted">Nom de la filière</label>
                                    <input type="text" name="filieres[0][nom]" class="form-control" placeholder="Ex: Licence en Informatique" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small text-muted">Code Filière</label>
                                    <input type="text" name="filieres[0][code]" class="form-control" placeholder="Ex: L-INFO" required>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-danger remove-filiere-btn" disabled>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success"><i class="bi bi-check-circle me-1"></i> Enregistrer tout</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Soumission automatique du filtre de département
            const filtersForm = document.getElementById('filiereFilters');
            const selectDept = document.getElementById('filterDepartement');
            if(selectDept) {
                selectDept.addEventListener('change', function () {
                    filtersForm.submit();
                });
            }

            // Gestion de l'ajout dynamique de plusieurs filières
            const container = document.getElementById('filieres-container');
            const addBtn = document.getElementById('add-filiere-btn');

            if (addBtn && container) {
                addBtn.addEventListener('click', function () {
                    const uniqueIndex = Date.now(); 
                    
                    const newRow = document.createElement('div');
                    newRow.className = 'row g-3 filiere-row mb-3 pb-3 border-bottom';
                    newRow.innerHTML = `
                        <div class="col-md-7">
                            <label class="form-label small text-muted">Nom de la filière</label>
                            <input type="text" name="filieres[${uniqueIndex}][nom]" class="form-control" placeholder="Ex: Licence en..." required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Code Filière</label>
                            <input type="text" name="filieres[${uniqueIndex}][code]" class="form-control" placeholder="Ex: L-INFO">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-danger remove-filiere-btn">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    `;
                    container.appendChild(newRow);
                });

                // Supprimer une ligne de filière ajoutée par erreur avant soumission
                container.addEventListener('click', function (e) {
                    if (e.target.closest('.remove-filiere-btn')) {
                        e.target.closest('.filiere-row').remove();
                    }
                });
            }
        });
    </script>
</body>
</html>