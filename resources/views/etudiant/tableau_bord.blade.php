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

    <!-- NAV BARRE (Connexion / Déconnexion / Profil) -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-student shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-mortarboard-fill me-2"></i> Plateforme Pédagogique</a>
            <div class="collapse navbar-collapse justify-content-end">
                <span class="navbar-text text-white me-3 fw-semibold">
                    <i class="bi bi-person-circle me-1"></i> Alizèta SAWADOGO (L3 Info)
                </span>
                <button class="btn btn-sm btn-outline-light me-2"><i class="bi bi-person-gear"></i> Gérer mon profil</button>
                <a href="#" class="btn btn-sm btn-danger"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
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
                    <a href="#" class="nav-link active">
                        <i class="bi bi-house-door me-2"></i>
                        Tableau de bord
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
                    <a href="#" class="nav-link text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        Déconnexion
                    </a>
                </li>

            </ul>

        </div>

        <!-- CONTENU PRINCIPAL -->
        <div class="col-md-9 col-lg-10 main-content">

            <!-- EMPLOI DU TEMPS DU JOUR -->
            <div class="card card-custom shadow-sm bg-white p-4 mb-4">

                <h5 class="fw-bold text-secondary mb-3">
                    <i class="bi bi-calendar-day text-primary me-2"></i>
                    Emploi du temps du jour
                </h5>

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
                        <tr>
                            <td>08h00 - 10h00</td>
                            <td>Base de données avancée</td>
                            <td>Amphi A</td>
                            <td>Dr. Ouédraogo</td>
                        </tr>

                        <tr>
                            <td>10h15 - 12h15</td>
                            <td>Développement Laravel</td>
                            <td>Labo Info 2</td>
                            <td>M. Traoré</td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <!-- EMPLOI DU TEMPS HEBDOMADAIRE -->
            <div class="card card-custom shadow-sm bg-white p-4">

                <h5 class="fw-bold text-secondary mb-3">
                    <i class="bi bi-calendar-week text-success me-2"></i>
                    Emploi du temps hebdomadaire
                </h5>

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
                        <tr>
                            <td>08h00-10h00</td>
                            <td>BDD</td>
                            <td>Réseaux</td>
                            <td>Laravel</td>
                            <td>Maths</td>
                            <td>IA</td>
                        </tr>

                        <tr>
                            <td>10h15-12h15</td>
                            <td>Laravel</td>
                            <td>UML</td>
                            <td>Projet</td>
                            <td>BD</td>
                            <td>Sécurité</td>
                        </tr>

                        <tr>
                            <td>14h00-16h00</td>
                            <td>TP BDD</td>
                            <td>TP Réseaux</td>
                            <td>TP Laravel</td>
                            <td>Projet</td>
                            <td>Libre</td>
                        </tr>
                    </tbody>

                </table>

            </div>

        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
