<?php
/**
 * Configuration des Emails Gmail
 * À configurer avec tes identifiants Gmail
 */

return [
    // Configuration SMTP Gmail
    'smtp' => [
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'secure' => 'tls', // STARTTLS
        'auth' => true,
    ],
    
    // Identifiants Gmail
    'credentials' => [
        'email' => 'noreply@impactshop.tn', // ⚠️ À REMPLACER par ton email Gmail
        'password' => 'your-app-password-here', // ⚠️ À REMPLACER par le mot de passe d'application
    ],
    
    // Expéditeur
    'from' => [
        'email' => 'noreply@impactshop.tn',
        'name' => 'ImpactShop',
    ],
    
    // Options
    'options' => [
        'charset' => 'UTF-8',
        'timeout' => 30,
    ]
];
?>
