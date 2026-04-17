<?php 

class Database {
    private static $instance = null;
    private $connection;

    private $host = 'sql100.infinityfree.com';
    private $db_name = 'campus_market';
    private $username = 'root';
    private $password = ''; 

    private function __construct() {
        try {
            // Utilisation bien propre de la flèche -> ici
            $this->connection = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("ConnectionError : "  . $e->getMessage());
        }
    }

    // Cette méthode renvoie l'INSTANCE de la classe (la boîte)
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Cette méthode renvoie la CONNEXION (la clé dans la boîte)
    public function getConnection() {
        return $this->connection;
    }
}