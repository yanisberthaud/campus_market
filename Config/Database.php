<?php 

class Database {
    private static $instance = null;
    private $connection;

    // Database credentials
    private $host = 'localhost';
    private $db_name = 'campus_market';
    private $username = 'root';
    private $password = ''; // Par défaut sur Laragon/XAMPP, c'est vide

    // The constructor is private to prevent creating multiple instances
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this.db_name . ";charset=utf8",
                $this->username,
                $this->password
            );
            // Force PDO to throw exceptions on error
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("ConnectionError " : . $e->getMessage());
        }
    }

    // Get the database connection instance 
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->connection;
    }
}

?>