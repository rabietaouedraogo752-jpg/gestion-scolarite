@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4"><i class="bi bi-calendar-event"></i> Gestion des Emplois du Temps</h2>

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
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ $filiere->nom_filiere }}</h5>
                                <p class="card-text text-muted">
                                    <small>
                                        Étudiants: <strong>{{ $filiere->etudiants()->count() }}</strong><br>
                                        Cours: <strong>{{ $filiere->emploisDuTemps()->count() }}</strong>
                                    </small>
                                </p>
                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('gestion.emploi_du_temps.show', $filiere) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i> Voir
                                    </a>
                                    <a href="{{ route('gestion.emploi_du_temps.create', $filiere) }}" class="btn btn-sm btn-success">
                                        <i class="bi bi-plus"></i> Ajouter
                                    </a>
                                </div>
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
