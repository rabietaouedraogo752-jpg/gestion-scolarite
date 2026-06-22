<h2 class="mb-4">
    <i class="bi bi-calendar3 me-2"></i>
    Mon Emploi du Temps
</h2>

@if ($emplois->count() > 0)
    <!-- Emploi du temps par jour -->
    @foreach ($jours as $jour)
        @php
            $emploisJour = $emplois->get(strtolower($jour), collect());
        @endphp

        @if ($emploisJour->count() > 0)
            <div class="card card-custom shadow-sm bg-white p-4 mb-4">
                <h5 class="fw-bold text-secondary mb-3">
                    <i class="bi bi-calendar-event text-success me-2"></i>
                    {{ ucfirst($jour) }}
                </h5>

                <div class="row">
                    @foreach ($emploisJour as $cours)
                        <div class="col-md-6 mb-3">
                            <div class="card course-card h-100">
                                <div class="card-body">
                                    <h6 class="card-title fw-bold text-dark">
                                        {{ $cours->matiere }}
                                    </h6>

                                    <p class="card-text small text-muted mb-2">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ substr($cours->heure_debut, 0, 5) }} - {{ substr($cours->heure_fin, 0, 5) }}
                                    </p>

                                    <p class="card-text small text-muted mb-2">
                                        <i class="bi bi-door-closed me-1"></i>
                                        {{ $cours->salle }}
                                    </p>

                                    <p class="card-text small text-muted mb-2">
                                        <i class="bi bi-book me-1"></i>
                                        <strong>Filière :</strong> {{ $cours->filiere->nom_filiere ?? 'Non renseignée' }}
                                    </p>

                                    <p class="card-text small text-muted">
                                        <i class="bi bi-mortarboard me-1"></i>
                                        <strong>Niveau:</strong> {{ $cours->niveau->intitule ?? $cours->niveau->code_niveau }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach

@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        Aucun cours n'a été assigné à votre emploi du temps pour le moment.
    </div>
@endif
