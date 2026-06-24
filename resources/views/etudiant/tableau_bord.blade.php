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

    <nav class="navbar navbar-expand-lg navbar-dark navbar-student shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-mortarboard-fill me-2"></i> Plateforme Pédagogique</a>
            <div class="collapse navbar-collapse justify-content-end">
                <span class="navbar-text text-white me-3 fw-semibold">
                    <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name ?? 'Étudiant' }}
                </span>
                <a href="{{ route('etudiant.profil') }}" class="btn btn-sm btn-outline-light me-2"><i class="bi bi-person-gear"></i> Gérer mon profil</a>
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
            <h5 class="text-white text-center mb-4">
                <i class="bi bi-person-circle"></i> Étudiant
            </h5>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('etudiant.tableau_bord') }}" class="nav-link active">
                        <i class="bi bi-house-door me-2"></i> Tableau de bord
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('etudiant.profil') }}" class="nav-link">
                        <i class="bi bi-person me-2"></i> Mon Profil
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('etudiant.notes') }}" class="nav-link">
                        <i class="bi bi-journal-text me-2"></i> Mes Notes
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-calendar3 me-2"></i> Programme des évaluations
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-file-earmark-text me-2"></i> Documents
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('etudiant.reclamations.index') }}" class="nav-link">
                        <i class="bi bi-chat-left-text me-2"></i> Réclamations
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-people me-2"></i> Forum
                    </a>
                </li>

                <li class="nav-item mt-4">
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-danger p-0 w-100 text-start">
                            <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                        </button>
                    </form>
                </li>
            </ul>
        </div> <div class="col-md-9 col-lg-10 main-content">

            <div class="card card-custom shadow-sm bg-white p-4 mb-4">
                <h5 class="fw-bold text-secondary mb-3">
                    <i class="bi bi-calendar-day text-primary me-2"></i> Emploi du temps du jour
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

                @if (count($emploisAujourdhui) > 0)
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

            <div class="card card-custom shadow-sm bg-white p-4">
                <h5 class="fw-bold text-secondary mb-3">
                    <i class="bi bi-calendar-week text-success me-2"></i> Emploi du temps hebdomadaire
                </h5>

                @if (count($emplois) > 0)
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
                            @php
                                $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'];
                                $heures = [];
                                foreach ($emplois as $e) {
                                    $h = (int)substr($e->heure_debut, 0, 2);
                                    $heures[$h] = true;
                                }
                                ksort($heures);
                            @endphp

                            @foreach (array_keys($heures) as $heure)
                                <tr>
                                    <td><strong>{{ str_pad($heure, 2, '0', STR_PAD_LEFT) }}h00</strong></td>
                                    @foreach ($jours as $jour)
                                        <td>
                                            @php
                                                $cours = collect($emplois)->filter(function($e) use ($jour, $heure) {
                                                    return strtolower($e->jour) === $jour && (int)substr($e->heure_debut, 0, 2) === $heure;
                                                })->first();
                                            @endphp
                                            @if ($cours)
                                                <div class="bg-primary text-white p-2 rounded">
                                                    <small><strong>{{ $cours->matiere }}</strong></small><br>
                                                    <small>{{ $cours->salle }}</small>
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> Aucun emploi du temps planifié pour votre filière
                    </div>
                @endif
            </div>

        </div> </div> </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>