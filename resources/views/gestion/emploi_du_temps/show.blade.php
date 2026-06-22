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
                        // Bornes par défaut, élargies automatiquement si des cours sortent de cette plage
                        $heureMin = 7;
                        $heureMax = 19;
                        foreach ($emplois as $e) {
                            $heureMin = min($heureMin, (int) substr($e->heure_debut, 0, 2));
                            $heureMax = max($heureMax, (int) substr($e->heure_fin, 0, 2));
                        }

                        // Construction de la grille : pour chaque jour/heure -> null, 'occupee', ou ['emploi' => ..., 'rowspan' => n]
                        $grille = [];
                        foreach ($jours as $jour) {
                            for ($h = $heureMin; $h < $heureMax; $h++) {
                                $grille[$jour][$h] = null;
                            }
                        }

                        foreach ($emplois as $emploi) {
                            // Trouve le jour correspondant dans $jours, peu importe la casse
                            $jourMatch = collect($jours)->first(fn($j) => strtolower($j) === strtolower($emploi->jour));
                            if (!$jourMatch) {
                                continue;
                            }

                            $hDebut = (int) substr($emploi->heure_debut, 0, 2);
                            $hFin = (int) substr($emploi->heure_fin, 0, 2);
                            $duree = max(1, $hFin - $hDebut);

                            if (array_key_exists($jourMatch, $grille) && array_key_exists($hDebut, $grille[$jourMatch])) {
                                $grille[$jourMatch][$hDebut] = ['emploi' => $emploi, 'rowspan' => $duree];
                                for ($h = $hDebut + 1; $h < $hDebut + $duree; $h++) {
                                    if (array_key_exists($h, $grille[$jourMatch])) {
                                        $grille[$jourMatch][$h] = 'occupee';
                                    }
                                }
                            }
                        }
                    @endphp

                    <h4 class="mt-5 mb-3">Vue Hebdomadaire</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Heure</th>
                                    @foreach ($jours as $jour)
                                        <th>{{ $jour }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @for ($heure = $heureMin; $heure < $heureMax; $heure++)
                                    <tr>
                                        <td><strong>{{ str_pad($heure, 2, '0', STR_PAD_LEFT) }}h00</strong></td>
                                        @foreach ($jours as $jour)
                                            @php $cell = $grille[$jour][$heure] ?? null; @endphp
                                            @if ($cell === 'occupee')
                                                {{-- déjà couverte par le rowspan de la ligne au-dessus --}}
                                            @elseif (is_array($cell))
                                                <td rowspan="{{ $cell['rowspan'] }}">
                                                    <div class="bg-primary text-white p-2 rounded">
                                                        <small><strong>{{ $cell['emploi']->matiere }}</strong></small><br>
                                                        <small>{{ $cell['emploi']->salle }}</small><br>
                                                        <small>{{ substr($cell['emploi']->heure_debut, 0, 5) }} - {{ substr($cell['emploi']->heure_fin, 0, 5) }}</small>
                                                    </div>
                                                </td>
                                            @else
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