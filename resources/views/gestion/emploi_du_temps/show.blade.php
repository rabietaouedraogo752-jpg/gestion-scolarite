@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="bi bi-calendar-event"></i> {{ $filiere->nom_filiere ?? $filiere->nom }}</h2>
                    <p class="text-muted">Emploi du temps par niveau</p>
                </div>
                <a href="{{ route('gestion.emploi_du_temps.create', [
                    'filiere_id' => $filiere->id, 
                    'niveau_id' => request('niveau') ?: ($niveaux->first()->id ?? 1)
                ]) }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Ajouter un cours
                </a>
            </div>
        </div>
    </div>

    @if (isset($niveaux) && count($niveaux) > 0)
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('gestion.emploi_du_temps.show', $filiere->id) }}">
                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <label for="niveau" class="form-label fw-bold">Filtrer par niveau :</label>
                            <select name="niveau" id="niveau" class="form-control form-select" onchange="this.form.submit()">
                                <option value="">-- Choisir un niveau --</option>
                                @foreach($niveaux as $niv)
                                    <option value="{{ $niv->id }}" {{ request('niveau') == $niv->id ? 'selected' : '' }}>
                                        {{ $niv->nom_niveau ?? $niv->intitule ?? $niv->code_niveau }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Heure</th>
                            <th>Lundi</th>
                            <th>Mardi</th>
                            <th>Mercredi</th>
                            <th>Jeudi</th>
                            <th>Vendredi</th>
                            <th>Samedi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold">08:00 - 10:00</td>
                            <td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">10:00 - 12:00</td>
                            <td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection