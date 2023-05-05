<?php
require_once 'globals.php';

class PDOConnector {
    private $pdo;

    public function __construct() {
        $host = constant('DB_HOST');
        $database = constant('DB_NAME');
        $username = constant('DB_USER');
        $password = constant('DB_PASS');

        $dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";

        try {
            $this->pdo = new PDO($dsn, $username, $password);
            echo 'É us guri papai!!!';
            
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }    
}

$pdo = new PDOConnector();