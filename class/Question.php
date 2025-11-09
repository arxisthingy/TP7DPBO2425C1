<?php
require_once 'config/db.php'; // Include database configuration

class Question {
    private $db; // Database connection

    // Constructor to initialize database connection
    public function __construct() {
        $this->db = (new Database())->conn;
    }

    // Get all questions with author usernames
    public function getAllQuestions() {
        $stmt = $this->db->query("
            SELECT q.*, u.username 
            FROM questions q
            JOIN users u ON q.author_id = u.user_id
            ORDER BY q.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single question by its ID
    public function getQuestionById($question_id) {
        $stmt = $this->db->prepare("
            SELECT q.*, u.username 
            FROM questions q
            JOIN users u ON q.author_id = u.user_id
            WHERE q.question_id = ?
        ");
        $stmt->execute([$question_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new question
    public function createQuestion($title, $body, $author_id) {
        $stmt = $this->db->prepare("INSERT INTO questions (title, body, author_id) VALUES (?, ?, ?)");
        return $stmt->execute([$title, $body, $author_id]);
    }

    // Update an existing question
    public function updateQuestion($question_id, $title, $body) {
        $stmt = $this->db->prepare("UPDATE questions SET title = ?, body = ? WHERE question_id = ?");
        return $stmt->execute([$title, $body, $question_id]);
    }

    // Delete a question
    public function deleteQuestion($question_id) {
        $stmt = $this->db->prepare("DELETE FROM questions WHERE question_id = ?");
        return $stmt->execute([$question_id]);
    }
}
?>