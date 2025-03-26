<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Analytics</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #1cc88a;
            --danger-color: #e74a3b;
            --warning-color: #f6c23e;
            --info-color: #36b9cc;
            --dark-color: #5a5c69;
            --light-color: #f8f9fc;
        }
        
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fc;
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 0%, #224abe 100%);
            min-height: 100vh;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
            padding: 1rem;
            margin: 0.25rem 0;
            border-left: 0.25rem solid transparent;
        }
        
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-left: 0.25rem solid var(--secondary-color);
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link i {
            margin-right: 0.5rem;
        }
        
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1.5rem 0 rgba(58, 59, 69, 0.2);
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 700;
        }
        
        .card-title {
            color: var(--dark-color);
            font-weight: 700;
        }
        
        .stat-card {
            border-left: 0.25rem solid;
        }
        
        .stat-card.clients {
            border-left-color: var(--primary-color);
        }
        
        .stat-card.tickets {
            border-left-color: var(--secondary-color);
        }
        
        .stat-card.leads {
            border-left-color: var(--info-color);
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
        }
        
        .page-header {
            border-bottom: 1px solid #e3e6f0;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }
        
        .form-select {
            border-radius: 0.35rem;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block sidebar">
                <div class="sidebar-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white">CRM Dashboard</h4>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">
                                <i class="fas fa-fw fa-tachometer-alt"></i>
                                Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('taux.create') }}">
                                <i class="fas fa-fw fa-percentage"></i>
                                Modifier le taux
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('tickets.index') }}">
                                <i class="fas fa-fw fa-ticket-alt"></i>
                                Tickets
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('leads.index') }}">
                                <i class="fas fa-fw fa-user-tag"></i>
                                Leads
                            </a>
                        </li>
                    </ul>
                    <div class="mt-auto p-3">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <main role="main" class="col-md-10 ml-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 page-header">
                    <h1 class="h2"><i class="fas fa-tachometer-alt me-2"></i>Tableau de bord</h1>
                    <div class="mb-3">
                        <div class="input-group">
                            <label class="input-group-text" for="yearSelect"><i class="fas fa-calendar-alt"></i></label>
                            <select class="form-select" id="yearSelect">
                            </select>
                        </div>
                    </div>
                </div>

               <!-- Section des totaux -->
                <div class="row mb-4">
                    <!-- Clients Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card clients h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Total Clients</h5>
                                        <span class="stat-value" id="totalClients">0</span>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tickets Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card tickets h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Total Tickets</h5>
                                        <span class="stat-value" id="totalTickets">0</span>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Leads Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card leads h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Total Leads</h5>
                                        <span class="stat-value" id="totalLeads">0</span>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-tag fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dépenses Tickets Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card tickets h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Dépenses Tickets</h5>
                                        <span class="stat-value" id="totalDepensesTickets">0</span>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('depensesTickets.index') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-list me-1"></i> Voir la liste
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dépenses Leads Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card leads h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Dépenses Leads</h5>
                                        <span class="stat-value" id="totalDepensesLeads">0</span>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('depensesLeads.index') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-list me-1"></i> Voir la liste
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Graphiques -->
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Clients par mois</h5>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-link text-muted" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#">Exporter</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="clientsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>Tickets par statut</h5>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-link text-muted" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#">Exporter</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="ticketsByStatusChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-user-tag me-2"></i>Leads par statut</h5>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-link text-muted" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#">Exporter</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="leadsByStatusChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script>
        let clientsChart;
        let ticketsByStatusChart;
        let leadsByStatusChart;

        // Fonction pour créer ou mettre à jour un graphique
        function createChart(chartElementId, labels, data, label, type = 'bar') {
            const chartElement = document.getElementById(chartElementId);
            if (!chartElement) {
                console.error(`Élément avec l'ID ${chartElementId} non trouvé.`);
                return;
            }

            const ctx = chartElement.getContext('2d');
            if (window[chartElementId + 'Chart']) {
                window[chartElementId + 'Chart'].destroy();
            }

            let backgroundColors;
            if (chartElementId === 'clientsChart') {
                backgroundColors = 'rgba(78, 115, 223, 0.5)';
            } else if (chartElementId === 'ticketsByStatusChart') {
                backgroundColors = [
                    'rgba(28, 200, 138, 0.5)',
                    'rgba(54, 185, 204, 0.5)',
                    'rgba(246, 194, 62, 0.5)',
                    'rgba(231, 74, 59, 0.5)'
                ];
            } else {
                backgroundColors = [
                    'rgba(78, 115, 223, 0.5)',
                    'rgba(28, 200, 138, 0.5)',
                    'rgba(246, 194, 62, 0.5)'
                ];
            }

            window[chartElementId + 'Chart'] = new Chart(ctx, {
                type: type,
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: Array.isArray(backgroundColors) ? backgroundColors.map(c => c.replace('0.5', '1')) : backgroundColors.replace('0.5', '1'),
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                drawBorder: false
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    }
                }
            });
        }

        async function fetchCustomersByYear(year) {
            const response = await fetch(`/api/customers-by-year?year=${year}`);
            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }
            const data = await response.json();
            console.log('Données des clients par année :', data);
            return data;
        }

        //F total des clients
        async function fetchTotalCustomers() {
            const response = await fetch('/api/total-customers');
            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }
            return response.json();
        }

        // F total des tickets
        async function fetchTotalTickets() {
            const response = await fetch('/api/total-tickets');
            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }
            return response.json();
        }

        // F récupérer les tickets par statut
        async function fetchTicketsByStatus() {
            const response = await fetch('/api/tickets-by-status');
            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }
            return response.json();
        }

        //F  total des leads
        async function fetchTotalLeads() {
            const response = await fetch('/api/total-leads');
            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }
            return response.json();
        }

        async function fetchTotalDepensesTickets() {
            const response = await fetch('/api/total-depenses-tickets');
            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }
            return response.json();
        }

        // Fonction pour récupérer le total des dépenses leads
        async function fetchTotalDepensesLeads() {
            const response = await fetch('/api/total-depenses-leads');
            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }
            return response.json();
        }

        // F récupérer les leads par statut
        async function fetchLeadsByStatus() {
            const response = await fetch('/api/leads-by-status');
            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }
            return response.json();
        }

        // Écouter les changements dans le sélecteur d'année
        document.getElementById('yearSelect').addEventListener('change', async function() {
            const selectedYear = this.value;
            const customersData = await fetchCustomersByYear(selectedYear);
            createChart('clientsChart', customersData.labels, customersData.data, 'Nombre de clients');
        });

        // Hita vao miditra ny page
    window.addEventListener('load', async () => {
        const yearSelect = document.getElementById('yearSelect');

        // Ajouter les 5 dernières années
        const currentYear = new Date().getFullYear();
        for (let year = currentYear; year >= currentYear - 4; year--) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            if (year === currentYear) option.selected = true;
            yearSelect.appendChild(option);
        }

        // Charger les données initiales pour l'année courante
        const initialCustomersData = await fetchCustomersByYear(currentYear);
        createChart('clientsChart', initialCustomersData.labels, initialCustomersData.data, 'Nombre de clients');

        // Afficher les totaux
        try {
            const totalCustomers = await fetchTotalCustomers();
            document.getElementById('totalClients').textContent = totalCustomers;

            const totalTickets = await fetchTotalTickets();
            document.getElementById('totalTickets').textContent = totalTickets;

            const totalLeads = await fetchTotalLeads();
            document.getElementById('totalLeads').textContent = totalLeads;

            // Ajoutez ces deux lignes pour les totaux des dépenses
            const totalDepensesTickets = await fetchTotalDepensesTickets();
            document.getElementById('totalDepensesTickets').textContent = totalDepensesTickets;

            const totalDepensesLeads = await fetchTotalDepensesLeads();
            document.getElementById('totalDepensesLeads').textContent = totalDepensesLeads;

            const ticketsByStatus = await fetchTicketsByStatus();
            createChart('ticketsByStatusChart', ticketsByStatus.labels, ticketsByStatus.data, 'Nombre de tickets', 'doughnut');

            const leadsByStatus = await fetchLeadsByStatus();
            createChart('leadsByStatusChart', leadsByStatus.labels, leadsByStatus.data, 'Nombre de leads', 'doughnut');
        } catch (error) {
            console.error('Erreur lors de la récupération des données :', error);
        }
    });
    </script>
</body>
</html>