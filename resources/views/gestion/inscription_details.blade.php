@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Détails de l'inscription</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Nom:</strong></label>
                            <p>{{ $inscription->nom }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Prénom:</strong></label>
                            <p>{{ $inscription->prenom }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Email:</strong></label>
                            <p>{{ $inscription->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Téléphone:</strong></label>
                            <p>{{ $inscription->telephone ?? 'Non fourni' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Date de naissance:</strong></label>
                            <p>{{ $inscription->date_naissance->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Statut:</strong></label>
                            <p>
                                <span class="badge bg-warning">{{ ucfirst($inscription->status) }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label"><strong>Date d'inscription:</strong></label>
                            <p>{{ $inscription->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>

                    @if($inscription->raison_rejet)
                        <div class="alert alert-danger">
                            <strong>Raison du rejet:</strong><br>
                            {{ $inscription->raison_rejet }}
                        </div>
                    @endif

                    <div class="mt-4">
                        @if($inscription->status === 'en_attente')
                            <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                                <i class="bi bi-check-circle"></i> Approuver
                            </button>
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle"></i> Rejeter
                            </button>
                        @else
                            <span class="badge bg-secondary">Déjà traitée</span>
                        @endif
                        
                        <a href="{{ route('gestion.inscriptions.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Approbation -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
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
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
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
                            <label for="raison_rejet" class="form-label">Raison du rejet (obligatoire)</label>
                            <textarea class="form-control" id="raison_rejet" name="raison_rejet" rows="3" required></textarea>
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
</div>
@endsection
