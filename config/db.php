<?php
class Database {
    private $host = "localhost"; // DB host
    private $username = "root"; // DB username
    private $password = ""; // DB password
    private $dbname = "forum_db"; // DB name
    public $conn; // Connection property

    // Constructor to establish database connection
    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password); // Create PDO instance
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
        } catch (PDOException $e) { // Catch connection errors
            echo "Connection failed: " . $e->getMessage(); // Display error message
        }
    }
}
?>