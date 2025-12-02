<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue - ImpactShop</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #ffb600;
            --primary-dark: #e6a500;
            --secondary: #1e3149;
            --secondary-dark: #15202e;
            --text-dark: #5a6570;
            --heading-color: #282f39;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            width: 100%;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 50%, var(--primary) 100%);
            position: relative;
            overflow: hidden;
            padding: 20px;
        }
        
        /* Animated Background Pattern */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 10px,
                rgba(255, 182, 0, 0.03) 10px,
                rgba(255, 182, 0, 0.03) 20px
            );
            animation: moveBackground 20s linear infinite;
            z-index: 1;
        }
        
        @keyframes moveBackground {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }
        
        /* Main Container */
        .main-container {
            position: relative;
            z-index: 10;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 80px rgba(0, 0, 0, 0.4);
            padding: 60px 50px;
            max-width: 1000px;
            width: 100%;
            animation: fadeInUp 0.8s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Logo Section */
        .logo-section {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .logo-section h1 {
            font-size: 4rem;
            font-weight: 900;
            color: var(--secondary);
            margin-bottom: 10px;
            letter-spacing: -1px;
        }
        
        .logo-section h1 .impact {
            color: var(--secondary);
        }
        
        .logo-section h1 .shop {
            color: var(--primary);
        }
        
        .logo-section .tagline {
            font-size: 1.2rem;
            color: var(--text-dark);
            font-weight: 600;
            margin-top: 10px;
        }
        
        /* Welcome Section */
        .welcome-section {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .welcome-section h2 {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--heading-color);
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .welcome-section h2 i {
            color: var(--primary);
            margin-right: 15px;
        }
        
        .welcome-section p {
            font-size: 1.2rem;
            color: var(--text-dark);
            font-weight: 600;
        }
        
        /* Role Cards Container */
        .roles-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 35px;
            margin-bottom: 40px;
        }
        
        /* Role Card */
        .role-card {
            background: white;
            border: 4px solid var(--secondary);
            border-radius: 20px;
            padding: 50px 35px;
            text-align: center;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            text-decoration: none;
            display: block;
        }
        
        .role-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);
            opacity: 0;
            transition: opacity 0.5s;
            z-index: 0;
        }
        
        .role-card:hover::before {
            opacity: 1;
        }
        
        .role-card:hover {
            border-color: var(--primary);
            transform: translateY(-15px) scale(1.05);
            box-shadow: 0 25px 50px rgba(30, 49, 73, 0.4);
        }
        
        .role-card > * {
            position: relative;
            z-index: 1;
        }
        
        /* Role Icon */
        .role-icon {
            width: 120px;
            height: 120px;
            background: var(--secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            transition: all 0.5s;
            box-shadow: 0 10px 30px rgba(30, 49, 73, 0.3);
        }
        
        .role-card:hover .role-icon {
            background: var(--primary);
            transform: rotate(360deg) scale(1.1);
            box-shadow: 0 15px 40px rgba(255, 182, 0, 0.5);
        }
        
        .role-icon i {
            font-size: 3.5rem;
            color: white;
            transition: color 0.5s;
        }
        
        .role-card:hover .role-icon i {
            color: var(--secondary);
        }
        
        /* Role Title */
        .role-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--secondary);
            margin-bottom: 20px;
            text-transform: uppercase;
            transition: color 0.5s;
            letter-spacing: 1px;
        }
        
        .role-card:hover .role-title {
            color: white;
        }
        
        /* Role Description */
        .role-description {
            font-size: 1.1rem;
            color: var(--text-dark);
            line-height: 1.8;
            font-weight: 600;
            transition: color 0.5s;
        }
        
        .role-card:hover .role-description {
            color: rgba(255, 255, 255, 0.95);
        }
        
        /* Footer Section */
        .footer-section {
            text-align: center;
            padding-top: 35px;
            border-top: 3px solid #ebebeb;
        }
        
        .footer-section p {
            font-size: 1rem;
            color: var(--text-dark);
            font-weight: 600;
        }
        
        .footer-section i {
            color: var(--primary);
            margin: 0 8px;
        }
        
        .footer-section strong {
            color: var(--secondary);
            font-weight: 800;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                padding: 40px 30px;
            }
            
            .logo-section h1 {
                font-size: 3rem;
            }
            
            .welcome-section h2 {
                font-size: 1.8rem;
            }
            
            .roles-container {
                grid-template-columns: 1fr;
                gap: 25px;
            }
            
            .role-card {
                padding: 40px 30px;
            }
            
            .role-icon {
                width: 100px;
                height: 100px;
            }
            
            .role-icon i {
                font-size: 3rem;
            }
            
            .role-title {
                font-size: 1.6rem;
            }
            
            .role-description {
                font-size: 1rem;
            }
        }
        
        @media (max-width: 480px) {
            body {
                padding: 15px;
            }
            
            .main-container {
                padding: 30px 20px;
            }
            
            .logo-section h1 {
                font-size: 2.5rem;
            }
            
            .logo-section .tagline {
                font-size: 1rem;
            }
            
            .welcome-section h2 {
                font-size: 1.5rem;
            }
            
            .welcome-section p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

    <!-- Main Container -->
    <div class="main-container">
        
        <!-- Logo Section -->
        <div class="logo-section">
            <h1>
                <span class="impact">Impact</span><span class="shop">Shop</span>
            </h1>
            <p class="tagline">
                <i class="fas fa-heart" style="color: var(--primary);"></i>
                Boutique Solidaire d'Aide Humanitaire
            </p>
        </div>

        <!-- Welcome Section -->
        <div class="welcome-section">
            <h2>
                <i class="fas fa-hand-holding-heart"></i>
                Bienvenue sur ImpactShop
            </h2>
            <p>Veuillez sélectionner votre rôle pour continuer</p>
        </div>

        <!-- Role Cards -->
        <div class="roles-container">
            
            <!-- Acheteur Card -->
            <a href="index.php?controller=product&action=shop" class="role-card">
                <div class="role-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 class="role-title">Acheteur</h3>
                <p class="role-description">
                    Parcourir et acheter des produits d'aide humanitaire
                </p>
            </a>

            <!-- Administrateur Card -->
            <a href="index.php?controller=dashboard&action=index" class="role-card">
                <div class="role-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <h3 class="role-title">Administrateur</h3>
                <p class="role-description">
                    Gérer le catalogue et voir le dashboard
                </p>
            </a>

        </div>

        <!-- Footer Section -->
        <div class="footer-section">
            <p>
                <i class="fas fa-heart"></i>
                Chaque achat compte
                <i class="fas fa-circle" style="font-size: 0.4rem; vertical-align: middle;"></i>
                <strong></strong>
            </p>
        </div>

    </div>

</body>
</html>