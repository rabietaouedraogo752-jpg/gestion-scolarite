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
        .sidebar {
    min-height: calc(100vh - 56px);
    background-color: #0f172a;
}

.sidebar .nav-link {
    color: #cbd5e1;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 5px;
}

.sidebar .nav-link:hover,
.sidebar .nav-link.active {
    background-color: #1e293b;
    color: white;
}

.main-content {
    padding: 25px;
}
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

    <div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 sidebar p-3">

            <h5 class="text-white text-center mb-4">
                <i class="bi bi-person-workspace"></i>
                Enseignant
            </h5>

            <ul class="nav flex-column">

                <li>
                    <a class="nav-link active" href="#">
                        <i class="bi bi-house-door me-2"></i>
                        Tableau de bord
                    </a>
                </li>

                <li>
                    <a class="nav-link" href="#">
                        <i class="bi bi-journal-check me-2"></i>
                        Gestion des notes
                    </a>
                </li>

                <li>
                    <a class="nav-link" href="#">
                        <i class="bi bi-calculator me-2"></i>
                        Vacations
                    </a>
                </li>

                <li>
                    <a class="nav-link" href="#">
                        <i class="bi bi-calendar3 me-2"></i>
                        Planning
                    </a>
                </li>

                <li>
                    <a class="nav-link" href="#">
                        <i class="bi bi-book me-2"></i>
                        Mes cours
                    </a>
                </li>

                <li>
                    <a class="nav-link" href="#">
                        <i class="bi bi-chat-left-text me-2"></i>
                        Réclamations
                    </a>
                </li>

                <li>
                    <a class="nav-link" href="#">
                        <i class="bi bi-person-gear me-2"></i>
                        Mon profil
                    </a>
                </li>

                <li class="mt-4">
                    <a class="nav-link text-danger" href="#">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        Déconnexion
                    </a>
                </li>

            </ul>

        </div>

        <!-- CONTENU -->
         <!-- CONTENU PRINCIPAL -->
<div class="col-md-9 col-lg-10 main-content">

    <!-- Planning du jour -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h4 class="fw-bold">
                <i class="bi bi-calendar-day text-primary"></i>
                Planning du jour
            </h4>

            <div class="alert alert-info mt-3">
                <strong>08h00 - 10h00 :</strong> Base de données avancée (Amphi A)
            </div>

            <div class="alert alert-success">
                <strong>10h00 - 12h00 :</strong> TP Base de données (Labo 2)
            </div>
        </div>
    </div>

    <!-- Planning hebdomadaire -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h4 class="fw-bold">
                <i class="bi bi-calendar-week text-success"></i>
                Planning hebdomadaire
            </h4>

            <table class="table table-bordered mt-3">
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
                        <td>BD Avancée</td>
                        <td>-</td>
                        <td>TP BD</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Cartes du bas -->
    <div class="row">

        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5>Gestion des notes</h5>

                    <button class="btn btn-primary w-100 mb-2">
                        Saisie manuelle
                    </button>

                    <button class="btn btn-success w-100">
                        Import Excel
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5>Vacations</h5>

                    <h2 class="text-primary">24 h</h2>

                    <button class="btn btn-primary w-100">
                        Déclarer mes heures
                    </button>
                </div>
            </div>
        </div>

    </div>

</div>
        <div class="col-md-9 col-lg-10 main-content">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
