<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Taux</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="h2 mb-4">Ajouter un Taux</h1>

        <!-- Formulaire d'ajout -->
        <form action="{{ route('taux.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="valeur" class="form-label">Valeur du Taux</label>
                <input type="text" class="form-control" id="valeur" name="valeur" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

    <!-- Bootstrap JS et dépendances -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>