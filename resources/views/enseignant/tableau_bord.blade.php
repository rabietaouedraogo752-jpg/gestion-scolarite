<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Enseignant - Plateforme Universitaire</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        body { background-color: #f4f6f9; }
        .navbar-teacher { background-color: #059669; }
        .card-custom { border: none; border-radius: 12px; }
        .btn-emerald { background-color: #059669; color: white; border: none; }
        .btn-emerald:hover { background-color: #047857; color: white; }
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #1e293b;
            overflow-y: auto;
        }

        .sidebar .nav-link {
            color: #cbd5e1;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 5px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #334155;
            color: white;
        }

        .main-content {
            padding: 25px;
        }

        .course-card {
            border-left: 4px solid #059669;
            transition: transform 0.2s;
        }

        .course-card:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    @php
        $activeTab = request('tab', 'emploi');
    @endphp

    <nav class="navbar navbar-expand-lg navbar-dark navbar-teacher shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-mortarboard-fill me-2"></i> Plateforme Pédagogique</a>
            <div class="collapse navbar-collapse justify-content-end">
                <span class="navbar-text text-white me-3 fw-semibold">
                    <i class="bi bi-person-circle me-1"></i> {{ $enseignant->user->name ?? 'Enseignant' }}
                </span>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-box-arrow-right"></i> Déconnexion</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <h5 class="text-white text-center mb-4"><i class="bi bi-person-circle"></i> Enseignant</h5>
                <ul class="nav flex-column gap-1">
                    <li class="nav-item">
                       <a href="{{ route('enseignant.tableau_bord', ['tab' => 'emploi']) }}" class="nav-link {{ $activeTab === 'emploi' ? 'active' : '' }}"><i class="bi bi-calendar3 me-2"></i>Mon Emploi du Temps</a>
                    </li>
    
                    <li class="nav-item">
                       <a href="{{ route('enseignant.tableau_bord', ['tab' => 'infos']) }}" class="nav-link {{ $activeTab === 'infos' ? 'active' : '' }}"><i class="bi bi-megaphone me-2"></i>Annonces & Infos</a>
                    </li>

                    <li class="nav-item">
                       <a href="{{ route('enseignant.tableau_bord', ['tab' => 'ressources']) }}" class="nav-link {{ $activeTab === 'ressources' ? 'active' : '' }}"><i class="bi bi-file-earmark-text me-2"></i>Ressources</a>
                    </li>

                    <li class="nav-item">
                       <a href="{{ route('enseignant.tableau_bord', ['tab' => 'mon_profil']) }}" class="nav-link {{ $activeTab === 'mon_profil' ? 'active' : '' }}"><i class="bi bi-person me-2"></i>Mon Profil</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-9 col-lg-10 main-content">

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

                @if ($activeTab === 'emploi')
                    @include('enseignant.emploi_du_temps')
                
                @elseif ($activeTab === 'infos')
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">
                            <i class="bi bi-megaphone me-2"></i>
                            Espace d'Annonces & Partage
                        </h2>
                        <button class="btn btn-emerald shadow-sm" data-bs-toggle="modal" data-bs-target="#addInformationModal">
                            <i class="bi bi-megaphone-fill me-2"></i> Publier une information
                        </button>
                    </div>

                    <div class="card card-custom shadow-sm bg-white p-4">
                        <h5 class="fw-bold text-secondary mb-4">
                            <i class="bi bi-bell text-success me-2"></i>
                            Historique des flux d'informations
                        </h5>
                        <div class="row g-3">
                            @forelse($informations ?? [] as $info)
                                <div class="col-12">
                                    <div class="p-3 rounded bg-light border-start border-4 {{ $info->visibilite === 'public' ? 'border-info' : 'border-warning' }}">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-bold mb-0 text-dark">{{ $info->titre }}</h6>
                                            <div>
                                                @if($info->visibilite === 'public')
                                                    <span class="badge bg-info text-dark"><i class="bi bi-globe me-1"></i> Public</span>
                                                @else
                                                    <span class="badge bg-warning text-dark"><i class="bi bi-lock-fill me-1"></i> Privé</span>
                                                @endif
                                                <small class="text-muted ms-2">{{ $info->created_at ? $info->created_at->diffForHumans() : '' }}</small>
                                            </div>
                                        </div>
                                        <p class="mb-0 text-secondary small">{{ $info->contenu }}</p>
                                        <small class="text-muted d-block mt-1 text-end" style="font-size: 0.75rem;">
                                            Par : {{ optional($info->auteur)->name ?? 'Auteur inconnu' }}
                                            @if($info->user_id === auth()->id()) <span class="fw-bold text-success">(Vous)</span> @endif
                                        </small>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center text-muted py-5">
                                    <i class="bi bi-chat-left-dots fs-3 d-block mb-2 text-secondary"></i> Aucune information partagée dans cet espace.
                                </div>
                            @endforelse
                        </div>
                    </div>

<<<<<<< HEAD
                @elseif ($activeTab === 'ressources')
                    @includeIf('enseignant.resources')

                @elseif ($activeTab === 'mon_profil')
                    <div class="mb-4">
                        <h2><i class="bi bi-person-bounding-box me-2 text-primary"></i> Mon Profil Enseignant</h2>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-4">
                            <div class="card card-custom shadow-sm bg-white p-4 text-center border-0">
                                <div class="mb-3">
                                    <i class="bi bi-person-badge text-primary" style="font-size: 5rem;"></i>
                                </div>
                                <h4 class="fw-bold text-dark">{{ $enseignant->user->name ?? 'Nom indisponible' }}</h4>
                                <p class="text-muted small mb-3">{{ $enseignant->user->email ?? 'Email indisponible' }}</p>
                                <span class="badge bg-primary px-3 py-2">Enseignant Permanent</span>
                                <hr class="my-4">
                                <div class="text-start">
                                    <p class="mb-2 text-secondary small"><i class="bi bi-building me-2"></i><strong>Spécialité :</strong> {{ $enseignant->domaine_enseignement ?? 'Non définie' }}</p>
                                    <p class="mb-2 text-secondary small"><i class="bi bi-building me-2"></i><strong>Grade :</strong> {{ $enseignant->grade ?? 'Non défini' }}</p>
                                    <p class="mb-2 text-secondary small"><i class="bi bi-calendar-check me-2"></i><strong>Membre depuis :</strong> {{ $enseignant->created_at ? $enseignant->created_at->format('d/m/Y') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="card card-custom shadow-sm bg-white p-4 border-0">
                                <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">
                                    <i class="bi bi-gear-fill text-secondary me-2"></i> Modifier mes informations
                                </h5>

                                <form action="{{ route('enseignant.informations.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action_type" value="update_profile">

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold text-secondary">Nom Complet</label>
                                            <input type="text" class="form-control" name="titre" value="{{ $enseignant->user->name ?? '' }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold text-secondary">Spécialité / Département</label>
                                            <input type="text" class="form-control" name="cible" value="{{ $enseignant->specialite ?? '' }}">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold text-secondary">À propos / Présentation courte</label>
                                            <textarea class="form-control" name="contenu" rows="3" placeholder="Présentation rapide..."></textarea>
                                        </div>
                                    </div>

                                    <div class="text-end mt-4">
                                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                            <i class="bi bi-save me-1"></i> Sauvegarder les modifications
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif   </div>

=======
            </div>
>>>>>>> a3ed8c694b7b79086ea386a6440a9e3ec1b421bc
        </div>
    </div>

    <div class="modal fade" id="addInformationModal" tabindex="-1" aria-labelledby="addInformationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('enseignant.informations.store') }}" method="POST">
                @csrf
                <div class="modal-content text-start">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark" id="addInformationModalLabel">Nouveau partage d'information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="titre_info" class="form-label text-dark fw-bold">Titre de l'annonce</label>
                            <input type="text" class="form-control" id="titre_info" name="titre" required>
                        </div>
                        <div class="mb-3">
                            <label for="contenu_info" class="form-label text-dark fw-bold">Contenu</label>
                            <textarea class="form-control" id="contenu_info" name="contenu" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="visibilite_info" class="form-label text-dark fw-bold">Visibilité</label>
                            <select class="form-select" id="visibilite_info" name="visibilite">
                                <option value="public">Public (Tout le monde)</option>
                                <option value="prive">Privé (Cible spécifique)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-emerald">Publier</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>