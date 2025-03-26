<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dépenses des Leads</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="h2 mb-4">Dépenses des Leads</h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Dépense</th>
                    <th>Montant</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Lead ID</th>
                    <th>Nom</th>
                    <th>Statut</th>
                    <th>Téléphone</th>
                    <th>Manager</th>
                    <th>Employé</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($depenses as $depense)
                    <tr>
                        <td>{{ $depense['id'] }}</td>
                        <td>{{ $depense['montant'] }}</td>
                        <td>{{ $depense['description'] ?? 'N/A' }}</td>
                        <td>{{ $depense['created_at'] }}</td>
                        <td>{{ $depense['lead']['lead_id'] }}</td>
                        <td>{{ $depense['lead']['name'] }}</td>
                        <td>{{ $depense['lead']['status'] }}</td>
                        <td>{{ $depense['lead']['phone'] ?? 'N/A' }}</td>
                        <td>{{ $depense['lead']['manager_name'] ?? 'N/A' }}</td>
                        <td>{{ $depense['lead']['employee_name'] ?? 'N/A' }}</td>

                        <!-- mitoviy am ticket iany -->
                        <td>
                                <a href="{{ route('depenses.leads.edit', $depense['id']) }}" class="btn btn-primary btn-sm">Modifier</a>
                                <form action="{{ route('depenses.leads.destroy', $depense['id']) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette dépense ?')">Supprimer</button>
                                </form>
                            </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">Retour au Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>