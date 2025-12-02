<?php
// models/Order.php
include_once __DIR__ . '/../config/database.php';

class Order {
    private int $id;
    private int $customer_id;
    private float $total_amount;
    private string $status;
    private string $payment_method;
    private ?string $created_at;
    private ?string $updated_at;

    // client info
    private ?string $customer_first_name = null;
    private ?string $customer_last_name = null;
    private ?string $customer_email = null;
    private ?string $customer_phone = null;

    // items
    private array $items = [];

    public function __construct(
        int $id,
        int $customer_id,
        float $total_amount,
        string $status,
        string $payment_method,
        ?string $created_at = null,
        ?string $updated_at = null
    ) {
        $this->id = $id;
        $this->customer_id = $customer_id;
        $this->total_amount = $total_amount;
        $this->status = $status;
        $this->payment_method = $payment_method;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public static function findById(int $id): ?Order {
        $db = Database::getConnexion();

        $sqlOrder = "SELECT 
                        o.*,
                        c.first_name,
                        c.last_name,
                        c.email,
                        c.phone
                     FROM orders o
                     LEFT JOIN customers c ON o.customer_id = c.id
                     WHERE o.id = :id
                     LIMIT 1";
        $stmt = $db->prepare($sqlOrder);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        $order = new Order(
            intval($row['id']),
            intval($row['customer_id']),
            floatval($row['total_amount']),
            $row['status'],
            $row['payment_method'],
            $row['created_at'] ?? null,
            $row['updated_at'] ?? null
        );

        $order->customer_first_name = $row['first_name'] ?? null;
        $order->customer_last_name  = $row['last_name'] ?? null;
        $order->customer_email      = $row['email'] ?? null;
        $order->customer_phone      = $row['phone'] ?? null;

        $sqlItems = "SELECT oi.product_id, oi.quantity, oi.unit_price, oi.subtotal, p.name_fr, p.img_name
                     FROM order_items oi
                     JOIN products p ON oi.product_id = p.id
                     WHERE oi.order_id = :order_id";
        $stmtItems = $db->prepare($sqlItems);
        $stmtItems->execute(['order_id' => $id]);
        $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

        foreach ($items as $it) {
            $order->items[] = [
                'product_id' => intval($it['product_id']),
                'name_fr'    => $it['name_fr'],
                'img_name'   => $it['img_name'],
                'quantity'   => intval($it['quantity']),
                'unit_price' => floatval($it['unit_price']),
                'subtotal'   => floatval($it['subtotal'])
            ];
        }

        return $order;
    }

    public function show() {
        echo "<h3>Commande #".htmlspecialchars($this->id)."</h3>";
        echo "<table border='1' cellpadding='6' style='border-collapse:collapse; margin-bottom:10px;'>";
        echo "<tr><th>Champ</th><th>Valeur</th></tr>";
        echo "<tr><td>Client</td><td>".htmlspecialchars(($this->customer_first_name ?? '') . ' ' . ($this->customer_last_name ?? ''))."</td></tr>";
        echo "<tr><td>Email</td><td>".htmlspecialchars($this->customer_email ?? '')."</td></tr>";
        echo "<tr><td>Téléphone</td><td>".htmlspecialchars($this->customer_phone ?? '')."</td></tr>";
        echo "<tr><td>Montant total</td><td>".number_format($this->total_amount, 2)." €</td></tr>";
        echo "<tr><td>Statut</td><td>".htmlspecialchars($this->status)."</td></tr>";
        echo "<tr><td>Méthode paiement</td><td>".htmlspecialchars($this->payment_method)."</td></tr>";
        echo "<tr><td>Créée le</td><td>".htmlspecialchars($this->created_at ?? '')."</td></tr>";
        echo "</table>";

        echo "<h4>Articles</h4>";
        if (empty($this->items)) {
            echo "<p>Aucun article.</p>";
            return;
        }

        echo "<table border='1' cellpadding='6' style='border-collapse:collapse; width:100%;'>";
        echo "<tr>
                <th>Produit</th>
                <th>Image</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Sous-total</th>
              </tr>";
        foreach ($this->items as $it) {
            $imgHtml = '';
            if (!empty($it['img_name'])) {
                $imgPath = 'uploads/' . htmlspecialchars($it['img_name']);
                $imgHtml = "<img src='". $imgPath ."' alt='' style='height:40px;'/>";
            }
            echo "<tr>";
            echo "<td>".htmlspecialchars($it['name_fr'] ?? 'Produit')."</td>";
            echo "<td style='text-align:center;'>".$imgHtml."</td>";
            echo "<td style='text-align:center;'>".intval($it['quantity'])."</td>";
            echo "<td style='text-align:right;'>".number_format($it['unit_price'],2)." €</td>";
            echo "<td style='text-align:right;'>".number_format($it['subtotal'],2)." €</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    // getters
    public function getId(): int { return $this->id; }
    public function getCustomerId(): int { return $this->customer_id; }
    public function getItems(): array { return $this->items; }
    public function getStatus(): string { return $this->status; }
}
?>