<?php
/**
 * Modèle Message - Contact / Support
 * Date: 2025-12-05
 * Author: dridi10331
 */

require_once __DIR__ . '/../config/database.php';

class Message {
    private $id;
    private $name;
    private $email;
    private $phone;
    private $subject;
    private $message;
    private $is_read;
    private $is_replied;
    private $reply_message;
    private $replied_at;
    private $created_at;

    public function __construct($data = []) {
        $this->id = $data['id'] ??  null;
        $this->name = $data['name'] ??  '';
        $this->email = $data['email'] ?? '';
        $this->phone = $data['phone'] ?? '';
        $this->subject = $data['subject'] ?? '';
        $this->message = $data['message'] ?? '';
        $this->is_read = $data['is_read'] ?? 0;
        $this->is_replied = $data['is_replied'] ?? 0;
        $this->reply_message = $data['reply_message'] ?? null;
        $this->replied_at = $data['replied_at'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
    }

    // ==================== CRUD ====================

    public static function findAll() {
        $db = Database::getConnexion();
        $sql = "SELECT * FROM messages ORDER BY created_at DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id) {
        $db = Database::getConnexion();
        $sql = "SELECT * FROM messages WHERE id = :id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Message($row) : null;
    }

    public static function findUnread() {
        $db = Database::getConnexion();
        $sql = "SELECT * FROM messages WHERE is_read = 0 ORDER BY created_at DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countUnread() {
        $db = Database::getConnexion();
        $sql = "SELECT COUNT(*) as count FROM messages WHERE is_read = 0";
        $stmt = $db->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($row['count']);
    }

    public static function create($data) {
        $db = Database::getConnexion();
        $sql = "INSERT INTO messages (name, email, phone, subject, message, is_read, created_at) 
                VALUES (:name, :email, :phone, :subject, :message, 0, NOW())";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? '',
            'subject' => $data['subject'],
            'message' => $data['message']
        ]);
        return $db->lastInsertId();
    }

    public function markAsRead() {
        $db = Database::getConnexion();
        $sql = "UPDATE messages SET is_read = 1 WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute(['id' => $this->id]);
    }

    public function reply($replyMessage) {
        $db = Database::getConnexion();
        $sql = "UPDATE messages SET is_replied = 1, reply_message = :reply, replied_at = NOW() WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute(['reply' => $replyMessage, 'id' => $this->id]);
    }

    public static function delete($id) {
        $db = Database::getConnexion();
        $sql = "DELETE FROM messages WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // ==================== Getters ====================
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    public function getPhone() { return $this->phone; }
    public function getSubject() { return $this->subject; }
    public function getMessage() { return $this->message; }
    public function isRead() { return $this->is_read; }
    public function isReplied() { return $this->is_replied; }
    public function getReplyMessage() { return $this->reply_message; }
    public function getRepliedAt() { return $this->replied_at; }
    public function getCreatedAt() { return $this->created_at; }
}
?>