<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Commande #<?php echo $order['id'] ?? ''; ?> - ImpactShop</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/style.css?v=3">
    <link rel="stylesheet" href="assets/css/dashboard.css?v=3">
    
    <!-- CSS INLINE - Couleurs Constra -->
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
        
        /* Admin Header Title */
        .admin-header h2 {
            color: var(--constra-blue) !important;
        }
        
        /* Order Details */
        .order-details-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 30px;
        }
        
        .details-card {
            background: white;
            padding: 30px;
            border-radius: 3px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .details-card h3 {
            color: var(--constra-blue);
            font-weight: 800;
            font-size: 1.5rem;
            margin-bottom: 25px;
            text-transform: uppercase;
            border-bottom: 3px solid var(--constra-yellow);
            padding-bottom: 15px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #ebebeb;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 700;
            color: #282f39;
            font-size: 15px;
        }
        
        .detail-value {
            color: #5a6570;
            font-size: 15px;
        }
        
        /* Order Items */
        .order-items-card {
            grid-column: 1 / -1;
            background: white;
            padding: 30px;
            border-radius: 3px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .order-item {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px;
            border: 1px solid #ebebeb;
            border-radius: 3px;
            margin-bottom: 15px;
            background: #f9f9f9;
        }
        
        .order-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 3px;
            border: 2px solid var(--constra-yellow);
        }
        
        .item-info {
            flex-grow: 1;
        }
        
        .item-name {
            font-weight: 700;
            font-size: 1.2rem;
            color: #282f39;
            margin-bottom: 5px;
        }
        
        .item-price {
            color: #5a6570;
            font-size: 1rem;
        }
        
        .item-total {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--constra-yellow);
        }
        
        /* Total */
        .total-box {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 3px;
            margin-top: 30px;
            border: 2px solid var(--constra-yellow);
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 2rem;
            font-weight: 900;
            color: #282f39;
        }
        
        .total-row .amount {
            color: var(--constra-yellow);
        }
        
        /* Status Update Form */
        .status-form {
            background: #f9f9f9;
            padding: 25px;
            border-radius: 3px;
            margin-top: 20px;
        }
        
        .status-form select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ebebeb;
            border-radius: 3px;
            font-size: 15px;
            margin-bottom: 15px;
        }
        
        .btn-update {
            background: var(--constra-yellow);
            color: var(--constra-blue);
            border: 2px solid var(--constra-yellow);
            padding: 12px 30px;
            font-weight: 800;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
        }
        
        .btn-update:hover {
            background: var(--constra-blue);
            border-color: var(--constra-blue);
            color: white;
        }
        
        /* Badges */
        .badge {
            padding: 8px 15px;
            border-radius: 3px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
        }
        
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
        
        @media (max-width: 768px) {
            .order-details-container {
                grid-template-columns: 1fr;
            }
            
            .order-item {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="admin-view">
        <!-- Header -->
        <header>
            <div>
                <h1><i class="fas fa-file-invoice"></i> Détails de la Commande</h1>
                <a href="index.php?controller=order&action=index" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Retour aux Commandes
                </a>
            </div>
        </header>

        <!-- Main Container -->
        <div class="admin-container">
            
            <!-- Navigation -->
            <div class="admin-nav">
                <a href="index.php?controller=dashboard&action=index" class="nav-item">
                    <i class="fas fa-chart-bar"></i> Dashboard
                </a>
                <a href="index.php?controller=product&action=index" class="nav-item">
                    <i class="fas fa-box"></i> Produits
                </a>
                <a href="index.php?controller=order&action=index" class="nav-item active">
                    <i class="fas fa-shopping-cart"></i> Commandes
                </a>
            </div>

            <!-- Alerts -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <strong>Succès!</strong> <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>Erreur!</strong> <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- Header -->
            <div class="admin-header">
                <h2>
                    <i class="fas fa-file-alt"></i> 
                    Commande #<?php echo str_pad($order['id'] ?? 0, 6, '0', STR_PAD_LEFT); ?>
                </h2>
                <div>
                    <?php
                    $statusConfig = [
                        'pending' => ['class' => 'warning', 'label' => 'En attente', 'icon' => 'clock'],
                        'paid' => ['class' => 'success', 'label' => 'Payé', 'icon' => 'check-circle'],
                        'cancelled' => ['class' => 'danger', 'label' => 'Annulé', 'icon' => 'times-circle']
                    ];
                    
                    $config = $statusConfig[$order['status'] ?? 'pending'];
                    ?>
                    <span class="badge badge-<?php echo $config['class']; ?>" style="font-size: 1.1rem; padding: 12px 25px;">
                        <i class="fas fa-<?php echo $config['icon']; ?>"></i>
                        <?php echo $config['label']; ?>
                    </span>
                </div>
            </div>

            <!-- Order Details Grid -->
            <div class="order-details-container">
                
                <!-- Customer Info -->
                <div class="details-card">
                    <h3><i class="fas fa-user"></i> Informations Client</h3>
                    
                    <div class="detail-row">
                        <span class="detail-label">Nom complet:</span>
                        <span class="detail-value">
                            <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?>
                        </span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">
                            <i class="fas fa-envelope" style="color: var(--constra-yellow); margin-right: 5px;"></i>
                            <?php echo htmlspecialchars($order['email']); ?>
                        </span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Téléphone:</span>
                        <span class="detail-value">
                            <i class="fas fa-phone" style="color: var(--constra-yellow); margin-right: 5px;"></i>
                            <?php echo htmlspecialchars($order['phone']); ?>
                        </span>
                    </div>
                </div>

                <!-- Order Info -->
                <div class="details-card">
                    <h3><i class="fas fa-info-circle"></i> Informations Commande</h3>
                    
                    <div class="detail-row">
                        <span class="detail-label">Date de création:</span>
                        <span class="detail-value">
                            <i class="fas fa-calendar" style="color: var(--constra-yellow); margin-right: 5px;"></i>
                            <?php echo date('d/m/Y', strtotime($order['created_at'])); ?>
                            <br>
                            <small>
                                <i class="fas fa-clock" style="margin-right: 3px;"></i>
                                <?php echo date('H:i', strtotime($order['created_at'])); ?>
                            </small>
                        </span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Méthode de paiement:</span>
                        <span class="detail-value" style="text-transform: uppercase; font-weight: 700;">
                            <i class="fab fa-paypal" style="color: #0070ba; margin-right: 5px;"></i>
                            <?php echo htmlspecialchars($order['payment_method']); ?>
                        </span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Montant total:</span>
                        <span class="detail-value" style="font-size: 1.5rem; font-weight: 800; color: var(--constra-yellow);">
                            $<?php echo number_format($order['total_amount'], 2); ?>
                        </span>
                    </div>

                    <!-- Status Update Form -->
                    <div class="status-form">
                        <h4 style="margin-bottom: 15px; color: var(--constra-blue); font-weight: 700;">
                            <i class="fas fa-edit"></i> Modifier le Statut
                        </h4>
                        <form action="index.php?controller=order&action=updateStatus" method="post">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <select name="status" required>
                                <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>
                                    En attente
                                </option>
                                <option value="paid" <?php echo $order['status'] === 'paid' ? 'selected' : ''; ?>>
                                    Payé
                                </option>
                                <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>
                                    Annulé
                                </option>
                            </select>
                            <button type="submit" class="btn-update">
                                <i class="fas fa-save"></i> METTRE À JOUR
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="order-items-card">
                    <h3><i class="fas fa-shopping-bag"></i> Articles de la Commande</h3>
                    
                    <?php if (!empty($orderItems)): ?>
                        <?php foreach ($orderItems as $item): ?>
                            <div class="order-item">
                                <img src="assets/images/<?php echo htmlspecialchars($item['img_name']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['name_fr']); ?>"
                                     onerror="this.src='https://via.placeholder.com/100x100/1e3149/ffb600?text=<?php echo urlencode(substr($item['name_fr'], 0, 1)); ?>'">
                                
                                <div class="item-info">
                                    <div class="item-name"><?php echo htmlspecialchars($item['name_fr']); ?></div>
                                    <div class="item-price">
                                        Prix unitaire: $<?php echo number_format($item['unit_price'], 2); ?> 
                                        × Quantité: <?php echo $item['quantity']; ?>
                                    </div>
                                </div>
                                
                                <div class="item-total">
                                    $<?php echo number_format($item['subtotal'], 2); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Total -->
                        <div class="total-box">
                            <div class="total-row">
                                <span>TOTAL:</span>
                                <span class="amount">$<?php echo number_format($order['total_amount'], 2); ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 60px; color: #999;">
                            <i class="fas fa-inbox" style="font-size: 4rem; margin-bottom: 20px; color: #ddd;"></i>
                            <h3>Aucun article trouvé</h3>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

        </div>
    </div>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>