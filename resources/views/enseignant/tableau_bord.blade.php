
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
        // Détection de l'onglet actif (par défaut : l'emploi du temps 'emploi')
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
                       <a href="#" class="nav-link"><i class="bi bi-file-earmark-text me-2"></i>Ressources</a>
                    </li>

                    <li class="nav-item">
                       <a href="#" class="nav-link"><i class="bi bi-person me-2"></i>Mon Profil</a>
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
                                                    <span class="badge bg-warning text-dark"><i class="bi bi-lock-fill me-1"></i> Privé ({{ ucfirst($info->cible) }})</span>
                                                @endif
                                                <small class="text-muted ms-2">{{ $info->created_at ? $info->created_at->diffForHumans() : '' }}</small>
                                            </div>
                                        </div>
                                        <p class="mb-0 text-secondary small">{{ $info->contenu }}</p>
                                        <small class="text-muted d-block mt-1 text-end" style="font-size: 0.75rem;">
                                            Par : {{ $info->auteur->name ?? 'Système' }}
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
                @endif

            </div>

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
                            <input type="text" class="form-control" id="titre_info" name="titre" placeholder="Ex: Report de cours, Disponibilité des polycopiés..." required>
                        </div>
                        <div class="mb-3">
                            <label for="contenu_info" class="form-label text-dark fw-bold">Contenu</label>
                            <textarea class="form-control" id="contenu_info" name="contenu" rows="4" placeholder="Écrivez votre message ici..." required></textarea>
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

```