<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Enseignant - Plateforme Universitaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { background-color: #f4f6f9; }
        .navbar-teacher { background-color: #059669; }
        .card-custom { border: none; border-radius: 12px; }
        .btn-emerald { background-color: #059669; color: white; border: none; }
        .btn-emerald:hover { background-color: #047857; color: white; }
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #1e293b;
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

    <!-- NAV BARRE -->
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

            <!-- SIDEBAR -->
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <h5 class="text-white text-center mb-4">
                    <i class="bi bi-person-circle"></i> Enseignant
                </h5>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('enseignant.tableau_bord') }}" class="nav-link active">
                            <i class="bi bi-house-door me-2"></i>
                            Mon Emploi du Temps
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-person me-2"></i>
                            Mon Profil
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            Ressources
                        </a>
                    </li>

                    <li class="nav-item mt-4">
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link text-danger p-0">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

            <!-- CONTENU PRINCIPAL -->
            <div class="col-md-9 col-lg-10 main-content">

                <h2 class="mb-4">
                    <i class="bi bi-calendar3 me-2"></i>
                    Mon Emploi du Temps
                </h2>

                @if ($emplois->count() > 0)
                    <!-- Emploi du temps par jour -->
                    @foreach ($jours as $jour)
                        @php
                            $emploisJour = $emplois->get(strtolower($jour), collect());
                        @endphp

                        @if ($emploisJour->count() > 0)
                            <div class="card card-custom shadow-sm bg-white p-4 mb-4">
                                <h5 class="fw-bold text-secondary mb-3">
                                    <i class="bi bi-calendar-event text-success me-2"></i>
                                    {{ ucfirst($jour) }}
                                </h5>

                                <div class="row">
                                    @foreach ($emploisJour as $cours)
                                        <div class="col-md-6 mb-3">
                                            <div class="card course-card h-100">
                                                <div class="card-body">
                                                    <h6 class="card-title fw-bold text-dark">
                                                        {{ $cours->matiere }}
                                                    </h6>

                                                    <p class="card-text small text-muted mb-2">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ substr($cours->heure_debut, 0, 5) }} - {{ substr($cours->heure_fin, 0, 5) }}
                                                    </p>

                                                    <p class="card-text small text-muted mb-2">
                                                        <i class="bi bi-door-closed me-1"></i>
                                                        {{ $cours->salle }}
                                                    </p>

                                                    <p class="card-text small text-muted mb-2">
                                                        <i class="bi bi-book me-1"></i>
                                                        <strong>Filière:</strong> {{ $cours->filiere->nom_filiere }}
                                                    </p>

                                                    <p class="card-text small text-muted">
                                                        <i class="bi bi-mortarboard me-1"></i>
                                                        <strong>Niveau:</strong> {{ $cours->niveau->intitule }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach

                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Aucun cours n'a été assigné à votre emploi du temps pour le moment.
                    </div>
                @endif

                <div class="mt-4">
                    @include('partials.espace_info')
                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
