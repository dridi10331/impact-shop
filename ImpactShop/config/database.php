<?php
/**
 * Configuration de la base de données
 * Date: 2025-11-25
 * Author: dridi10331
 */

class Database {
    private static $host = "localhost";
    private static $db_name = "impactshop_db";
    private static $username = "root";
    private static $password = "";
    private static $conn = null;

    /**
     * Obtenir la connexion PDO (méthode STATIQUE)
     */
    public static function getConnexion() {
        if (self::$conn === null) {
            try {
                self::$conn = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$db_name,
                    self::$username,
                    self::$password,
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                    )
                );
            } catch(PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }
        }
        
        return self::$conn;
    }
}
?>