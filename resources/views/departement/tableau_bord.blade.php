<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Chef de Département</title>
    <!-- Vos liens Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .navbar-chef { background-color: #0369a1; }
        .card-custom { border: none; border-radius: 12px; }
    </style>
</head>
<body>

    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-chef shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-shield-shaded me-2"></i> Direction - Informatique</a>
            <div class="collapse navbar-collapse justify-content-end">
                <span class="navbar-text text-white me-3 fw-semibold">
                    <i class="bi bi-person-workspace me-1"></i> Pr. Rabièta OUÉDRAOGO
                </span>
                <a href="#" class="btn btn-sm btn-danger"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <div class="row g-4">
            
            <!-- 1. VALIDATION DES FICHES DE VACATION -->
            <div class="col-lg-7">
                <div class="card card-custom shadow-sm bg-white p-4 h-100">
                    <h5 class="fw-bold text-secondary mb-3"><i class="bi bi-clipboard-check text-success me-2"></i> Fiches de vacation en attente</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle small">
                            <thead class="table-light">
                                <tr>
                                    <th>Enseignant</th>
                                    <th>Cours</th>
                                    <th>Heures</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold">Dr. Alassane O.</td>
                                    <td>BD Avancée</td>
                                    <td>12h</td>
                                    <td>
                                        <button class="btn btn-sm btn-success py-0 px-2"><i class="bi bi-check-lg"></i> Valider</button>
                                        <button class="btn btn-sm btn-outline-danger py-0 px-2">Rejeter</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 2. SUIVRE L'AVANCEMENT DES COURS -->
            <div class="col-lg-5">
                <div class="card card-custom shadow-sm bg-white p-4 h-100">
                    <h5 class="fw-bold text-secondary mb-3"><i class="bi bi-graph-up-arrow text-primary me-2"></i> Avancement des enseignements</h5>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small fw-bold mb-1">
                            <span>Base de données (L3 Info)</span>
                            <span class="text-primary">60%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: 60%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between small fw-bold mb-1">
                            <span>Laravel Framework (L3 Info)</span>
                            <span class="text-success">85%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. CONSULTER LES RAPPORT D'ÉVALUATION DES COURS (Anonymes) -->
            <div class="col-12">
                <div class="card card-custom shadow-sm bg-white p-4">
                    <h5 class="fw-bold text-secondary mb-3"><i class="bi bi-bar-chart-line text-warning me-2"></i> Retours & Évaluations Anonymes des Étudiants</h5>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-0">Cours : Développement Web Laravel</h6>
                                    <small class="text-muted">Pédagogie, clarté et rythme du cours</small>
                                </div>
                                <span class="badge bg-success fs-6">4.8 / 5</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-0">Cours : Architecture des Systèmes</h6>
                                    <small class="text-muted">Volume des travaux pratiques et supports</small>
                                </div>
                                <span class="badge bg-warning text-dark fs-6">3.2 / 5</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Votre lien JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
