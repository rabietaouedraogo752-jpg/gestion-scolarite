<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Étudiant - Plateforme Universitaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { background-color: #f4f6f9; }
        .navbar-student { background-color: #4f46e5; }
        .card-custom { border: none; border-radius: 12px; }
        .btn-indigo { background-color: #4f46e5; color: white; border: none; }
        .btn-indigo:hover { background-color: #4338ca; color: white; }
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
    </style>
</head>
<body>
    @php
    $activeTab = request('tab', 'dashboard');
@endphp


    <!-- NAV BARRE (Connexion / Déconnexion / Profil) -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-student shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-mortarboard-fill me-2"></i> Plateforme Pédagogique</a>
            <div class="collapse navbar-collapse justify-content-end">
                <span class="navbar-text text-white me-3 fw-semibold">
                    <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name ?? 'Étudiant' }}
                </span>
                <button class="btn btn-sm btn-outline-light me-2"><i class="bi bi-person-gear"></i> Gérer mon profil</button>
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
                <i class="bi bi-person-circle"></i> Étudiant
            </h5>

            <ul class="nav flex-column">
    <li class="nav-item">
        <a href="{{ request()->fullUrlWithQuery(['tab' => 'dashboard']) }}" class="nav-link {{ $activeTab === 'dashboard' ? 'active' : '' }}">
            <i class="bi bi-house-door me-2"></i>
            Tableau de bord
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ request()->fullUrlWithQuery(['tab' => 'annonces']) }}" class="nav-link {{ $activeTab === 'annonces' ? 'active' : '' }}">
            <i class="bi bi-megaphone me-2"></i>
            Annonces et infos
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
            <i class="bi bi-journal-text me-2"></i>
            Mes Notes
        </a>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="bi bi-calendar3 me-2"></i>
            Programme des évaluations
        </a>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="bi bi-file-earmark-text me-2"></i>
            Documents
        </a>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="bi bi-chat-left-text me-2"></i>
            Réclamations
        </a>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="bi bi-people me-2"></i>
            Forum
        </a>
    </li>
    <li class="nav-item mt-4">
        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="nav-link btn btn-link text-danger p-0 text-start w-100 border-0 bg-transparent">
                <i class="bi bi-box-arrow-right me-2"></i>
                Déconnexion
            </button>
        </form>
    </li>
</ul>

        </div>

        <!-- CONTENU PRINCIPAL -->
        <div class="col-md-9 col-lg-10 main-content">
@if($activeTab === 'dashboard')
            <!-- EMPLOI DU TEMPS DU JOUR -->
            <div class="card card-custom shadow-sm bg-white p-4 mb-4">

                <h5 class="fw-bold text-secondary mb-3">
                    <i class="bi bi-calendar-day text-primary me-2"></i>
                    Emploi du temps du jour
                </h5>

                @php
                    $today = strtolower(\Carbon\Carbon::now()->format('l'));
                    $dayMap = [
                        'monday' => 'lundi',
                        'tuesday' => 'mardi',
                        'wednesday' => 'mercredi',
                        'thursday' => 'jeudi',
                        'friday' => 'vendredi',
                        'saturday' => 'samedi',
                        'sunday' => 'dimanche'
                    ];
                    $todayFr = $dayMap[$today] ?? 'lundi';
                    $emploisAujourdhui = collect($emplois)->filter(fn($e) => strtolower($e->jour) === $todayFr);
                @endphp

                @if ($emploisAujourdhui->count() > 0)
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Heure</th>
                                <th>Matière</th>
                                <th>Salle</th>
                                <th>Enseignant</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($emploisAujourdhui as $emploi)
                                <tr>
                                    <td>{{ substr($emploi->heure_debut, 0, 5) }} - {{ substr($emploi->heure_fin, 0, 5) }}</td>
                                    <td>{{ $emploi->matiere }}</td>
                                    <td>{{ $emploi->salle }}</td>
                                    <td>{{ $emploi->enseignant }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> Aucun cours prévu pour aujourd'hui
                    </div>
                @endif

            </div>

            <!-- EMPLOI DU TEMPS HEBDOMADAIRE -->
            <div class="card card-custom shadow-sm bg-white p-4">

                <h5 class="fw-bold text-secondary mb-3">
                    <i class="bi bi-calendar-week text-success me-2"></i>
                    Emploi du temps hebdomadaire
                </h5>

                @if (collect($emplois)->count() > 0)
                    <table class="table table-bordered table-hover text-center">

                        <thead class="table-primary">
                            <tr>
                                <th>Heure</th>
                                <th>Lundi</th>
                                <th>Mardi</th>
                                <th>Mercredi</th>
                                <th>Jeudi</th>
                                <th>Vendredi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Ligne par défaut si le tableau hebdomadaire est géré par une boucle plus tard -->
                            <tr>
                                <td colspan="6" class="text-muted py-3">Consultez votre calendrier complet pour le détail des horaires.</td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> Aucun cours planifié dans votre calendrier hebdomadaire.
                    </div>
                @endif
            </div>
        
        <!-- NOUVEL ONGLET : FLUX DES ANNONCES & INFORMATIONS DU CHEF DE DÉPARTEMENT -->
        @elseif($activeTab === 'annonces')
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-0">Annonces & Communiqués Écoles</h2>
                    <p class="text-muted small mb-0">Retrouvez les informations partagées par l'administration et vos enseignants</p>
                </div>
            </div>

            <div class="card card-custom shadow-sm border-0 p-4 bg-white">
                <h5 class="fw-bold text-dark mb-4"><i class="bi bi-megaphone-fill text-primary me-2"></i> Panneau d'affichage numérique</h5>
                <div class="row g-3">
                    @forelse($informations ?? [] as $info)
                        <div class="col-12">
                            <div class="p-3 rounded-3 border-start border-4 @if($info->visibilite === 'public') border-success bg-light @else border-info bg-light @endif">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h6 class="fw-bold text-dark mb-1">{{ $info->titre }}</h6>
                                    <span class="badge @if($info->visibilite === 'public') bg-success @else bg-info @endif text-uppercase small" style="font-size: 0.7rem;">
                                        {{ $info->visibilite }}
                                    </span>
                                </div>
                                <p class="mb-2 text-muted small mt-1" style="white-space: pre-line;">{{ $info->contenu }}</p>
                                <div class="d-flex justify-content-between align-items-center text-mini text-muted mt-2" style="font-size: 0.8rem;">
                                    <small><i class="bi bi-person-workspace me-1"></i> Émetteur : {{ $info->user->name ?? 'Direction / Enseignant' }}</small>
                                    <small><i class="bi bi-calendar-event me-1"></i> Le {{ $info->created_at ? $info->created_at->format('d/m/Y à H:i') : date('d/m/Y') }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-muted">
                            <i class="bi bi-chat-left-dots fs-1 text-secondary"></i>
                            <p class="mt-3 mb-0 fw-semibold">Aucun communiqué officiel n'a été publié à votre attention.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        </div> <!-- Fin main-content -->
    </div> <!-- Fin row -->
</div> <!-- Fin container-fluid -->


                        
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
