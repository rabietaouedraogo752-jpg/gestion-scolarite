<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="bi bi-folder-symlink me-2 text-primary"></i>
        Ressources Pédagogiques
    </h2>
    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addRessourceModal">
        <i class="bi bi-plus-circle me-1"></i> Ajouter un document
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(count($ressourcesParGroupe) > 0)
    @foreach($ressourcesParGroupe as $nomFiliere => $ressources)
        <div class="card card-custom shadow-sm bg-white p-4 mb-4 border-0">
            <h5 class="fw-bold text-secondary mb-3 border-bottom pb-2">
                <i class="bi bi-collection text-primary me-2"></i>
                Filière : {{ $nomFiliere }}
            </h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Titre du document</th>
                            <th>Niveau</th>
                            <th>Date d'ajout</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ressources as $ressource)
                            <tr>
                                <td class="fw-semibold text-dark">
                                    <i class="bi bi-file-earmark-text me-2 text-muted"></i>
                                    {{ $ressource->titre }}
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $ressource->niveau->intitule ?? $ressource->niveau->code_niveau ?? 'Général' }}
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    {{ $ressource->created_at->format('d/m/Y à H:i') }}
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('ressources.download', $ressource->id) }}" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-download me-1"></i> Télécharger
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@else
    <div class="alert alert-info border-0 shadow-sm bg-white p-4 text-center mb-4">
        <i class="bi bi-info-circle fs-3 text-info d-block mb-2"></i>
        Aucun document ou ressource n'est disponible pour le moment.
    </div>
@endif


<div class="card card-custom shadow-sm bg-white p-4 border-0 mt-5">
    <div class="border-bottom pb-2 mb-4">
        <h4 class="fw-bold text-dark mb-1">
            <i class="bi bi-pencil-square text-success me-2"></i> Saisie des Évaluations & Notes
        </h4>
        <p class="text-muted small mb-0">Sélectionnez une ressource pour attribuer une note correspondante aux étudiants inscrits.</p>
    </div>

    <form action="{{ route('enseignant.informations.store') }}" method="POST">
        @csrf
        
        <div class="row g-3 align-items-center mb-4">
            <div class="col-md-6">
                <label for="evaluation_ressource" class="form-label fw-semibold text-secondary">Ressource / Support associé à la note</label>
                <select class="form-select border-2 border-light shadow-sm" id="evaluation_ressource" name="titre" required>
                    <option value="">-- Choisir le support évalué --</option>
                    @foreach($ressourcesParGroupe as $nomFiliere => $ressources)
                        <optgroup label="Filière : {{ $nomFiliere }}">
                            @foreach($ressources as $ressource)
                                <option value="Note - {{ $ressource->titre }}">Note : {{ $ressource->titre }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle border">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 15%">Matricule</th>
                        <th style="width: 50%">Nom & Prénom(s) de l'Étudiant</th>
                        <th style="width: 35%" class="text-center">Note / 20</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($etudiants ?? [] as $etudiant)
                        <tr>
                            <td class="fw-bold text-secondary">{{ $etudiant->matricule ?? 'MAT-'. $etudiant->id }}</td>
                            <td class="fw-semibold text-dark">
                                <i class="bi bi-person-fill me-2 text-muted"></i>
                                {{ $etudiant->nom }} {{ $etudiant->prenom }}
                            </td>
                            <td>
                                <div class="input-group input-group-sm justify-content-center mx-auto" style="max-width: 150px;">
                                    <input type="number" 
                                           class="form-control text-center fw-bold text-primary" 
                                           name="contenu[{{ $etudiant->id }}]" 
                                           min="0" 
                                           max="20" 
                                           step="0.25" 
                                           placeholder="--">
                                    <span class="input-group-text bg-light fw-bold">/20</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                <i class="bi bi-people fs-3 d-block mb-2 text-secondary"></i>
                                Aucun étudiant rattaché ou trouvé pour vos cours.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(count($etudiants ?? []) > 0)
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success shadow-sm px-4">
                    <i class="bi bi-cloud-check me-2"></i> Enregistrer les notes
                </button>
            </div>
        @endif
    </form>
</div>


<div class="modal fade" id="addRessourceModal" tabindex="-1" aria-labelledby="addRessourceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addRessourceModalLabel">
                    <i class="bi bi-file-earmark-plus me-2"></i> Partager un nouveau document
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('ressources.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="titre" class="form-label fw-semibold">Titre du document</label>
                        <input type="text" class="form-control" id="titre" name="titre" placeholder="Ex: Cours d'Algorithmique - Chapitre 1" required>
                    </div>

                    <div class="mb-3">
                        <label for="fichier" class="form-label fw-semibold">Fichier (PDF, Word, Image, Zip... max 10Mo)</label>
                        <input type="file" class="form-control" id="fichier" name="fichier" required>
                    </div>

                    <div class="mb-3">
                        <label for="filiere_id" class="form-label fw-semibold">Filière concernée (Optionnel)</label>
                        <select class="form-select" id="filiere_id" name="filiere_id">
                            <option value="">-- Toutes les Filières (Général) --</option>
                            @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id }}">{{ $filiere->nom_filiere }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="niveau_id" class="form-label fw-semibold">Niveau (Optionnel)</label>
                        <select class="form-select" id="niveau_id" name="niveau_id">
                            <option value="">-- Tous les Niveaux --</option>
                            @foreach($niveaux as $niveau)
                                <option value="{{ $niveau->id }}">{{ $niveau->intitule ?? $niveau->code_niveau }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-cloud-upload me-1"></i> Publier la ressource
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>