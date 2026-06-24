@extends('layouts.app')

@section('title', 'Espace Chef de Département')

@section('content')
@php
    $activeTab = request('tab', 'dashboard');
@endphp

<style>
    .navbar-chef { background-color: #0369a1; }
    .sidebar-dept { min-height: calc(100vh - 56px); background-color: #111827; }
    .sidebar-dept .nav-link { color: #d1d5db; border-radius: 8px; }
    .sidebar-dept .nav-link.active,
    .sidebar-dept .nav-link:hover { background-color: #1f2937; color: #fff; }
    .stat-card { border: 0; border-radius: 8px; }
    .section-card { border: 0; border-radius: 8px; }
</style>

<nav class="navbar navbar-expand-lg navbar-dark navbar-chef shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('departement.tableau_bord') }}">
            <i class="bi bi-shield-shaded me-2"></i> Espace Département
        </a>
        <div class="ms-auto">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <aside class="col-md-3 col-lg-2 sidebar-dept p-3">
            <h5 class="fw-bold text-white mb-4"><i class="bi bi-shield-shaded me-2"></i> Chef Dépt.</h5>
            <ul class="nav flex-column gap-2">
                <li><a class="nav-link {{ $activeTab === 'dashboard' ? 'active' : '' }}" href="{{ route('departement.tableau_bord') }}"><i class="bi bi-house-door me-2"></i> Tableau de bord</a></li>
                <li><a class="nav-link {{ $activeTab === 'infos' ? 'active' : '' }}" href="{{ route('departement.tableau_bord', ['tab' => 'infos']) }}"><i class="bi bi-megaphone me-2"></i> Annonces & Infos</a></li>
                <li><a class="nav-link {{ $activeTab === 'enseignants' ? 'active' : '' }}" href="{{ route('departement.tableau_bord', ['tab' => 'enseignants']) }}"><i class="bi bi-people me-2"></i> Enseignants</a></li>
                <li><a class="nav-link {{ $activeTab === 'enseignements' ? 'active' : '' }}" href="{{ route('departement.tableau_bord', ['tab' => 'enseignements']) }}"><i class="bi bi-book me-2"></i> Enseignements</a></li>
                <li><a class="nav-link" href="{{ route('gestion.emploi_du_temps.index') }}"><i class="bi bi-calendar3 me-2"></i> Emploi du temps</a></li>
                <li><a class="nav-link {{ $activeTab === 'vacations' ? 'active' : '' }}" href="{{ route('departement.tableau_bord', ['tab' => 'vacations']) }}"><i class="bi bi-clipboard-check me-2"></i> Vacations</a></li>
                <li><a class="nav-link {{ $activeTab === 'evaluations' ? 'active' : '' }}" href="{{ route('departement.tableau_bord', ['tab' => 'evaluations']) }}"><i class="bi bi-bar-chart me-2"></i> Évaluations</a></li>
            </ul>
        </aside>

        <main class="col-md-9 col-lg-10 p-4">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card stat-card shadow-sm"><div class="card-body text-center">
                        <h2 class="text-primary">{{ number_format($stats['enseignants'] ?? 0) }}</h2>
                        <p class="mb-0">Enseignants</p>
                    </div></div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card shadow-sm"><div class="card-body text-center">
                        <h2 class="text-success">{{ number_format($stats['cours'] ?? 0) }}</h2>
                        <p class="mb-0">Cours planifiés</p>
                    </div></div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card shadow-sm"><div class="card-body text-center">
                        <h2 class="text-warning">{{ number_format($stats['vacations'] ?? 0) }}</h2>
                        <p class="mb-0">Vacations</p>
                    </div></div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card shadow-sm"><div class="card-body text-center">
                        <h2 class="text-danger">{{ number_format($stats['alertes'] ?? 0) }}</h2>
                        <p class="mb-0">Alertes</p>
                    </div></div>
                </div>
            </div>

            @if ($activeTab === 'dashboard')
                <div class="card section-card shadow-sm p-4">
                    <h4 class="text-dark"><i class="bi bi-speedometer2 text-primary me-2"></i> Bienvenue sur votre Tableau de Bord</h4>
                    <p class="text-muted">Sélectionnez une option dans le menu de gauche pour gérer les enseignants, annonces, emplois du temps ou validations de vacations.</p>
                </div>

            @elseif ($activeTab === 'enseignants')
                <div class="card section-card shadow-sm">
                    <div class="card-header bg-white"><h5 class="mb-0"><i class="bi bi-people text-primary me-2"></i> Enseignants et domaines</h5></div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light"><tr><th>Nom</th><th>Email</th><th>Grade</th><th>Domaine d'enseignement</th><th>Téléphone</th></tr></thead>
                            <tbody>
                                @forelse ($enseignants ?? [] as $enseignant)
                                    <tr>
                                        <td class="fw-semibold">{{ $enseignant->user->name ?? '—' }}</td>
                                        <td>{{ $enseignant->user->email ?? '—' }}</td>
                                        <td><span class="badge bg-info text-dark">{{ $enseignant->grade ?? '—' }}</span></td>
                                        <td>{{ $enseignant->domaine_enseignement ?? 'Non renseigné' }}</td>
                                        <td>{{ $enseignant->telephone ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">Aucun enseignant enregistré.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            @elseif ($activeTab === 'enseignements')
                <div class="row g-3">
                    <div class="col-lg-12">
                        <div class="card section-card shadow-sm h-100">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="bi bi-book text-success me-2"></i> Filières de formation</h5>
                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addFiliereModal">
                                    <i class="bi bi-plus-circle me-1"></i> Ajouter
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Filière</th>
                                            <th>Niveaux</th>
                                            <th>Étudiants</th>
                                            <th>Cours</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($filieres ?? [] as $filiere)
                                            <tr>
                                                <td class="fw-semibold">{{ $filiere->nom_filiere }}</td>
                                                <td>
                                                    @forelse ($filiere->niveaux ?? [] as $niveau)
                                                        <span class="badge bg-secondary">{{ $niveau->code_niveau }}</span>
                                                    @empty
                                                        <span class="text-muted">Aucun niveau</span>
                                                    @endforelse
                                                </td>
                                                <td>{{ $filiere->etudiants_count ?? 0 }}</td>
                                                <td>{{ $filiere->emplois_du_temps_count ?? 0 }}</td>
                                                <td class="text-end">
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editFiliereModal{{ $filiere->id }}" title="Modifier">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteFiliereModal{{ $filiere->id }}" title="Supprimer">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>

                                                    <div class="modal fade" id="editFiliereModal{{ $filiere->id }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form action="{{ route('departement.filieres.update', $filiere->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-content text-start">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title text-dark">Modifier la filière</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <label class="form-label text-dark fw-bold">Nom de la filière</label>
                                                                            <input type="text" class="form-control" name="nom_filiere" value="{{ $filiere->nom_filiere }}" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    <div class="modal fade" id="deleteFiliereModal{{ $filiere->id }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form action="{{ route('departement.filieres.destroy', $filiere->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <div class="modal-content text-start">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title text-danger">Confirmer la suppression</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p class="text-dark">Êtes-vous sûr de vouloir supprimer la filière <strong>{{ $filiere->nom_filiere }}</strong> ?</p>
                                                                        <small class="text-danger">Cette action supprime définitivement les données liées.</small>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-center text-muted py-4">Aucune filière enregistrée.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="addFiliereModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('departement.filieres.store') }}" method="POST">
                            @csrf
                            <div class="modal-content text-start">
                                <div class="modal-header">
                                    <h5 class="modal-title text-dark">Ajouter une nouvelle filière</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label text-dark fw-bold">Nom de la filière</label>
                                        <input type="text" class="form-control" name="nom_filiere" placeholder="Ex: Informatique de Gestion" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-dark fw-bold">Département / UFR (Optionnel)</label>
                                        <select class="form-select" name="ufr_id">
                                            <option value="">-- Sélectionner un département --</option>
                                            @foreach($departements ?? [] as $dept)
                                                <option value="{{ $dept->id }}">{{ $dept->nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-dark fw-bold d-block">Niveaux associés</label>
                                        <div class="row g-2 px-2">
                                            @foreach($niveaux ?? [] as $niveau)
                                                <div class="col-6 form-check">
                                                    <input class="form-check-input" type="checkbox" name="niveaux[]" value="{{ $niveau->id }}" id="niveau_{{ $niveau->id }}">
                                                    <label class="form-check-label text-dark" for="niveau_{{ $niveau->id }}">{{ $niveau->code_niveau }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-success">Créer la filière</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            @elseif ($activeTab === 'infos')
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold text-dark mb-0"><i class="bi bi-megaphone-fill text-primary me-2"></i> Espace d'Annonces & Partage</h4>
                    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addInformationModal">
                        <i class="bi bi-plus-circle me-1"></i> Publier une information
                    </button>
                </div>
                <div class="card section-card shadow-sm">
                    <div class="card-header bg-white"><h5 class="mb-0"><i class="bi bi-bell text-primary me-2"></i> Historique des flux d'informations</h5></div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            @forelse($informations ?? [] as $info)
                                <div class="col-12">
                                    <div class="p-3 rounded bg-light border-start border-4 {{ $info->visibilite === 'public' ? 'border-info' : 'border-warning' }}">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-bold mb-0 text-dark">{{ $info->titre }}</h6>
                                            <div>
                                                <span class="badge {{ $info->visibilite === 'public' ? 'bg-info' : 'bg-warning' }} text-dark">
                                                    {{ $info->visibilite === 'public' ? 'Public' : 'Privé' }}
                                                </span>
                                                <small class="text-muted ms-2">{{ $info->created_at ? $info->created_at->diffForHumans() : '' }}</small>
                                            </div>
                                        </div>
                                        <p class="mb-0 text-secondary small">{{ $info->contenu }}</p>
                                        <small class="text-muted d-block mt-1 text-end">Par : {{ $info->auteur->name ?? 'Système' }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-5">Aucune information diffusée.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

            @elseif ($activeTab === 'vacations')
                <div class="card section-card shadow-sm">
                    <div class="card-header bg-white"><h5 class="mb-0"><i class="bi bi-clipboard-check text-warning me-2"></i> Gestion des Demandes de Vacation</h5></div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Enseignant</th>
                                    <th>Cours / Module</th>
                                    <th>Heures</th>
                                    <th>Date Demande</th>
                                    <th>Statut</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($vacations ?? [] as $vacation)
                                    <tr>
                                        <td class="fw-semibold">{{ $vacation->enseignant->user->name ?? '—' }}</td>
                                        <td>{{ $vacation->intitule_cours ?? $vacation->emploiDuTemps->matiere ?? 'Non spécifié' }}</td>
                                        <td><span class="badge bg-light text-dark fw-bold">{{ $vacation->nombre_heures }} h</span></td>
                                        <td>{{ $vacation->created_at ? $vacation->created_at->format('d/m/Y') : '—' }}</td>
                                        <td>
                                            @if($vacation->statut === 'en_attente')
                                                <span class="badge bg-warning text-dark">En attente</span>
                                            @elseif($vacation->statut === 'approuve' || $vacation->statut === 'valide')
                                                <span class="badge bg-success">Approuvée</span>
                                            @else
                                                <span class="badge bg-danger">Rejetée</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($vacation->statut === 'en_attente')
                                                <form action="{{ route('departement.vacations.statut', [$vacation->id, 'approuve']) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-success">Valider</button>
                                                </form>
                                                <form action="{{ route('departement.vacations.statut', [$vacation->id, 'rejete']) }}" method="POST" class="d-inline ms-1">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Rejeter cette demande ?')">Rejeter</button>
                                                </form>
                                            @else
                                                <span class="text-muted small">Traité</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted py-4">Aucune demande de vacation.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            @elseif ($activeTab === 'evaluations')
                <div class="row g-3">
                    <div class="col-lg-7">
                        <div class="card section-card shadow-sm h-100">
                            <div class="card-header bg-white"><h5 class="mb-0"><i class="bi bi-calendar-check text-danger me-2"></i> Calendriers d'évaluations</h5></div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light"><tr><th>Intitulé</th><th>Filière</th><th>Niveau</th><th>Type</th><th>Période</th></tr></thead>
                                    <tbody>
                                        @forelse ($calendriers ?? [] as $calendrier)
                                            <tr>
                                                <td class="fw-semibold">{{ $calendrier->intitule }}</td>
                                                <td>{{ $calendrier->filiere->nom_filiere ?? '—' }}</td>
                                                <td>{{ $calendrier->niveau->code_niveau ?? '—' }}</td>
                                                <td>{{ $calendrier->type }}</td>
                                                <td>{{ $calendrier->date_debut?->format('d/m/Y') }} - {{ $calendrier->date_fin?->format('d/m/Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-center text-muted py-4">{{ ($calendriersEnabled ?? false) ? 'Aucun calendrier créé.' : 'Lancez les migrations pour activer le module évaluations.' }}</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card section-card shadow-sm">
                            <div class="card-header bg-white"><h5 class="mb-0"><i class="bi bi-plus-circle text-primary me-2"></i> Créer un calendrier</h5></div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('departement.calendriers_evaluations.store') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Intitulé</label>
                                        <input type="text" name="intitule" class="form-control" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Filière</label>
                                            <select name="filiere_id" class="form-select" required>
                                                <option value="">Choisir</option>
                                                @foreach ($filieres ?? [] as $filiere)
                                                    <option value="{{ $filiere->id }}">{{ $filiere->nom_filiere }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Niveau</label>
                                            <select name="niveau_id" class="form-select" required>
                                                <option value="">Choisir</option>
                                                @foreach ($niveaux ?? [] as $niveau)
                                                    <option value="{{ $niveau->id }}">{{ $niveau->code_niveau }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Type</label>
                                            <input type="text" name="type" class="form-control" value="Examen" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Début</label>
                                            <input type="date" name="date_debut" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Fin</label>
                                        <input type="date" name="date_fin" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Créer le calendrier</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </main>
    </div>
</div>
@endsection