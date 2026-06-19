<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Gestion Pédagogique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background-color: #1e293b; color: white; }
        .sidebar .nav-link { color: #94a3b8; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: white; background-color: #334155; border-radius: 8px; }
        .card-stat { border: none; border-radius: 12px; transition: transform 0.2s, box-shadow 0.2s; }
        
        /* Style pour rendre les cartes de statistiques cliquables proprement */
        .card-stat-link { text-decoration: none; color: inherit; display: block; }
        .card-stat-link:hover .card-stat { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.08)!important; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 sidebar p-3 d-flex flex-column">
            <h5 class="text-center fw-bold py-3 border-bottom border-secondary">Scolarité Admin</h5>
            <ul class="nav nav-pills flex-column mb-auto mt-3 gap-2">
                <li class="nav-item">
                    <a href="#" class="nav-link active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                </li>
                <li>
                    <a href="{{ route('gestion.liste_etudiant') }}" class="nav-link"><i class="bi bi-people me-2"></i> Étudiants</a>
                </li>
                <li>
                    <a href="{{ route('gestion.liste_enseignant') }}" class="nav-link"><i class="bi bi-person-badge me-2"></i> Enseignants</a>
                </li>
                <li>
                    <a href="{{ route('gestion.liste_departement') }}" class="nav-link"><i class="bi bi-building me-2"></i> Départements</a>
                </li>
                <li>
                    <a href="#" class="nav-link"><i class="bi bi-journal-text me-2"></i> Examens & Notes</a>
                </li>
            </ul>
            
            <div class="border-top border-secondary pt-3">
                <a href="#" class="text-danger text-decoration-none small"><i class="bi bi-box-arrow-left me-2"></i> Déconnexion</a>
            </div>
        </div>

        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-0">Tableau de bord</h2>
                    <p class="text-muted small mb-0">Bienvenue sur votre espace de gestion pédagogique</p>
                </div>
                <a href="{{ url('/admin/vu_compte') }}" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 rounded-3 shadow-sm fw-semibold text-decoration-none">
                    <i class="bi bi-person-gear fs-5"></i>
                    <span>Gestion de comptes</span>
                </a>            
            </div>

            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <a href="/gestion/liste_etudiant" class="card-stat-link">
                        <div class="card card-stat bg-white shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted small text-uppercase">Étudiants</h6>
                                <h3 class="fw-bold mb-0">{{ number_format($etudiants) }}</h3>
                            </div>
                            <div class="fs-1 text-primary"><i class="bi bi-people-fill"></i></div>
                        </div>
                    </a>
                </div>
                
                <div class="col-sm-6 col-xl-3">
                    <a href="{{ route('gestion.liste_enseignant') }}" class="card-stat-link">
                        <div class="card card-stat bg-white shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted small text-uppercase">Enseignants</h6>
                                <h3 class="fw-bold mb-0">{{ number_format($enseignants) }}</h3>
                            </div>
                            <div class="fs-1 text-success"><i class="bi bi-person-badge-fill"></i></div>
                        </div>
                    </a>
                </div>
                
                <div class="col-sm-6 col-xl-3">
                    <a href="{{ route('gestion.liste_departement') }}" class="card-stat-link">
                        <div class="card card-stat bg-white shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted small text-uppercase">Départements</h6>
                                <h3 class="fw-bold mb-0">{{ number_format($departements) }}</h3>
                            </div>
                            <div class="fs-1 text-warning"><i class="bi bi-building-fill"></i></div>
                        </div>
                    </a>
                </div>
                
                <div class="col-sm-6 col-xl-3">
                    <a href="#" class="card-stat-link">
                        <div class="card card-stat bg-white shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted small text-uppercase">Inscriptions en attente</h6>
                                <h3 class="fw-bold mb-0 text-danger">{{ number_format($inscriptions_en_attente) }}</h3>
                            </div>
                            <div class="fs-1 text-danger"><i class="bi bi-exclamation-circle-fill"></i></div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="card shadow-sm border-0 p-4 bg-white">
                <h5 class="fw-bold text-secondary mb-3"><i class="bi bi-clock-history me-2"></i> Dernières demandes d'inscriptions</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nom Complet</th>
                                <th>Email</th>
                                <th>Rôle demandé</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                                        @if($latestInscription)
                                @php
                                    $inscriptionQuery = array_filter([
                                        'prenom' => $latestInscription->prenom,
                                        'nom' => $latestInscription->nom,
                                        'name' => trim($latestInscription->prenom . ' ' . $latestInscription->nom),
                                        'email' => $latestInscription->email,
                                        'date_naissance' => optional($latestInscription->date_naissance)->format('Y-m-d'),
                                        'telephone' => $latestInscription->telephone,
                                    ]);
                                @endphp
                                <tr>
                                    <td class="fw-semibold">{{ $latestInscription->prenom }} {{ $latestInscription->nom }}</td>
                                    <td>{{ $latestInscription->email }}</td>
                                    <td><span class="badge bg-info text-dark">Étudiant</span></td>
                                    <td>
                                        @if($latestInscription->status === 'en_attente')
                                            <span class="badge bg-warning">En attente</span>
                                        @elseif($latestInscription->status === 'approuvee')
                                            <span class="badge bg-success">Approuvée</span>
                                        @else
                                            <span class="badge bg-danger">Rejetée</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <a href="{{ route('gestion.creer_etudiant') }}?{{ http_build_query($inscriptionQuery) }}" class="btn btn-sm btn-success" title="Créer un étudiant">
                                                <i class="bi bi-person-plus"></i>
                                            </a>
                                            <a href="{{ route('gestion.creer_enseignant') }}?{{ http_build_query($inscriptionQuery) }}" class="btn btn-sm btn-info" title="Créer un enseignant">
                                                <i class="bi bi-person-badge"></i>
                                            </a>
                                            <a href="{{ route('gestion.creer_departement') }}?{{ http_build_query($inscriptionQuery) }}" class="btn btn-sm btn-warning text-white" title="Créer un département">
                                                <i class="bi bi-building-add"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Aucune demande d'inscription récente.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                @include('partials.espace_info')
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
