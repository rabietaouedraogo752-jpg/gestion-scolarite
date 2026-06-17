<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .inscription-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
            padding: 40px;
        }
        .inscription-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .inscription-header h1 {
            color: #333;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .inscription-header p {
            color: #666;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-inscription {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            margin-top: 20px;
        }
        .btn-inscription:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .form-row .form-group {
            margin-bottom: 0;
        }
        @media (max-width: 576px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            .inscription-container {
                padding: 25px;
            }
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .text-center-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
        .text-center-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .text-center-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="inscription-container">
        <div class="inscription-header">
            <h1><i class="bi bi-person-plus me-2"></i> Inscription</h1>
            <p>Remplissez le formulaire ci-dessous pour créer votre compte</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Erreurs :</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('inscription.store') }}" method="POST">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="nom" class="form-label">Nom</label>
                    <input 
                        type="text" 
                        class="form-control @error('nom') is-invalid @enderror" 
                        id="nom" 
                        name="nom" 
                        placeholder="Ex: Dupont"
                        value="{{ old('nom') }}"
                        required
                    >
                    @error('nom') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input 
                        type="text" 
                        class="form-control @error('prenom') is-invalid @enderror" 
                        id="prenom" 
                        name="prenom" 
                        placeholder="Ex: Jean"
                        value="{{ old('prenom') }}"
                        required
                    >
                    @error('prenom') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input 
                    type="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    id="email" 
                    name="email" 
                    placeholder="exemple@email.com"
                    value="{{ old('email') }}"
                    required
                >
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="ine" class="form-label">INE ou matricule</label>
                <input 
                    type="text" 
                    class="form-control @error('ine') is-invalid @enderror" 
                    id="ine" 
                    name="ine" 
                    placeholder="Ex: 123456789 ou ABC123"
                    value="{{ old('ine') }}"
                    required
                >
                <small class="text-muted">Entrez votre numéro INE ou votre matricule.</small>
                @error('ine') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="date_naissance" class="form-label">Date de naissance</label>
                <input 
                    type="date" 
                    class="form-control @error('date_naissance') is-invalid @enderror" 
                    id="date_naissance" 
                    name="date_naissance"
                    value="{{ old('date_naissance') }}"
                    required
                >
                @error('date_naissance') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="telephone" class="form-label">Téléphone</label>
                <input 
                    type="tel" 
                    class="form-control @error('telephone') is-invalid @enderror" 
                    id="telephone" 
                    name="telephone" 
                    placeholder="Ex: +33 6 12 34 56 78"
                    value="{{ old('telephone') }}"
                >
                @error('telephone') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn btn-inscription">
                <i class="bi bi-check-circle me-2"></i> S'inscrire
            </button>
        </form>

        <div class="text-center-link">
            Vous avez déjà un compte ? <a href="{{ url('/connexion') }}">Se connecter</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
