@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4"><i class="bi bi-calendar-event"></i> Gestion des Emplois du Temps par Niveau</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row">
                @forelse ($filieres as $filiere)
                    <div class="col-md-12 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">{{ $filiere->nom_filiere }}</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-3">
                                    <i class="bi bi-people"></i> <strong>Étudiants:</strong> {{ $filiere->etudiants()->count() }}
                                </p>

                                @if ($filiere->niveaux && count($filiere->niveaux) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Niveau</th>
                                                    <th>Nombre de Cours</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($filiere->niveaux as $niveau)
                                                    @php
                                                        $nbCours = $filiere->emploisDuTemps()
                                                            ->where('niveau_id', $niveau->id)
                                                            ->count();
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $niveau->intitule }}</td>
                                                        <td><span class="badge bg-info">{{ $nbCours }}</span></td>
                                                        <td>
                                                            <a href="{{ route('gestion.emploi_du_temps.show_niveau', ['filiere' => $filiere, 'niveau' => $niveau]) }}" 
                                                               class="btn btn-sm btn-primary">
                                                                <i class="bi bi-eye"></i> Voir
                                                            </a>
                                                            <a href="{{ route('gestion.emploi_du_temps.create_niveau', ['filiere' => $filiere, 'niveau' => $niveau]) }}" 
                                                               class="btn btn-sm btn-success">
                                                                <i class="bi bi-plus"></i> Ajouter
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-warning mb-0">
                                        <i class="bi bi-exclamation-triangle"></i> Aucun niveau défini pour cette filière.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12">
                        <div class="alert alert-info">Aucune filière disponible.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

