<?php
/**
 * Contrôleur Dashboard - Statistiques et tableaux de bord
 * Date: 2025-11-25
 * Author: dridi10331
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Product.php';

class DashboardController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnexion();
    }
    
    /**
     * Afficher le dashboard principal
     */
    public function index() {
        // Récupérer toutes les commandes pour statistiques (returns array)
        $orders = Order::findAll();
        
        // Calculer les statistiques manuellement
        $stats = $this->calculateStatistics($orders);
        
        // Récupérer les 5 commandes récentes
        $recentOrders = array_slice($orders, 0, 5);
        
        // Récupérer les 5 produits les plus vendus
        $topProducts = $this->getTopProducts(5);
        
        // Récupérer les statistiques mensuelles
        $monthlyStats = $this->getMonthlyStats(12);
        
        // Afficher la vue
        require __DIR__ . '/../views/admin/dashboard.php';
    }
    
    /**
     * Calculer les statistiques
     */
    private function calculateStatistics($orders) {
        $stats = [
            'total_revenue' => 0,
            'monthly_revenue' => 0,
            'total_orders' => count($orders),
            'pending_orders' => 0,
            'total_customers' => 0,
            'average_order' => 0
        ];
        
        $currentMonth = date('Y-m');
        
        foreach ($orders as $order) {
            $status = $order['status'] ?? '';
            $totalAmount = floatval($order['total_amount'] ?? 0);
            $createdAt = $order['created_at'] ?? '';
            
            // Revenu total (seulement paid)
            if ($status === 'paid') {
                $stats['total_revenue'] += $totalAmount;
            }
            
            // Revenu du mois
            if ($status === 'paid' && strpos($createdAt, $currentMonth) === 0) {
                $stats['monthly_revenue'] += $totalAmount;
            }
            
            // Commandes en attente
            if ($status === 'pending') {
                $stats['pending_orders']++;
            }
        }
        
        // Total clients (requête séparée)
        try {
            $query = "SELECT COUNT(*) as total FROM customers";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['total_customers'] = $result['total'] ?? 0;
        } catch (Exception $e) {
            $stats['total_customers'] = 0;
        }
        
        // Panier moyen
        if ($stats['total_orders'] > 0) {
            $stats['average_order'] = $stats['total_revenue'] / $stats['total_orders'];
        }
        
        return $stats;
    }
    
    /**
     * Récupérer les produits les plus vendus
     */
    private function getTopProducts($limit = 5) {
        $query = "SELECT 
                    p.id,
                    p.name_fr,
                    p.name_en,
                    p.price,
                    p.img_name,
                    COALESCE(SUM(oi.quantity), 0) as total_sold,
                    COALESCE(SUM(oi.subtotal), 0) as total_revenue
                  FROM products p
                  LEFT JOIN order_items oi ON p.id = oi.product_id
                  GROUP BY p.id
                  ORDER BY total_sold DESC
                  LIMIT :limit";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupérer les statistiques mensuelles
     */
    private function getMonthlyStats($months = 12) {
        $query = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COALESCE(SUM(total_amount), 0) as revenue,
                    COUNT(*) as order_count
                  FROM orders
                  WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
                  AND status = 'paid'
                  GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                  ORDER BY month DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':months', $months, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>