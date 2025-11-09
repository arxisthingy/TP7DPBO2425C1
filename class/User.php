<?php
require_once 'config/db.php'; // Include database configuration

class User { 
    private $db; // Database connection

    // Constructor to initialize database connection
    public function __construct() {
        $this->db = (new Database())->conn;
    }

    // Get all users
    public function getAllUsers() {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single user by their ID
    public function getUserById($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new user
    public function createUser($username, $password, $email) {
        $stmt = $this->db->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        return $stmt->execute([$username, password_hash($password, PASSWORD_BCRYPT), $email]);
    }

    // Update an existing user
    public function updateUser($user_id, $username, $email) {
        $stmt = $this->db->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
        return $stmt->execute([$username, $email, $user_id]);
    }

    // Delete a user
    public function deleteUser($user_id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = ?");
        return $stmt->execute([$user_id]);
    }
}
?>