<?php
/**
 * ImpactShop - Routeur Principal (index.php corrigé)
 *
 * Correction principale :
 * - Le cas 'confirmation' pour controller=order inclut désormais la vue views/shop/order_confirmation.php
 *   en lui passant $orderDetails (array associatif) construit depuis la BDD (order + customer + items).
 *
 * Remarque :
 * - Assure-toi que le fichier de vue existe : views/shop/order_confirmation.php
 * - Si tu as placé order_confirmation.php ailleurs, adapte le chemin require ci-dessous.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/database.php';

$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

try {
    switch ($controller) {

        case 'product':
            require_once __DIR__ . '/controllers/ProductController.php';
            $productController = new ProductController();

            switch ($action) {
                case 'index':
                    $products = $productController->listProducts();
                    require __DIR__ . '/views/admin/product_list.php';
                    break;

                case 'shop':
                    $products = $productController->listProducts();
                    require __DIR__ . '/views/shop/product_catalog.php';
                    break;

                case 'create':
                    require __DIR__ . '/views/admin/product_form.php';
                    break;

                case 'store':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $data = [
                            'name_en' => trim($_POST['name_en'] ?? ''),
                            'name_fr' => trim($_POST['name_fr'] ?? ''),
                            'description_en' => trim($_POST['description_en'] ?? ''),
                            'description_fr' => trim($_POST['description_fr'] ?? ''),
                            'price' => floatval($_POST['price'] ?? 0),
                            'img_name' => trim($_POST['img_name'] ?? ''),
                            'stock' => intval($_POST['stock'] ?? 0)
                        ];
                        $productId = $productController->addProduct($data);
                        if ($productId === false) {
                            $_SESSION['error'] = "Erreur lors de la création du produit.";
                        } else {
                            $_SESSION['success'] = "Produit créé !";
                        }
                        header("Location: index.php?controller=product&action=index");
                        exit();
                    }
                    break;

                case 'edit':
                    $id = intval($_GET['id'] ?? 0);
                    if ($id) {
                        require_once __DIR__ . '/models/Product.php';
                        $product = Product::findById($id);
                        require __DIR__ . '/views/admin/product_form.php';
                    } else {
                        $_SESSION['error'] = "Produit introuvable.";
                        header("Location: index.php?controller=product&action=index");
                        exit();
                    }
                    break;

                case 'update':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $id = intval($_POST['id'] ?? 0);
                        $data = [
                            'name_en' => trim($_POST['name_en'] ?? ''),
                            'name_fr' => trim($_POST['name_fr'] ?? ''),
                            'description_en' => trim($_POST['description_en'] ?? ''),
                            'description_fr' => trim($_POST['description_fr'] ?? ''),
                            'price' => floatval($_POST['price'] ?? 0),
                            'img_name' => trim($_POST['img_name'] ?? ''),
                            'stock' => intval($_POST['stock'] ?? 0)
                        ];
                        $ok = $productController->updateProduct($data, $id);
                        if ($ok) $_SESSION['success'] = "Produit mis à jour !";
                        else $_SESSION['error'] = "Erreur lors de la mise à jour du produit.";
                        header("Location: index.php?controller=product&action=index");
                        exit();
                    }
                    break;

                case 'delete':
                    $id = intval($_GET['id'] ?? 0);
                    if ($id) {
                        $productController->deleteProduct($id);
                        $_SESSION['success'] = "Produit supprimé !";
                    }
                    header("Location: index.php?controller=product&action=index");
                    exit();
                    break;

                case 'view':
                    $id = intval($_GET['id'] ?? 0);
                    if ($id) {
                        $productController->showProductById($id);
                    } else {
                        echo "<p>Produit introuvable.</p>";
                    }
                    break;

                default:
                    throw new Exception("Action '$action' non trouvée pour controller product");
            }
            break;

        case 'order':
            require_once __DIR__ . '/controllers/OrderController.php';
            $orderController = new OrderController();

            switch ($action) {
                case 'index':
                    $orders = $orderController->listOrders();
                    require __DIR__ . '/views/admin/order_list.php';
                    break;

                case 'checkout':
                    require __DIR__ . '/views/shop/checkout_form.php';
                    break;

                case 'create':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $customerData = [
                            'first_name' => trim($_POST['first_name'] ?? ''),
                            'last_name'  => trim($_POST['last_name'] ?? ''),
                            'email'      => trim($_POST['email'] ?? ''),
                            'phone'      => trim($_POST['phone'] ?? '')
                        ];
                        $cartData = json_decode($_POST['cart_data'] ?? '[]', true);
                        $paymentMethod = trim($_POST['payment_method'] ?? 'paypal');

                        $orderId = $orderController->createOrderWithStock($customerData, $cartData, $paymentMethod);
                        if ($orderId === false) {
                            $err = method_exists($orderController, 'getLastError') ? $orderController->getLastError() : null;
                            if (!$err) $err = "Erreur lors de la création de la commande (stock ou données).";
                            $_SESSION['error'] = $err;
                            header("Location: index.php?controller=order&action=checkout");
                            exit();
                        } else {
                            $_SESSION['success'] = "Commande créée avec succès !";
                            header("Location: index.php?controller=order&action=confirmation&id=" . intval($orderId));
                            exit();
                        }
                    }
                    break;

                case 'confirmation':
                    // NEW: build $orderDetails assoc array and include the styled confirmation view
                    $orderId = intval($_GET['id'] ?? 0);
                    if ($orderId) {
                        $db = Database::getConnexion();
                        // Fetch order + customer
                        $stmt = $db->prepare("SELECT o.*, c.first_name, c.last_name, c.email, c.phone
                                              FROM orders o
                                              LEFT JOIN customers c ON o.customer_id = c.id
                                              WHERE o.id = :id
                                              LIMIT 1");
                        $stmt->execute(['id' => $orderId]);
                        $orderDetails = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($orderDetails) {
                            // Fetch items with product info
                            $stmtItems = $db->prepare("SELECT oi.product_id, oi.quantity, oi.unit_price, oi.subtotal, p.name_fr, p.img_name
                                                      FROM order_items oi
                                                      JOIN products p ON oi.product_id = p.id
                                                      WHERE oi.order_id = :order_id");
                            $stmtItems->execute(['order_id' => $orderId]);
                            $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
                            $orderDetails['items'] = $items ?: [];

                            // If the view is in views/shop/order_confirmation.php
                            if (file_exists(__DIR__ . '/views/shop/order_confirmation.php')) {
                                require __DIR__ . '/views/shop/order_confirmation.php';
                            } elseif (file_exists(__DIR__ . '/views/order_confirmation.php')) {
                                // fallback to views/order_confirmation.php if placed there
                                require __DIR__ . '/views/order_confirmation.php';
                            } else {
                                // If view missing, fallback to showing with controller helper
                                $orderController->showById($orderId);
                            }
                        } else {
                            echo "<p>Commande introuvable (id=" . htmlspecialchars($orderId) . ").</p>";
                        }
                    } else {
                        echo "<p>Commande introuvable (id invalide).</p>";
                    }
                    break;

                case 'view':
                    $orderId = intval($_GET['id'] ?? 0);
                    if ($orderId) {
                        $orderController->showById($orderId);
                    } else {
                        echo "<p>Commande introuvable.</p>";
                    }
                    break;

                case 'updateStatus':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $orderId = intval($_POST['order_id'] ?? 0);
                        $status = trim($_POST['status'] ?? 'pending');
                        $ok = $orderController->changeOrderStatus($orderId, $status, $_SESSION['user'] ?? null);
                        if ($ok) $_SESSION['success'] = "Statut mis à jour.";
                        else {
                            $err = method_exists($orderController, 'getLastError') ? $orderController->getLastError() : "Impossible de changer le statut.";
                            $_SESSION['error'] = $err;
                        }
                        header("Location: index.php?controller=order&action=view&id=" . $orderId);
                        exit();
                    }
                    break;

                case 'delete':
                    $id = intval($_GET['id'] ?? 0);
                    if ($id) {
                        $ok = $orderController->deleteOrder($id);
                        if ($ok) $_SESSION['success'] = "Commande supprimée.";
                        else $_SESSION['error'] = $orderController->getLastError() ?? "Erreur lors de la suppression.";
                    }
                    header("Location: index.php?controller=order&action=index");
                    exit();
                    break;

                default:
                    throw new Exception("Action '$action' non trouvée pour controller order");
            }
            break;

        case 'dashboard':
            require_once __DIR__ . '/controllers/OrderController.php';
            require_once __DIR__ . '/controllers/ProductController.php';
            $orderController = new OrderController();
            $productController = new ProductController();

            $orders = $orderController->listOrders();

            $db = Database::getConnexion();
            $sqlLow = "SELECT id, name_en, name_fr, stock FROM products WHERE stock <= :threshold ORDER BY stock ASC LIMIT 10";
            $stmtLow = $db->prepare($sqlLow);
            $stmtLow->execute(['threshold' => 5]);
            $lowStockProducts = $stmtLow->fetchAll(PDO::FETCH_ASSOC);

            require __DIR__ . '/views/admin/dashboard.php';
            break;

        case 'home':
        default:
            require __DIR__ . '/views/home.php';
            break;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo "<!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'><title>Erreur - ImpactShop</title></head><body style='font-family: Arial; padding: 50px;'>";
    echo "<h1>❌ Erreur</h1>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><a href='index.php'>← Retour à l'accueil</a></p>";
    echo "</body></html>";
}
?>