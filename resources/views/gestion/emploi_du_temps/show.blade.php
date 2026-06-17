@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="bi bi-calendar-event"></i> {{ $filiere->nom_filiere }}</h2>
                    <p class="text-muted">Emploi du temps</p>
                </div>
                <a href="{{ route('gestion.emploi_du_temps.create', $filiere) }}" class="btn btn-success">
                    <i class="bi bi-plus"></i> Ajouter un cours
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Jour</th>
                            <th>Heure</th>
                            <th>Matière</th>
                            <th>Salle</th>
                            <th>Enseignant</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($emplois as $emploi)
                            <tr>
                                <td>
                                    <strong>{{ ucfirst($emploi->jour) }}</strong>
                                </td>
                                <td>
                                    {{ substr($emploi->heure_debut, 0, 5) }} - {{ substr($emploi->heure_fin, 0, 5) }}
                                </td>
                                <td>{{ $emploi->matiere }}</td>
                                <td>{{ $emploi->salle }}</td>
                                <td>{{ $emploi->enseignant }}</td>
                                <td>
                                    <a href="{{ route('gestion.emploi_du_temps.edit', $emploi) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('gestion.emploi_du_temps.destroy', $emploi) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Aucun cours planifié pour cette filière</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tableau hebdomadaire en grille -->
            @if ($emplois->count() > 0)
                <h4 class="mt-5 mb-3">Vue Hebdomadaire</h4>
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="table-light">
                            <tr>
                                <th>Heure</th>
                                @foreach ($jours as $jour)
                                    <th>{{ $jour }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @for ($heure = 8; $heure < 18; $heure++)
                                <tr>
                                    <td><strong>{{ str_pad($heure, 2, '0', STR_PAD_LEFT) }}h00</strong></td>
                                    @foreach ($jours as $jour)
                                        <td>
                                            @foreach ($emplois as $emploi)
                                                @if (strtolower($emploi->jour) === strtolower($jour) && (int)substr($emploi->heure_debut, 0, 2) === $heure)
                                                    <div class="bg-primary text-white p-2 rounded">
                                                        <small><strong>{{ $emploi->matiere }}</strong></small><br>
                                                        <small>{{ $emploi->salle }}</small>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </td>
                                    @endforeach
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            @endif

            <a href="{{ route('gestion.emploi_du_temps.index') }}" class="btn btn-secondary mt-3">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>
</div>
@endsection
