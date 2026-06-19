@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2 class="mb-4"><i class="bi bi-plus-circle"></i> Ajouter un cours - {{ $filiere->nom_filiere }}</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('gestion.emploi_du_temps.store', $filiere) }}" method="POST" class="card shadow-sm p-4">
                @csrf

                <div class="mb-3">
                    <label for="niveau_id" class="form-label">Niveau</label>
                    <select name="niveau_id" id="niveau_id" class="form-select @error('niveau_id') is-invalid @enderror" required>
                        <option value="">-- Sélectionner un niveau --</option>
                        @foreach ($niveaux as $niv)
                            <option value="{{ $niv->id }}" {{ old('niveau_id', $niveau?->id) == $niv->id ? 'selected' : '' }}>
                                {{ $niv->intitule }}
                            </option>
                        @endforeach
                    </select>
                    @error('niveau_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="jour" class="form-label">Jour de la semaine</label>
                    <select name="jour" id="jour" class="form-select @error('jour') is-invalid @enderror" required>
                        <option value="">-- Sélectionner un jour --</option>
                        @foreach ($jours as $jour)
                            <option value="{{ $jour }}" {{ old('jour') === $jour ? 'selected' : '' }}>
                                {{ ucfirst($jour) }}
                            </option>
                        @endforeach
                    </select>
                    @error('jour')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="heure_debut" class="form-label">Heure de début</label>
                        <input type="time" name="heure_debut" id="heure_debut" class="form-control @error('heure_debut') is-invalid @enderror" value="{{ old('heure_debut') }}" required>
                        @error('heure_debut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="heure_fin" class="form-label">Heure de fin</label>
                        <input type="time" name="heure_fin" id="heure_fin" class="form-control @error('heure_fin') is-invalid @enderror" value="{{ old('heure_fin') }}" required>
                        @error('heure_fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="matiere" class="form-label">Matière</label>
                    <input type="text" name="matiere" id="matiere" class="form-control @error('matiere') is-invalid @enderror" value="{{ old('matiere') }}" required>
                    @error('matiere')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="salle" class="form-label">Salle</label>
                    <input type="text" name="salle" id="salle" class="form-control @error('salle') is-invalid @enderror" value="{{ old('salle') }}" required>
                    @error('salle')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="enseignant_id" class="form-label">Enseignant</label>
                    <select name="enseignant_id" id="enseignant_id" class="form-select @error('enseignant_id') is-invalid @enderror">
                        <option value="">-- Sélectionner un enseignant --</option>
                        @foreach ($enseignants as $ens)
                            <option value="{{ $ens->id }}" {{ old('enseignant_id') == $ens->id ? 'selected' : '' }}>
                                {{ $ens->user->name ?? 'Enseignant ' . $ens->id }}
                                @if ($ens->domaine_enseignement)
                                    - {{ $ens->domaine_enseignement }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('enseignant_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="enseignant" class="form-label">Ou saisir le nom de l'enseignant</label>
                    <input type="text" name="enseignant" id="enseignant" class="form-control @error('enseignant') is-invalid @enderror" value="{{ old('enseignant') }}" placeholder="Nom alternatif de l'enseignant">
                    @error('enseignant')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check"></i> Ajouter le cours
                    </button>
                    @if ($niveau)
                        <a href="{{ route('gestion.emploi_du_temps.show_niveau', ['filiere' => $filiere, 'niveau' => $niveau]) }}" class="btn btn-secondary">
                            <i class="bi bi-x"></i> Annuler
                        </a>
                    @else
                        <a href="{{ route('gestion.emploi_du_temps.show', $filiere) }}" class="btn btn-secondary">
                            <i class="bi bi-x"></i> Annuler
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

