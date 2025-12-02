<?php
case 'create':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer et valider sommairement
        $customerData = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? '')
        ];
        // Attention : cart_data doit être un JSON encodé côté front
        $cartData = json_decode($_POST['cart_data'] ?? '[]', true);
        $paymentMethod = trim($_POST['payment_method'] ?? 'paypal');

        try {
            $orderId = $orderController->createOrderWithStock($customerData, $cartData, $paymentMethod);
            $_SESSION['success'] = "Commande créée avec succès !";
            header("Location: index.php?controller=order&action=confirmation&id=" . $orderId);
            exit();
        } catch (Exception $e) {
            // Stocke le message d'erreur précis pour affichage dans checkout
            $_SESSION['error'] = "Erreur lors de la création de la commande : " . $e->getMessage();
            header("Location: index.php?controller=order&action=checkout");
            exit();
        }
    }
    break;
?>