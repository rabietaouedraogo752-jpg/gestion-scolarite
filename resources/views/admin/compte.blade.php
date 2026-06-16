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
        .card-stat { border: none; border-radius: 12px; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 sidebar p-3 d-flex flex-column">
            <h5 class="text-center fw-bold py-3 border-bottom border-secondary">Scolarité Admin</h5>
            <ul class="nav nav-pills flex-column mb-auto mt-3 gap-2">
                
                <li>
                    <a href="#" class="nav-link"><i class="bi bi-people me-2"></i> Étudiants</a>
                </li>
                <li>
                    <a href="#" class="nav-link"><i class="bi bi-person-badge me-2"></i> Enseignants</a>
                </li>
                <li>
                    <a href="#" class="nav-link"><i class="bi bi-building me-2"></i>  Chef de départements</a>
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
                           
            </div>

            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-stat bg-white shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted small text-uppercase">Étudiants</h6>
                            <h3 class="fw-bold mb-0">1,245</h3>
                        </div>
                        <div class="fs-1 text-primary"><i class="bi bi-people-fill"></i></div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-stat bg-white shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted small text-uppercase">Enseignants</h6>
                            <h3 class="fw-bold mb-0">84</h3>
                        </div>
                        <div class="fs-1 text-success"><i class="bi bi-person-badge-fill"></i></div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-stat bg-white shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted small text-uppercase">Départements</h6>
                            <h3 class="fw-bold mb-0">6</h3>
                        </div>
                        <div class="fs-1 text-warning"><i class="bi bi-building-fill"></i></div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-stat bg-white shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted small text-uppercase">Inscriptions en attente</h6>
                            <h3 class="fw-bold mb-0 text-danger">12</h3>
                        </div>
                        <div class="fs-1 text-danger"><i class="bi bi-exclamation-circle-fill"></i></div>
                    </div>
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
                            <tr>
                                <td class="fw-semibold">Alizèta SAWADOGO</td>
                                <td>alizeta@etudiant.bf</td>
                                <td><span class="badge bg-info text-dark">Étudiant</span></td>
                                <td><span class="badge bg-warning">En attente</span></td>
                                <td>
                                    <button class="btn btn-sm btn-success me-1"><i class="bi bi-check-lg"></i></button>
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>