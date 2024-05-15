<?php
class Database {
private $host = "localhost";
private $database_name = "jpo_iut_meaux_mmi";
private $username = "root";
private $password = "";
public $conn;

// Get the database connection
public function getConnection() {
    $this->conn = null; // Reset the connection

    // Try to connect to the database and catch any errors
    try {
        $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
        $this->conn->exec("set names utf8");
    } catch(PDOException $exception) {
        echo "Erreur de connexion à la base de données: " . $exception->getMessage(); // Display the error message
    }

    return $this->conn; // Return the connection
    }
}