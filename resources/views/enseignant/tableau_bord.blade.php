<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Enseignant - Plateforme Universitaire</title>
    <!-- Vos liens Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .navbar-teacher { background-color: #0f172a; }
        .card-custom { border: none; border-radius: 12px; }
    </style>
</head>
<body>

    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-teacher shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-person-workspace me-2"></i> Portail Enseignant</a>
            <div class="collapse navbar-collapse justify-content-end">
                <span class="navbar-text text-white me-3 fw-semibold">
                    <i class="bi bi-person-badge-fill me-1"></i> Dr. Alassane OUÉDRAOGO
                </span>
                <button class="btn btn-sm btn-outline-light me-2"><i class="bi bi-gear-fill"></i> Profil & Sécurité</button>
                <a href="#" class="btn btn-sm btn-danger"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <div class="row g-4">
            <!-- 1. GESTION DES NOTES (Saisir / Importer) -->
            <div class="col-md-7">
                <div class="card card-custom shadow-sm bg-white p-4 h-100">
                    <h5 class="fw-bold text-secondary mb-3"><i class="bi bi-journal-check text-success me-2"></i> Saisie & Importation des Notes</h5>
                    <p class="text-muted small">Sélectionnez un élément constitutif (EC) pour intégrer les notes.</p>
                    
                    <form class="row g-3 mb-4">
                        <div class="col-sm-8">
                            <select class="form-select form-select-sm">
                                <option>Base de données avancée (L3 Info - Semestre 5)</option>
                                <option>Algorithmique appliquée (L2 Info)</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-sm btn-primary w-100"><i class="bi bi-pencil-square"></i> Saisie Manuelle</button>
                        </div>
                    </form>

                    <div class="border rounded p-3 bg-light text-center">
                        <i class="bi bi-file-earmark-excel fs-2 text-success d-block mb-2"></i>
                        <h6>Importer un fichier Excel / CSV</h6>
                        <input type="file" class="form-control form-control-sm my-2">
                        <button class="btn btn-sm btn-success text-uppercase fw-bold w-100">Valider l'importation</button>
                    </div>
                </div>
            </div>

            <!-- 2. FICHE DE VACATION (Renseigner / Déclarer) -->
            <div class="col-md-5">
                <div class="card card-custom shadow-sm bg-white p-4 h-100">
                    <h5 class="fw-bold text-secondary mb-3"><i class="bi bi-calculator text-primary me-2"></i> Suivi des Vacations</h5>
                    
                    <div class="p-3 bg-light rounded mb-3 border border-primary">
                        <div class="d-flex justify-content-between">
                            <span class="small text-muted">Heures effectuées ce mois :</span>
                            <span class="fw-bold text-primary">24 heures</span>
                        </div>
                    </div>

                    <h6 class="fw-bold text-muted small text-uppercase mb-2">Déclarer de nouvelles heures</h6>
                    <form class="row g-2">
                        <div class="col-6">
                            <input type="date" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-6">
                            <input type="number" class="form-control form-control-sm" placeholder="Nb heures" min="1" required>
                        </div>
                        <div class="col-100 w-100 mt-2">
                            <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-send-plus"></i> Soumettre ma fiche de vacation</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 3. CONSULTATION EMPLOI DU TEMPS -->
            <div class="col-12">
                <div class="card card-custom shadow-sm bg-white p-4">
                    <h5 class="fw-bold text-secondary mb-3"><i class="bi bi-calendar3 text-warning me-2"></i> Mon Planning de Cours</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center small">
                            <thead class="table-dark">
                                <tr>
                                    <th>Lundi</th>
                                    <th>Mardi</th>
                                    <th>Mercredi</th>
                                    <th>Jeudi</th>
                                    <th>Vendredi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><div class="p-2 bg-info-subtle border border-info rounded"><strong>08h - 10h</strong><br>BD Avancée (Amphi A)</div></td>
                                    <td>-</td>
                                    <td><div class="p-2 bg-info-subtle border border-info rounded"><strong>10h - 12h</strong><br>BD Avancée (Labo 2)</div></td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Votre lien JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
