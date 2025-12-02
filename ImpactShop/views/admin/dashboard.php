<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ImpactShop BackOffice</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/style.css?v=3">
    <link rel="stylesheet" href="assets/css/dashboard.css?v=3">
    
    <!-- CSS INLINE - Couleurs Constra forcées -->
    <style>
        :root {
            --constra-yellow: #ffb600;
            --constra-blue: #1e3149;
            --constra-blue-dark: #15202e;
        }
        
        /* Header - Bleu Constra */
        .admin-view header {
            background: var(--constra-blue) !important;
            background: linear-gradient(90deg, var(--constra-blue), var(--constra-blue-dark)) !important;
            color: white !important;
        }
        
        .admin-view header h1 {
            color: white !important;
        }
        
        /* Bouton Retour - Jaune Constra */
        .back-btn {
            background-color: var(--constra-yellow) !important;
            color: var(--constra-blue) !important;
            border: none !important;
        }
        
        .back-btn:hover {
            background-color: #e6a500 !important;
        }
        
        /* Navigation - Active en Bleu */
        .nav-item.active {
            background-color: var(--constra-blue) !important;
            color: white !important;
        }
        
        .nav-item:hover {
            color: var(--constra-blue) !important;
        }
        
        /* Boutons */
        .btn-sm,
        .btn-primary {
            background-color: var(--constra-blue) !important;
            color: white !important;
            border: 2px solid var(--constra-blue) !important;
        }
        
        .btn-sm:hover,
        .btn-primary:hover {
            background-color: var(--constra-yellow) !important;
            border-color: var(--constra-yellow) !important;
            color: var(--constra-blue) !important;
        }
        
        /* Card Headers */
        .card-header h3 {
            color: var(--constra-blue) !important;
        }
        
        .card-header h3 i {
            color: var(--constra-yellow) !important;
        }
        
        /* Tables */
        .mini-table thead {
            background-color: var(--constra-blue) !important;
        }
        
        /* Admin Header Title */
        .admin-header h2 {
            color: var(--constra-blue) !important;
        }
        
        /* Badge Primary */
        .badge-primary {
            background-color: var(--constra-yellow) !important;
            color: var(--constra-blue) !important;
        }
    </style>
</head>
<body>
    <div class="admin-view">
        <!-- Header -->
        <header>
            <div>
                <h1><i class="fas fa-chart-line"></i> Dashboard ImpactShop</h1>
                <a href="index.php" class="back-btn">← Retour à l'Accueil</a>
            </div>
        </header>

        <!-- Main Container -->
        <div class="admin-container">
            
            <!-- Navigation -->
            <div class="admin-nav">
                <a href="index.php?controller=dashboard&action=index" class="nav-item active">
                    <i class="fas fa-chart-bar"></i> Dashboard
                </a>
                <a href="index.php?controller=product&action=index" class="nav-item">
                    <i class="fas fa-box"></i> Produits
                </a>
                <a href="index.php?controller=order&action=index" class="nav-item">
                    <i class="fas fa-shopping-cart"></i> Commandes
                </a>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <!-- Revenu Total -->
                <div class="stat-card revenue">
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Revenu Total</h3>
                        <p class="stat-value">$<?php echo number_format($stats['total_revenue'] ?? 0, 2); ?></p>
                        <small class="stat-label">Toutes les commandes payées</small>
                    </div>
                </div>

                <!-- Revenu du Mois -->
                <div class="stat-card monthly">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Revenu du Mois</h3>
                        <p class="stat-value">$<?php echo number_format($stats['monthly_revenue'] ?? 0, 2); ?></p>
                        <small class="stat-label">Novembre 2025</small>
                    </div>
                </div>

                <!-- Total Commandes -->
                <div class="stat-card orders">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Commandes</h3>
                        <p class="stat-value"><?php echo $stats['total_orders'] ?? 0; ?></p>
                        <small class="stat-label">Commandes passées</small>
                    </div>
                </div>

                <!-- En Attente -->
                <div class="stat-card pending">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3>En Attente</h3>
                        <p class="stat-value"><?php echo $stats['pending_orders'] ?? 0; ?></p>
                        <small class="stat-label">À traiter</small>
                    </div>
                </div>

                <!-- Clients -->
                <div class="stat-card customers">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Clients</h3>
                        <p class="stat-value"><?php echo $stats['total_customers'] ?? 0; ?></p>
                        <small class="stat-label">Clients enregistrés</small>
                    </div>
                </div>

                <!-- Panier Moyen -->
                <div class="stat-card average">
                    <div class="stat-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Panier Moyen</h3>
                        <p class="stat-value">$<?php echo number_format($stats['average_order'] ?? 0, 2); ?></p>
                        <small class="stat-label">Par commande</small>
                    </div>
                </div>
            </div>

            <!-- Dashboard Row -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 25px; margin-bottom: 30px;">
                
                <!-- Recent Orders -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-shopping-cart"></i> Commandes Récentes</h3>
                        <a href="index.php?controller=order&action=index" class="btn btn-sm">Voir Tout</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentOrders)): ?>
                            <table class="mini-table">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Client</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($recentOrders, 0, 5) as $order): ?>
                                        <tr>
                                            <td><strong>#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></strong></td>
                                            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                            <td><strong>$<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                                            <td>
                                                <?php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'paid' => 'success',
                                                    'cancelled' => 'danger'
                                                ];
                                                $statusLabels = [
                                                    'pending' => 'En attente',
                                                    'paid' => 'Payé',
                                                    'cancelled' => 'Annulé'
                                                ];
                                                $badgeClass = $statusColors[$order['status']] ?? 'default';
                                                $statusLabel = $statusLabels[$order['status']] ?? $order['status'];
                                                ?>
                                                <span class="badge badge-<?php echo $badgeClass; ?>">
                                                    <?php echo $statusLabel; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p style="text-align: center; padding: 40px; color: #999;">
                                <i class="fas fa-inbox" style="font-size: 3rem; display: block; margin-bottom: 15px;"></i>
                                Aucune commande récente
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Top Products -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-star"></i> Produits Populaires</h3>
                        <a href="index.php?controller=product&action=index" class="btn btn-sm">Gérer</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($topProducts)): ?>
                            <table class="mini-table">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Produit</th>
                                        <th>Vendus</th>
                                        <th>Revenu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($topProducts as $product): ?>
                                        <tr>
                                            <td>
                                                <img src="assets/images/<?php echo htmlspecialchars($product['img_name']); ?>" 
                                                     alt="<?php echo htmlspecialchars($product['name_fr']); ?>"
                                                     onerror="this.src='https://via.placeholder.com/60x60/1e3149/ffb600?text=<?php echo urlencode(substr($product['name_fr'], 0, 2)); ?>'">
                                            </td>
                                            <td><?php echo htmlspecialchars($product['name_fr']); ?></td>
                                            <td><strong><?php echo $product['total_sold']; ?></strong></td>
                                            <td><strong style="color: #ffb600;">$<?php echo number_format($product['total_revenue'], 2); ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p style="text-align: center; padding: 40px; color: #999;">
                                <i class="fas fa-box-open" style="font-size: 3rem; display: block; margin-bottom: 15px;"></i>
                                Aucun produit vendu
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <!-- Revenue Chart -->
            <div class="dashboard-card" style="grid-column: 1 / -1;">
                <div class="card-header">
                    <h3><i class="fas fa-chart-area"></i> Évolution des Revenus (12 derniers mois)</h3>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" style="max-height: 400px;"></canvas>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <script>
        // Données du graphique
        const monthlyData = <?php echo json_encode($monthlyStats ?? []); ?>;
        
        // Préparer les données
        const labels = monthlyData.map(item => {
            const date = new Date(item.month + '-01');
            return date.toLocaleDateString('fr-FR', { month: 'short', year: 'numeric' });
        }).reverse();
        
        const revenues = monthlyData.map(item => parseFloat(item.revenue)).reverse();
        const orders = monthlyData.map(item => parseInt(item.order_count)).reverse();

        // Configuration du graphique - Couleurs Constra
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Revenus ($)',
                        data: revenues,
                        borderColor: '#ffb600',
                        backgroundColor: 'rgba(255, 182, 0, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Commandes',
                        data: orders,
                        borderColor: '#1e3149',
                        backgroundColor: 'rgba(30, 49, 73, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                family: 'Montserrat',
                                size: 13,
                                weight: '600'
                            },
                            padding: 20,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(30, 49, 73, 0.9)',
                        titleFont: {
                            family: 'Montserrat',
                            size: 14,
                            weight: '700'
                        },
                        bodyFont: {
                            family: 'Montserrat',
                            size: 13
                        },
                        padding: 12,
                        cornerRadius: 3
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Revenus ($)',
                            font: {
                                family: 'Montserrat',
                                size: 12,
                                weight: '700'
                            }
                        },
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toFixed(0);
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Nombre de Commandes',
                            font: {
                                family: 'Montserrat',
                                size: 12,
                                weight: '700'
                            }
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>