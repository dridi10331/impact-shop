<?php
/**
 * Contrôleur Home - Page d'accueil
 */

class HomeController {
    
    public function index() {
        // Afficher la page de sélection du rôle
        include __DIR__ . '/../views/home.php';
    }
}
?>