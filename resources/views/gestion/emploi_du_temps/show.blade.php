@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="bi bi-calendar-event"></i> {{ $filiere->nom_filiere }}</h2>
                    <p class="text-muted">Emploi du temps par niveau</p>
                </div>
            </div>

            <!-- Sélecteur de niveau -->
            @if ($niveaux && count($niveaux) > 0)
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('gestion.emploi_du_temps.show', $filiere) }}" class="row g-3 align-items-end">
                            <div class="col-md-6">
                                <label for="niveau" class="form-label">Sélectionner un niveau</label>
                                <select name="niveau" id="niveau" class="form-select" onchange="this.form.submit()">
                                    <option value="">-- Tous les niveaux --</option>
                                    @foreach ($niveaux as $niv)
                                        <option value="{{ $niv->id }}" {{ $niveau?->id == $niv->id ? 'selected' : '' }}>
                                            {{ $niv->intitule }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                @if ($niveau)
                                    <a href="{{ route('gestion.emploi_du_temps.create_niveau', ['filiere' => $filiere, 'niveau' => $niveau]) }}" 
                                       class="btn btn-success w-100">
                                        <i class="bi bi-plus"></i> Ajouter un cours
                                    </a>
                                @else
                                    <a href="{{ route('gestion.emploi_du_temps.create', ['filiere' => $filiere]) }}" 
                                       class="btn btn-success w-100">
                                        <i class="bi bi-plus"></i> Ajouter un cours
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($emplois->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Jour</th>
                                <th>Heure</th>
                                <th>Matière</th>
                                <th>Salle</th>
                                <th>Enseignant</th>
                                <th>Niveau</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($emplois as $emploi)
                                <tr>
                                    <td>
                                        <strong>{{ ucfirst($emploi->jour) }}</strong>
                                    </td>
                                    <td>
                                        {{ substr($emploi->heure_debut, 0, 5) }} - {{ substr($emploi->heure_fin, 0, 5) }}
                                    </td>
                                    <td>{{ $emploi->matiere }}</td>
                                    <td>{{ $emploi->salle }}</td>
                                    <td>{{ $emploi->enseignantModel?->user?->name ?? $emploi->getAttribute('enseignant') ?? 'Non assigné' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $emploi->niveau->intitule }}</span>
                                    </td>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Tableau hebdomadaire en grille -->
                @if ($emplois->count() > 0)
                    @php
                        // Construit une grille [jour][heure] gérant la durée réelle des cours.
                        $grille = [];
                        $occupe = [];
                        $heureMin = 23;
                        $heureMax = 8;

                        foreach ($emplois as $emploi) {
                            $jourCours = strtolower($emploi->jour);
                            $debut = (int) substr($emploi->heure_debut, 0, 2);
                            $fin = max($debut + 1, (int) ceil((int) substr($emploi->heure_fin, 0, 2) + (((int) substr($emploi->heure_fin, 3, 2)) > 0 ? 1 : 0)));

                            $grille[$jourCours][$debut] = [
                                'emploi' => $emploi,
                                'duree' => max(1, $fin - $debut),
                            ];

                            for ($h = $debut; $h < $fin; $h++) {
                                $occupe[$jourCours][$h] = true;
                            }

                            $heureMin = min($heureMin, $debut);
                            $heureMax = max($heureMax, $fin);
                        }

                        $heureMin = min($heureMin, 8);
                        $heureMax = max($heureMax, 18);
                        $joursMin = array_map('strtolower', $jours);
                    @endphp

                    <h4 class="mt-5 mb-3">Vue Hebdomadaire</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 90px;">Heure</th>
                                    @foreach ($jours as $jour)
                                        <th>{{ $jour }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @for ($heure = $heureMin; $heure < $heureMax; $heure++)
                                    <tr>
                                        <td><strong>{{ str_pad($heure, 2, '0', STR_PAD_LEFT) }}h00</strong></td>
                                        @foreach ($joursMin as $jourMin)
                                            @if (isset($grille[$jourMin][$heure]))
                                                @php $bloc = $grille[$jourMin][$heure]; $cours = $bloc['emploi']; @endphp
                                                <td rowspan="{{ $bloc['duree'] }}" class="p-1">
                                                    <div class="bg-primary text-white p-2 rounded h-100">
                                                        <small><strong>{{ $cours->matiere }}</strong></small><br>
                                                        <small><i class="bi bi-clock"></i> {{ substr($cours->heure_debut, 0, 5) }}-{{ substr($cours->heure_fin, 0, 5) }}</small><br>
                                                        <small><i class="bi bi-door-closed"></i> {{ $cours->salle }}</small><br>
                                                        <small><i class="bi bi-person"></i> {{ $cours->enseignantModel?->user?->name ?? $cours->getAttribute('enseignant') ?? 'Non assigné' }}</small>
                                                    </div>
                                                </td>
                                            @elseif (empty($occupe[$jourMin][$heure]))
                                                <td></td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                @endif
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    Aucun cours planifié {{ $niveau ? 'pour ce niveau' : '' }}. 
                    <a href="{{ $niveau ? route('gestion.emploi_du_temps.create_niveau', ['filiere' => $filiere, 'niveau' => $niveau]) : route('gestion.emploi_du_temps.create', $filiere) }}" class="alert-link">
                        Ajouter un cours
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
