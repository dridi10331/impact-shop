<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "database.php";

/**
 * DonationController - Gestion des donations
 */
class DonationController {
    private ?string $lastError = null;

    public function getLastError(): ?string {
        return $this->lastError;
    }

    /**
     * Enregistrer une donation
     */
    public function recordDonation(array $donationData): bool {
        $db = Database::getConnexion();
        
        try {
            // Valider les données
            if (empty($donationData['email']) || !filter_var($donationData['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email invalide.');
            }
            if (empty($donationData['amount']) || floatval($donationData['amount']) <= 0) {
                throw new Exception('Montant invalide.');
            }
            if (empty($donationData['payment_method'])) {
                throw new Exception('Méthode de paiement requise.');
            }

            // Insérer la donation
            $sql = "INSERT INTO donations (
                first_name, last_name, email, phone, amount, 
                payment_method, status, newsletter, created_at
            ) VALUES (
                :first_name, :last_name, :email, :phone, :amount,
                :payment_method, :status, :newsletter, NOW()
            )";

            $stmt = $db->prepare($sql);
            $result = $stmt->execute([
                'first_name' => $donationData['first_name'] ?? '',
                'last_name' => $donationData['last_name'] ?? '',
                'email' => $donationData['email'],
                'phone' => $donationData['phone'] ?? '',
                'amount' => floatval($donationData['amount']),
                'payment_method' => $donationData['payment_method'],
                'status' => 'pending',
                'newsletter' => $donationData['newsletter'] ? 1 : 0
            ]);

            if ($result) {
                error_log('✅ Donation recorded: ' . $donationData['email'] . ' - ' . $donationData['amount'] . ' TND');
                return true;
            }

            throw new Exception('Erreur lors de l\'enregistrement de la donation.');
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('❌ DonationController::recordDonation error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer toutes les donations
     */
    public function listDonations(): array {
        $db = Database::getConnexion();
        
        try {
            $sql = "SELECT * FROM donations ORDER BY created_at DESC";
            $stmt = $db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('DonationController::listDonations error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer les donations par statut
     */
    public function getDonationsByStatus(string $status): array {
        $db = Database::getConnexion();
        
        try {
            $sql = "SELECT * FROM donations WHERE status = :status ORDER BY created_at DESC";
            $stmt = $db->prepare($sql);
            $stmt->execute(['status' => $status]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('DonationController::getDonationsByStatus error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Mettre à jour le statut d'une donation
     */
    public function updateDonationStatus(int $donationId, string $status): bool {
        $db = Database::getConnexion();
        
        try {
            $sql = "UPDATE donations SET status = :status, updated_at = NOW() WHERE id = :id";
            $stmt = $db->prepare($sql);
            $result = $stmt->execute(['status' => $status, 'id' => $donationId]);
            
            if ($result) {
                error_log('✅ Donation status updated: ID ' . $donationId . ' -> ' . $status);
            }
            
            return $result;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('DonationController::updateDonationStatus error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir les statistiques de donations
     */
    public function getDonationStats(): array {
        $db = Database::getConnexion();
        
        try {
            $stats = [];

            // Total donations
            $stmt = $db->query("SELECT COUNT(*) as count, SUM(amount) as total FROM donations WHERE status = 'completed'");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['total_donations'] = $result['count'] ?? 0;
            $stats['total_amount'] = floatval($result['total'] ?? 0);

            // Donations by payment method
            $stmt = $db->query("SELECT payment_method, COUNT(*) as count, SUM(amount) as total FROM donations WHERE status = 'completed' GROUP BY payment_method");
            $stats['by_payment_method'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Recent donations
            $stmt = $db->query("SELECT * FROM donations WHERE status = 'completed' ORDER BY created_at DESC LIMIT 10");
            $stats['recent'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Average donation
            $stats['average_donation'] = $stats['total_donations'] > 0 
                ? $stats['total_amount'] / $stats['total_donations'] 
                : 0;

            return $stats;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('DonationController::getDonationStats error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Supprimer une donation
     */
    public function deleteDonation(int $donationId): bool {
        $db = Database::getConnexion();
        
        try {
            $sql = "DELETE FROM donations WHERE id = :id";
            $stmt = $db->prepare($sql);
            $result = $stmt->execute(['id' => $donationId]);
            
            if ($result) {
                error_log('✅ Donation deleted: ID ' . $donationId);
            }
            
            return $result;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('DonationController::deleteDonation error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer un email de remerciement
     */
    public function sendThankYouEmail(array $donationData): bool {
        try {
            if (!file_exists(__DIR__ . '/../services/EmailService.php')) {
                return false;
            }

            require_once __DIR__ . '/../services/EmailService.php';

            $email = $donationData['email'];
            $name = ($donationData['first_name'] ?? '') . ' ' . ($donationData['last_name'] ?? '');
            $amount = $donationData['amount'];

            $subject = "ImpactShop - Merci pour votre donation!";
            $html = $this->getThankYouEmailTemplate($name, $amount);

            return EmailService::sendEmail($email, $subject, $html);
        } catch (Exception $e) {
            error_log('DonationController::sendThankYouEmail error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Template email de remerciement
     */
    private function getThankYouEmailTemplate(string $name, float $amount): string {
        return "
        <!DOCTYPE html>
        <html>
        <head><meta charset='UTF-8'></head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: linear-gradient(135deg, #1e3149, #15202e); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;'>
                <h1 style='color: white; margin: 0;'><span style='color: #ffb600;'>Impact</span>Shop</h1>
                <p style='color: rgba(255,255,255,0.8); margin: 10px 0 0 0;'>Merci pour votre générosité!</p>
            </div>
            
            <div style='background: white; padding: 30px; border: 1px solid #eee;'>
                <h2 style='color: #1e3149; margin-top: 0;'>❤️ Merci $name!</h2>
                
                <p>Nous vous remercions sincèrement pour votre donation de <strong>$amount TND</strong>.</p>
                
                <p>Votre générosité nous aide à:</p>
                <ul style='color: #666; line-height: 1.8;'>
                    <li>Soutenir les enfants en difficulté</li>
                    <li>Fournir des bourses d'études</li>
                    <li>Offrir des soins médicaux</li>
                    <li>Créer des opportunités d'emploi</li>
                </ul>
                
                <div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 25px 0;'>
                    <p style='margin: 0; color: #1e3149; font-weight: bold;'>Votre impact:</p>
                    <p style='margin: 10px 0 0 0; color: #666;'>Grâce à vous, nous pouvons continuer notre mission d'aide humanitaire.</p>
                </div>
                
                <p>Si vous avez des questions, n'hésitez pas à nous contacter à contact@impactshop.tn</p>
                
                <p style='margin-top: 30px; color: #666;'>Cordialement,<br><strong>L'équipe ImpactShop</strong></p>
            </div>
            
            <div style='background: #1e3149; padding: 20px; text-align: center; border-radius: 0 0 10px 10px;'>
                <p style='color: rgba(255,255,255,0.8); margin: 0;'>❤️ Ensemble, nous changeons des vies - ImpactShop 2025</p>
            </div>
        </body>
        </html>";
    }
}
?>
