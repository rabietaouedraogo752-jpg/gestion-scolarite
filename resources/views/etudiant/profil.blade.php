@extends('layouts.app') {{-- Assure-toi que c'est bien le nom de ton layout principal (ex: layouts.app ou layouts.master) --}}

@section('content')
<div class="container-fluid p-4">
    <div class="card shadow-sm p-4" style="max-width: 600px; margin: 0 auto;">
        <h3 class="mb-4">Mon Profil Étudiant</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('etudiant.profil.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="username" class="form-label fw-bold">Nom d'utilisateur</label>
                <input type="text" name="username" id="username" class="form-control" 
                       value="{{ old('username', auth()->user()->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Adresse Email</label>
                <input type="email" name="email" id="email" class="form-control" 
                       value="{{ old('email', auth()->user()->email) }}" required>
            </div>

            <div class="mb-3">
                <label for="whatsapp" class="form-label fw-bold">Numéro WhatsApp</label>
                <input type="text" name="whatsapp" id="whatsapp" class="form-control" 
                       value="{{ old('whatsapp', $etudiant->telephone ?? '') }}" required>
            </div>

            <hr class="my-4">
            <h5 class="text-muted mb-3">Sécurité (Laisser vide pour ne pas modifier)</h5>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold">Nouveau mot de passe</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label fw-bold">Confirmer le nouveau mot de passe</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">Enregistrer les modifications</button>
        </form>
    </div>
</div>
@endsection