<?php
/**
 * Vue: Catalogue produits (Boutique)
 * Date: 2025-11-25
 * Author: dridi10331
 */

// Convertir PDOStatement en array
$productsArray = [];
if (isset($products)) {
    foreach ($products as $product) {
        $productsArray[] = $product;
    }
}
$products = $productsArray;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique - ImpactShop</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #ffb600;
            --primary-dark: #e6a500;
            --secondary-color: #1e3149;
            --secondary-dark: #15202e;
            --text-color: #5a6570;
            --heading-color: #282f39;
            --bg-light: #f9f9f9;
            --border-color: #ebebeb;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--text-color);
            line-height: 1.8;
            font-size: 15px;
            background: #f9f9f9;
        }
        
        /* Top Bar */
        .top-bar {
            background: var(--secondary-color);
            color: white;
            padding: 10px 0;
            font-size: 13px;
        }
        
        .top-bar a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }
        
        .top-bar a:hover {
            color: var(--primary-color);
        }
        
        .top-bar i {
            color: var(--primary-color);
            margin-right: 5px;
        }
        
        /* Header */
        .site-header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 25px 0;
        }
        
        .site-logo h1 {
            color: var(--secondary-color);
            font-weight: 800;
            margin: 0;
            font-size: 2.5rem;
        }
        
        .site-logo span {
            color: var(--primary-color);
        }
        
        .site-logo a {
            text-decoration: none;
        }
        
        /* Cart Button */
        .cart-button {
            background: var(--primary-color);
            color: var(--secondary-color);
            padding: 15px 35px;
            border-radius: 3px;
            font-weight: 800;
            border: 2px solid var(--primary-color);
            cursor: pointer;
            position: relative;
            font-size: 16px;
            text-transform: uppercase;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .cart-button:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
        }
        
        #cart-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #e74c3c;
            color: white;
            font-size: 12px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 50%;
            min-width: 25px;
            text-align: center;
            display: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }
        
        /* Navigation */
        .main-nav {
            background: var(--secondary-color);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .main-nav .navbar {
            padding: 0;
        }
        
        .main-nav .nav-link {
            color: white !important;
            padding: 20px 30px;
            font-weight: 700;
            font-size: 15px;
            text-transform: uppercase;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .main-nav .nav-link:hover,
        .main-nav .nav-item.active .nav-link {
            background: var(--primary-color);
            color: var(--secondary-color) !important;
        }
        
        .navbar-toggler {
            background: var(--primary-color);
            border: none;
        }
        
        /* Page Title */
        .page-title-section {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--secondary-dark) 100%);
            padding: 60px 0;
            text-align: center;
            color: white;
        }
        
        .page-title-section h1 {
            font-size: 3rem;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0;
        }
        
        /* Products Section */
        .products-section {
            padding: 80px 0;
            background: white;
        }
        
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--heading-color);
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        
        .section-subtitle {
            text-align: center;
            color: var(--text-color);
            font-size: 18px;
            margin-bottom: 60px;
        }
        
        /* Products Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }
        
        .product-card {
            background: white;
            border: 1px solid var(--border-color);
            transition: all 0.3s;
            overflow: hidden;
            position: relative;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .product-image-wrapper {
            position: relative;
            overflow: hidden;
            height: 280px;
        }
        
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .product-card:hover .product-image {
            transform: scale(1.1);
        }
        
        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--primary-color);
            color: var(--secondary-color);
            padding: 8px 15px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            z-index: 2;
        }
        
        .product-info {
            padding: 25px;
        }
        
        .product-category {
            color: var(--primary-color);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 10px;
            display: block;
        }
        
        .product-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--heading-color);
            margin-bottom: 15px;
            line-height: 1.4;
            min-height: 60px;
        }
        
        .product-description {
            color: var(--text-color);
            font-size: 14px;
            line-height: 1.8;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 75px;
        }
        
        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 2px solid var(--border-color);
        }
        
        .product-price {
            font-size: 2rem;
            font-weight: 900;
            color: var(--primary-color);
        }
        
        .add-to-cart-btn {
            background: var(--secondary-color);
            color: white;
            border: 2px solid var(--secondary-color);
            padding: 12px 25px;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .add-to-cart-btn:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--secondary-color);
        }
        
        /* Cart Section */
        .cart-section {
            padding: 80px 0;
            background: white;
        }
        
        .cart-container {
            background: white;
            border: 1px solid var(--border-color);
            padding: 40px;
            border-radius: 3px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        /* Empty Cart */
        .empty-cart {
            text-align: center;
            padding: 80px 20px;
        }
        
        .empty-cart i {
            font-size: 6rem;
            color: #ddd;
            margin-bottom: 30px;
        }
        
        .empty-cart h3 {
            font-size: 2rem;
            color: #999;
            margin-bottom: 15px;
        }
        
        /* Footer */
        .footer {
            background: #1a1a1a;
            color: #c7d0d9;
            padding: 60px 0 20px;
            margin-top: 80px;
        }
        
        .footer h3 {
            color: white;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        
        .copyright {
            background: #111;
            padding: 20px 0;
            text-align: center;
            color: #999;
            margin-top: 40px;
            font-size: 14px;
        }
        
        /* Utilities */
        .hidden {
            display: none !important;
        }
        
        /* Responsive */
        @media (max-width: 991px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
        }
        
        @media (max-width: 768px) {
            .site-logo h1 {
                font-size: 2rem;
            }
            
            .page-title-section h1 {
                font-size: 2rem;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <i class="fas fa-phone"></i> +216 XX XXX XXX
                    <span style="margin-left: 20px;">
                        <i class="fas fa-envelope"></i> contact@impactshop.tn
                    </span>
                </div>
                <div class="col-md-6 text-right">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="site-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="site-logo">
                        <a href="index.php">
                            <h1><span>Impact</span>Shop</h1>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <button class="cart-button" onclick="showCart()">
                        <i class="fas fa-shopping-cart"></i>
                        PANIER
                        <span id="cart-badge">0</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <div class="main-nav">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">
                                <i class="fas fa-home"></i> ACCUEIL
                            </a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="index.php?controller=product&action=shop">
                                <i class="fas fa-store"></i> BOUTIQUE
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=dashboard&action=index">
                                <i class="fas fa-chart-line"></i> DASHBOARD
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=order&action=index">
                                <i class="fas fa-receipt"></i> COMMANDES
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <!-- Page Title -->
    <section class="page-title-section">
        <div class="container">
            <h1><i class="fas fa-shopping-bag"></i> BOUTIQUE SOLIDAIRE</h1>
        </div>
    </section>

    <!-- Shop Section -->
    <section id="shop-section" class="products-section">
        <div class="container">
            <h2 class="section-title">NOS PRODUITS SOLIDAIRES</h2>
            <p class="section-subtitle">Votre achat finance directement une aide vitale pour ceux qui en ont besoin</p>

            <div class="products-grid">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <div class="product-image-wrapper">
                                <span class="product-badge">IMPACT</span>
                                <img src="assets/images/<?php echo htmlspecialchars($product['img_name']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name_fr']); ?>"
                                     class="product-image"
                                     onerror="this.src='https://via.placeholder.com/400x300/1e3149/ffb600?text=<?php echo urlencode(substr($product['name_fr'], 0, 1)); ?>'">
                            </div>
                            <div class="product-info">
                                <span class="product-category">Aide Humanitaire</span>
                                <h3 class="product-title"><?php echo htmlspecialchars($product['name_fr']); ?></h3>
                                <p class="product-description"><?php echo htmlspecialchars($product['description_fr']); ?></p>
                                
                                <div class="product-footer">
                                    <div class="product-price">$<?php echo number_format($product['price'], 2); ?></div>
                                    <button class="add-to-cart-btn" 
                                            onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars(addslashes($product['name_fr'])); ?>', <?php echo $product['price']; ?>, '<?php echo htmlspecialchars(addslashes($product['img_name'])); ?>')">
                                        <i class="fas fa-cart-plus"></i> AJOUTER
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1/-1;">
                        <div class="empty-cart">
                            <i class="fas fa-box-open"></i>
                            <h3>Aucun produit disponible</h3>
                            <p>Les produits seront bientôt disponibles</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Cart Section -->
    <section id="checkout-section" class="cart-section hidden">
        <div class="container">
            <h2 class="section-title">VOTRE PANIER</h2>
            <p class="section-subtitle">Vérifiez vos articles avant de passer commande</p>

            <div class="row">
                <div class="col-lg-8">
                    <div class="cart-container">
                        <div id="cart-items"></div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div style="background: #f9f9f9; padding: 40px; border-radius: 3px; border: 2px solid #ebebeb;">
                        <h3 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 30px;">RÉCAPITULATIF</h3>
                        
                        <div style="display: flex; justify-content: space-between; font-size: 2.5rem; font-weight: 900; padding: 25px 0; border-top: 3px solid #1e3149; border-bottom: 3px solid #1e3149; margin: 25px 0;">
                            <span>TOTAL:</span>
                            <span style="color: #ffb600;">$<span id="total-amount">0.00</span></span>
                        </div>

                        <button style="width: 100%; background: #ffb600; color: #1e3149; border: 2px solid #ffb600; padding: 20px; font-size: 1.3rem; font-weight: 800; cursor: pointer;" onclick="proceedToCheckout()">
                            <i class="fas fa-credit-card"></i> PROCÉDER AU PAIEMENT
                        </button>

                        <button style="width: 100%; background: white; color: #1e3149; border: 2px solid #1e3149; padding: 18px; font-size: 1.1rem; font-weight: 700; cursor: pointer; margin-top: 15px;" onclick="showShop()">
                            <i class="fas fa-arrow-left"></i> CONTINUER LES ACHATS
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h3>ImpactShop</h3>
                    <p>Boutique solidaire d'aide humanitaire. Chaque achat compte!</p>
                </div>
            </div>
        </div>
        <div class="copyright">
            Copyright &copy; 2025 ImpactShop. Développé par dridi10331. Tous droits réservés.
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/shop.js?v=<?php echo time(); ?>"></script>

</body>
</html>