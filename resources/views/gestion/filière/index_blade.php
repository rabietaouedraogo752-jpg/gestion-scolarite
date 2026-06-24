<a href="{{ route('filiere.edit', $filiere->id) }}" class="btn btn-sm btn-warning">
    <i class="bi bi-pencil"></i> Modifier
</a>

<form action="{{ route('filiere.destroy', $filiere->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette filière ?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger">
        <i class="bi bi-trash"></i> Supprimer
    </button>
</form>