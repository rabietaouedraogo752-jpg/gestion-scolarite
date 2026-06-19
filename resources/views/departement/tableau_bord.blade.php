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
                        <h2 class="text-primary">{{ number_format($stats['enseignants']) }}</h2>
                        <p class="mb-0">Enseignants</p>
                    </div></div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card shadow-sm"><div class="card-body text-center">
                        <h2 class="text-success">{{ number_format($stats['cours']) }}</h2>
                        <p class="mb-0">Cours planifiés</p>
                    </div></div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card shadow-sm"><div class="card-body text-center">
                        <h2 class="text-warning">{{ number_format($stats['vacations']) }}</h2>
                        <p class="mb-0">Vacations</p>
                    </div></div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card shadow-sm"><div class="card-body text-center">
                        <h2 class="text-danger">{{ number_format($stats['alertes']) }}</h2>
                        <p class="mb-0">Alertes</p>
                    </div></div>
                </div>
            </div>

            @if ($activeTab === 'enseignants')
                <div class="card section-card shadow-sm">
                    <div class="card-header bg-white"><h5 class="mb-0"><i class="bi bi-people text-primary me-2"></i> Enseignants et domaines</h5></div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light"><tr><th>Nom</th><th>Email</th><th>Grade</th><th>Domaine d'enseignement</th><th>Téléphone</th></tr></thead>
                            <tbody>
                                @forelse ($enseignants as $enseignant)
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
                    <div class="col-lg-7">
                        <div class="card section-card shadow-sm h-100">
                            <div class="card-header bg-white"><h5 class="mb-0"><i class="bi bi-book text-success me-2"></i> Filières de formation</h5></div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light"><tr><th>Filière</th><th>Niveaux</th><th>Étudiants</th><th>Cours</th></tr></thead>
                                    <tbody>
                                        @forelse ($filieres as $filiere)
                                            <tr>
                                                <td class="fw-semibold">{{ $filiere->nom_filiere }}</td>
                                                <td>
                                                    @forelse ($filiere->niveaux as $niveau)
                                                        <span class="badge bg-secondary">{{ $niveau->code_niveau }}</span>
                                                    @empty
                                                        <span class="text-muted">Aucun niveau</span>
                                                    @endforelse
                                                </td>
                                                <td>{{ $filiere->etudiants_count }}</td>
                                                <td>{{ $filiere->emplois_du_temps_count }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center text-muted py-4">Aucune filière enregistrée.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="card section-card shadow-sm">
                            <div class="card-header bg-white"><h5 class="mb-0"><i class="bi bi-plus-circle text-primary me-2"></i> Créer une filière</h5></div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('departement.filieres.store') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Nom de la filière</label>
                                        <input type="text" name="nom_filiere" class="form-control" value="{{ old('nom_filiere') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Département</label>
                                        <select name="ufr_id" class="form-select">
                                            @foreach ($departements as $departement)
                                                <option value="{{ $departement->id }}">{{ $departement->nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Niveaux concernés</label>
                                        <div class="row g-2">
                                            @foreach ($niveaux as $niveau)
                                                <div class="col-6">
                                                    <label class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="niveaux[]" value="{{ $niveau->id }}">
                                                        <span class="form-check-label">{{ $niveau->code_niveau }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-lg me-1"></i> Créer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($activeTab === 'vacations')
                <div class="card section-card shadow-sm">
                    <div class="card-header bg-white"><h5 class="mb-0"><i class="bi bi-clipboard-check text-warning me-2"></i> Vacations</h5></div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light"><tr><th>Enseignant</th><th>Cours</th><th>Heures</th><th>Période</th><th>Statut</th></tr></thead>
                            <tbody>
                                @forelse ($vacations as $vacation)
                                    <tr>
                                        <td>{{ $vacation->enseignant->user->name ?? 'Non assigné' }}</td>
                                        <td class="fw-semibold">{{ $vacation->matiere }}</td>
                                        <td>{{ $vacation->nombre_heures }}h</td>
                                        <td>{{ $vacation->periode ?? '—' }}</td>
                                        <td><span class="badge bg-{{ $vacation->statut === 'validee' ? 'success' : ($vacation->statut === 'rejetee' ? 'danger' : 'warning text-dark') }}">{{ str_replace('_', ' ', $vacation->statut) }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">{{ $vacationsEnabled ? 'Aucune vacation enregistrée.' : 'Lancez les migrations pour activer le module vacations.' }}</td></tr>
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
                                        @forelse ($calendriers as $calendrier)
                                            <tr>
                                                <td class="fw-semibold">{{ $calendrier->intitule }}</td>
                                                <td>{{ $calendrier->filiere->nom_filiere ?? '—' }}</td>
                                                <td>{{ $calendrier->niveau->code_niveau ?? '—' }}</td>
                                                <td>{{ $calendrier->type }}</td>
                                                <td>{{ $calendrier->date_debut?->format('d/m/Y') }} - {{ $calendrier->date_fin?->format('d/m/Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-center text-muted py-4">{{ $calendriersEnabled ? 'Aucun calendrier créé.' : 'Lancez les migrations pour activer le module évaluations.' }}</td></tr>
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
                                        <input type="text" name="intitule" class="form-control" value="{{ old('intitule') }}" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Filière</label>
                                            <select name="filiere_id" class="form-select" required>
                                                <option value="">Choisir</option>
                                                @foreach ($filieres as $filiere)
                                                    <option value="{{ $filiere->id }}">{{ $filiere->nom_filiere }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Niveau</label>
                                            <select name="niveau_id" class="form-select" required>
                                                <option value="">Choisir</option>
                                                @foreach ($niveaux as $niveau)
                                                    <option value="{{ $niveau->id }}">{{ $niveau->code_niveau }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Type</label>
                                            <input type="text" name="type" class="form-control" value="{{ old('type', 'Examen') }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Début</label>
                                            <input type="date" name="date_debut" class="form-control" value="{{ old('date_debut') }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Fin</label>
                                            <input type="date" name="date_fin" class="form-control" value="{{ old('date_fin') }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100" @disabled(!$calendriersEnabled)>
                                        <i class="bi bi-check-lg me-1"></i> Créer le calendrier
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row g-3">
                    <div class="col-lg-7">
                        <div class="card section-card shadow-sm h-100">
                            <div class="card-header bg-white"><h5 class="mb-0"><i class="bi bi-graph-up-arrow text-primary me-2"></i> Avancement des enseignements</h5></div>
                            <div class="card-body">
                                @forelse ($filieres->take(5) as $filiere)
                                    @php
                                        $niveauCount = max($filiere->niveaux->count(), 1);
                                        $percent = min(100, $filiere->emplois_du_temps_count * 10);
                                    @endphp
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between small fw-bold mb-1">
                                            <span>{{ $filiere->nom_filiere }} ({{ $niveauCount }} niveau{{ $niveauCount > 1 ? 'x' : '' }})</span>
                                            <span class="text-primary">{{ $percent }}%</span>
                                        </div>
                                        <div class="progress" style="height: 8px;"><div class="progress-bar bg-primary" style="width: {{ $percent }}%"></div></div>
                                    </div>
                                @empty
                                    <p class="text-muted mb-0">Aucune filière à suivre.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="card section-card shadow-sm h-100">
                            <div class="card-header bg-white"><h5 class="mb-0"><i class="bi bi-exclamation-triangle text-danger me-2"></i> Alertes</h5></div>
                            <div class="card-body">
                                <p class="mb-2"><strong>{{ $stats['alertes'] - ($vacationsEnabled ? $vacations->where('statut', 'en_attente')->count() : 0) }}</strong> cours sans enseignant assigné.</p>
                                <p class="mb-0"><strong>{{ $vacationsEnabled ? $vacations->where('statut', 'en_attente')->count() : 0 }}</strong> vacation(s) en attente.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </main>
    </div>
</div>
@endsection
