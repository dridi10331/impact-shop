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
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    /**
     * Afficher le dashboard principal
     */
    public function index() {
        // Récupérer toutes les commandes pour statistiques
        $orders = Order::all($this->db);
        
        // Calculer les statistiques manuellement
        $stats = $this->calculateStatistics($orders);
        
        // Récupérer les 5 commandes récentes
        $allOrders = Order::all($this->db);
        $recentOrders = [];
        
        $count = 0;
        foreach ($allOrders as $order) {
            if ($count >= 5) break;
            
            $customer = $order->getCustomer();
            $recentOrders[] = [
                'id' => $order->getId(),
                'total_amount' => $order->getTotalAmount(),
                'status' => $order->getStatus(),
                'created_at' => $order->getCreatedAt(),
                'customer_name' => ($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? '')
            ];
            
            $count++;
        }
        
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
            // Revenu total (seulement paid)
            if ($order->getStatus() === 'paid') {
                $stats['total_revenue'] += $order->getTotalAmount();
            }
            
            // Revenu du mois
            if ($order->getStatus() === 'paid' && strpos($order->getCreatedAt(), $currentMonth) === 0) {
                $stats['monthly_revenue'] += $order->getTotalAmount();
            }
            
            // Commandes en attente
            if ($order->getStatus() === 'pending') {
                $stats['pending_orders']++;
            }
        }
        
        // Total clients (requête séparée)
        $query = "SELECT COUNT(*) as total FROM customers";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stats['total_customers'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
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