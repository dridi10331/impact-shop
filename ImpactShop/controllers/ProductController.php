<?php
// controllers/ProductController.php
require_once __DIR__ . "/../models/Product.php";
require_once __DIR__ . "/../config/database.php";

class ProductController {
    public function listProducts(): array {
        try {
            return Product::findAll();
        } catch (Exception $e) {
            // En cas d'erreur renvoyer tableau vide et logger
            error_log('ProductController::listProducts error: ' . $e->getMessage());
            return [];
        }
    }

    public function showProductById(int $id) {
        $product = Product::findById($id);
        if ($product) {
            echo "<h2>Informations du produit :</h2>";
            $product->show();
        } else {
            echo "<p>Produit introuvable (id=".htmlspecialchars($id).").</p>";
        }
    }

    public function addProduct(array $data) {
        $db = Database::getConnexion();
        try {
            $sql = "INSERT INTO products (name_en, name_fr, description_en, description_fr, price, img_name, stock, created_at)
                    VALUES (:name_en, :name_fr, :description_en, :description_fr, :price, :img_name, :stock, NOW())";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'name_en' => $data['name_en'] ?? '',
                'name_fr' => $data['name_fr'] ?? '',
                'description_en' => $data['description_en'] ?? null,
                'description_fr' => $data['description_fr'] ?? null,
                'price' => floatval($data['price'] ?? 0),
                'img_name' => $data['img_name'] ?? null,
                'stock' => intval($data['stock'] ?? 0)
            ]);
            return $db->lastInsertId();
        } catch (Exception $e) {
            error_log('ProductController::addProduct error: ' . $e->getMessage());
            return false;
        }
    }

    public function updateProduct(array $data, int $id): bool {
        $db = Database::getConnexion();
        try {
            $sql = "UPDATE products SET 
                        name_en = :name_en,
                        name_fr = :name_fr,
                        description_en = :description_en,
                        description_fr = :description_fr,
                        price = :price,
                        img_name = :img_name,
                        stock = :stock,
                        updated_at = NOW()
                    WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                'id' => $id,
                'name_en' => $data['name_en'] ?? '',
                'name_fr' => $data['name_fr'] ?? '',
                'description_en' => $data['description_en'] ?? null,
                'description_fr' => $data['description_fr'] ?? null,
                'price' => floatval($data['price'] ?? 0),
                'img_name' => $data['img_name'] ?? null,
                'stock' => intval($data['stock'] ?? 0)
            ]);
            return true;
        } catch (Exception $e) {
            error_log('ProductController::updateProduct error: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteProduct($id): bool {
        $db = Database::getConnexion();
        try {
            $sql = "DELETE FROM products WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return true;
        } catch (Exception $e) {
            error_log('ProductController::deleteProduct error: ' . $e->getMessage());
            return false;
        }
    }

    // helper to set stock (admin)
    public function setStock(int $id, int $newStock): bool {
        $db = Database::getConnexion();
        try {
            $sql = "UPDATE products SET stock = :stock, updated_at = NOW() WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute(['stock' => $newStock, 'id' => $id]);
            return true;
        } catch (Exception $e) {
            error_log('ProductController::setStock error: ' . $e->getMessage());
            return false;
        }
    }
}
?>