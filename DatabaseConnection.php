<?php


class DatabaseConnection {
    private $dbHost;
    private $dbName;
    private $dbUser;
    private $dbPass;
    private $pdo;
 

    public function __construct() {
        $this->dbHost = "localhost";
        $this->dbName = "challenge_geopagos";
        $this->dbUser = "root";
        $this->dbPass = "";
    }

    public function connect() {
        try {
            $this->pdo = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName", $this->dbUser, $this->dbPass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (PDOException $e) {
            echo "Error al conectar a la base de datos: " . $e->getMessage() . "\n";
            return null;
        }
    }

    public function disconnect() {
        if ($this->pdo != null) {
            $this->pdo = null;
        }
    }

    public function __destruct() {
        $this->disconnect();
    }
}