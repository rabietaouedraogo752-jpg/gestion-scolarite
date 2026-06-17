<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Créer un étudiant</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
	<h2>Créer un étudiant</h2>

	@php
		$prefill = $prefill ?? request()->all();
	@endphp

	@if(!empty($prefill['name']) || !empty($prefill['email']) || !empty($prefill['date_naissance']) || !empty($prefill['telephone']))
		<div class="alert alert-info">
			<h5 class="mb-2">Informations préremplies</h5>
			<ul class="mb-0">
				@if(!empty($prefill['name']))<li><strong>Nom complet :</strong> {{ $prefill['name'] }}</li>@endif
				@if(!empty($prefill['email']))<li><strong>Email :</strong> {{ $prefill['email'] }}</li>@endif
				@if(!empty($prefill['date_naissance']))<li><strong>Date de naissance :</strong> {{ $prefill['date_naissance'] }}</li>@endif
				@if(!empty($prefill['telephone']))<li><strong>Téléphone :</strong> {{ $prefill['telephone'] }}</li>@endif
			</ul>
		</div>
	@endif

	@if(session('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
	@endif

	@if($errors->any())
		<div class="alert alert-danger">
			<ul class="mb-0">
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<form method="post" action="{{ route('gestion.creer_etudiant.store') }}">
		@csrf
		<div class="mb-3">
			<label class="form-label">Nom complet</label>
			<input type="text" name="name" class="form-control" value="{{ old('name', $prefill['name'] ?? '') }}" required>
		</div>
		<div class="mb-3">
			<label class="form-label">Email</label>
			<input type="email" name="email" class="form-control" value="{{ old('email', $prefill['email'] ?? '') }}" required>
		</div>
		<div class="mb-3">
			<label class="form-label">Matricule (INE)</label>
			<input type="text" name="matricule" class="form-control" value="{{ old('matricule', $prefill['matricule'] ?? '') }}" required>
		</div>
		<div class="mb-3">
			<label class="form-label">Genre</label>
			<select name="genre" class="form-select" required>
				<option value="">-- Choisir --</option>
				<option value="M" {{ old('genre') == 'M' ? 'selected' : '' }}>Masculin</option>
				<option value="F" {{ old('genre') == 'F' ? 'selected' : '' }}>Féminin</option>
			</select>
		</div>
		<div class="row">
			<div class="col-md-3 mb-3">
				<label class="form-label">Filière</label>
				<input type="text" name="filiere" class="form-control" value="{{ old('filiere') }}" placeholder="Entrez la filière" required>
			</div>
			<div class="col-md-3 mb-3">
				<label class="form-label">Niveau</label>
				<input type="text" name="niveau" class="form-control" value="{{ old('niveau') }}" placeholder="Entrez le niveau" required>
			</div>
			<div class="col-md-6 mb-3">
				<label class="form-label">Année académique</label>
				<input type="text" name="annee_academique" class="form-control" value="{{ old('annee_academique') }}" placeholder="Ex: 2025-2026" required>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 mb-3">
				<label class="form-label">Date de naissance</label>
				<input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance', $prefill['date_naissance'] ?? '') }}" required>
			</div>
			<div class="col-md-6 mb-3">
				<label class="form-label">Lieu de naissance</label>
				<input type="text" name="lieu_naissance" class="form-control" value="{{ old('lieu_naissance', $prefill['lieu_naissance'] ?? '') }}" required>
			</div>
		</div>

		<div class="d-flex gap-2">
			<button class="btn btn-primary" type="submit">Créer</button>
			<a href="{{ route('gestion.liste_etudiant') }}" class="btn btn-secondary">Annuler</a>
		</div>
	</form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
