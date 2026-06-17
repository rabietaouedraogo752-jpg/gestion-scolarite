@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Inscriptions en attente</h1>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    
                    @if(session('new_credentials'))
                        <hr>
                        <h5>Identifiants d'accès générés:</h5>
                        <ul class="mb-0">
                            <li><strong>Email:</strong> {{ session('new_credentials')['email'] }}</li>
                            <li><strong>Nom d'utilisateur:</strong> {{ session('new_credentials')['username'] }}</li>
                            <li><strong>Mot de passe:</strong> <code>{{ session('new_credentials')['password'] }}</code></li>
                        </ul>
                        <small class="text-muted">N'oubliez pas de communiquer ces identifiants à l'étudiant!</small>
                    @endif
                    
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($inscriptions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Date d'inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inscriptions as $inscription)
                                <tr>
                                    <td>{{ $inscription->nom }}</td>
                                    <td>{{ $inscription->prenom }}</td>
                                    <td>{{ $inscription->email }}</td>
                                    <td>{{ $inscription->telephone ?? 'N/A' }}</td>
                                    <td>{{ $inscription->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('gestion.inscriptions.show', $inscription) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> Détails
                                        </a>
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $inscription->id }}">
                                            <i class="bi bi-check-circle"></i> Approuver
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $inscription->id }}">
                                            <i class="bi bi-x-circle"></i> Rejeter
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal Approbation -->
                                <div class="modal fade" id="approveModal{{ $inscription->id }}" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="approveModalLabel">Approuver l'inscription</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Êtes-vous sûr de vouloir approuver l'inscription de <strong>{{ $inscription->prenom }} {{ $inscription->nom }}</strong>?</p>
                                                <p class="text-muted">Un compte utilisateur sera créé et les identifiants seront affichés.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <form action="{{ route('gestion.inscriptions.approve', $inscription) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">Approuver</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Rejet -->
                                <div class="modal fade" id="rejectModal{{ $inscription->id }}" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="rejectModalLabel">Rejeter l'inscription</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('gestion.inscriptions.reject', $inscription) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <p>Êtes-vous sûr de vouloir rejeter l'inscription de <strong>{{ $inscription->prenom }} {{ $inscription->nom }}</strong>?</p>
                                                    <div class="mb-3">
                                                        <label for="raison_rejet{{ $inscription->id }}" class="form-label">Raison du rejet (obligatoire)</label>
                                                        <textarea class="form-control" id="raison_rejet{{ $inscription->id }}" name="raison_rejet" rows="3" required></textarea>
                                                        @error('raison_rejet')
                                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-danger">Rejeter</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $inscriptions->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Aucune inscription en attente pour le moment.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
