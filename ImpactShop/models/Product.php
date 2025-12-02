<?php
// models/Product.php
// Modèle Product simple et compatible avec les controllers/index fournis.

include_once __DIR__ . '/../config/database.php';

class Product {
    private int $id;
    private string $name_en;
    private string $name_fr;
    private ?string $description_en;
    private ?string $description_fr;
    private float $price;
    private ?string $img_name;
    private int $stock;
    private ?string $created_at;
    private ?string $updated_at;

    public function __construct(
        int $id,
        string $name_en,
        string $name_fr,
        ?string $description_en,
        ?string $description_fr,
        float $price,
        ?string $img_name,
        int $stock = 0,
        ?string $created_at = null,
        ?string $updated_at = null
    ) {
        $this->id = $id;
        $this->name_en = $name_en;
        $this->name_fr = $name_fr;
        $this->description_en = $description_en;
        $this->description_fr = $description_fr;
        $this->price = $price;
        $this->img_name = $img_name;
        $this->stock = $stock;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Retourne un tableau associatif de tous les produits (compatible avec les vues existantes)
    public static function findAll(): array {
        $db = Database::getConnexion();
        $sql = "SELECT * FROM products ORDER BY id DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Charge un produit et retourne une instance Product ou null
    public static function findById(int $id): ?Product {
        $db = Database::getConnexion();
        $sql = "SELECT * FROM products WHERE id = :id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        return new Product(
            intval($row['id']),
            $row['name_en'] ?? '',
            $row['name_fr'] ?? '',
            $row['description_en'] ?? null,
            $row['description_fr'] ?? null,
            floatval($row['price'] ?? 0),
            $row['img_name'] ?? null,
            intval($row['stock'] ?? 0),
            $row['created_at'] ?? null,
            $row['updated_at'] ?? null
        );
    }

    // Affiche le produit en HTML (simple helper)
    public function show() {
        echo "<h3>Produit #".htmlspecialchars($this->id)."</h3>";
        echo "<table border='1' cellpadding='6' style='border-collapse:collapse; margin-bottom:10px;'>";
        echo "<tr><th>Champ</th><th>Valeur</th></tr>";
        echo "<tr><td>Nom (EN)</td><td>".htmlspecialchars($this->name_en)."</td></tr>";
        echo "<tr><td>Nom (FR)</td><td>".htmlspecialchars($this->name_fr)."</td></tr>";
        echo "<tr><td>Prix</td><td>".number_format($this->price,2)." €</td></tr>";
        echo "<tr><td>Stock</td><td>".intval($this->stock)."</td></tr>";
        echo "<tr><td>Image</td><td>".htmlspecialchars($this->img_name ?? '')."</td></tr>";
        echo "</table>";
    }

    // getters
    public function getId(): int { return $this->id; }
    public function getNameEn(): string { return $this->name_en; }
    public function getNameFr(): string { return $this->name_fr; }
    public function getPrice(): float { return $this->price; }
    public function getImgName(): ?string { return $this->img_name; }
    public function getStock(): int { return $this->stock; }
}
?>