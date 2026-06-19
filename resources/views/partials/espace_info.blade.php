@php
    $__infoUser = auth()->user();
    $__infos = \App\Models\Information::query()
        ->visiblePour($__infoUser)
        ->with('auteur')
        ->orderByDesc('created_at')
        ->limit(20)
        ->get();
@endphp

<div class="card section-card shadow-sm" id="espace-info">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-megaphone text-primary me-2"></i> Espace Info</h5>
        @if ($__infoUser)
            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#form-info">
                <i class="bi bi-plus-lg me-1"></i> Partager une info
            </button>
        @endif
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif

        @if ($__infoUser)
            <div class="collapse mb-3" id="form-info">
                <form method="POST" action="{{ route('informations.store') }}" enctype="multipart/form-data" class="border rounded p-3 bg-light">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label mb-1">Titre</label>
                        <input type="text" name="titre" class="form-control form-control-sm" value="{{ old('titre') }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-1">Message</label>
                        <textarea name="contenu" rows="3" class="form-control form-control-sm" required>{{ old('contenu') }}</textarea>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label mb-1">Visibilité</label>
                            <select name="visibilite" class="form-select form-select-sm">
                                <option value="prive">Privée (moi uniquement)</option>
                                <option value="public">Publique (tout le monde)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-1">Catégorie</label>
                            <input type="text" name="categorie" class="form-control form-control-sm" value="annonce">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-1">Pièce jointe</label>
                            <input type="file" name="fichier" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-send me-1"></i> Publier</button>
                    </div>
                </form>
            </div>
        @endif

        @forelse ($__infos as $info)
            <div class="border-start border-3 ps-3 mb-3 {{ $info->visibilite === 'public' ? 'border-success' : 'border-secondary' }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <strong>{{ $info->titre }}</strong>
                        <span class="badge {{ $info->visibilite === 'public' ? 'bg-success' : 'bg-secondary' }} ms-1">
                            {{ $info->visibilite === 'public' ? 'Public' : 'Privé' }}
                        </span>
                        @if ($info->categorie)
                            <span class="badge bg-info text-dark ms-1">{{ $info->categorie }}</span>
                        @endif
                    </div>
                    @if ($__infoUser && $info->user_id === $__infoUser->id)
                        <form method="POST" action="{{ route('informations.destroy', $info) }}" class="ms-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Supprimer" onclick="return confirm('Supprimer cette information ?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>
                <p class="mb-1 small text-muted">{{ $info->contenu }}</p>
                @if ($info->fichier)
                    <a href="{{ asset('storage/'.$info->fichier) }}" class="small" target="_blank">
                        <i class="bi bi-paperclip"></i> Pièce jointe
                    </a>
                @endif
                <div class="small text-muted">
                    <i class="bi bi-person"></i> {{ $info->auteur->name ?? 'Système' }}
                    · {{ $info->created_at?->format('d/m/Y H:i') }}
                </div>
            </div>
        @empty
            <p class="text-muted mb-0">Aucune information pour le moment.</p>
        @endforelse
    </div>
</div>
