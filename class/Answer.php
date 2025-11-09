<?php
require_once 'config/db.php'; // Include database configuration

class Answer {
    private $db; // Database connection

    // Constructor to initialize database connection
    public function __construct() {
        $this->db = (new Database())->conn;
    }

    // Get all answers for a specific question
    public function getAnswersByQuestionId($question_id) {
        $stmt = $this->db->prepare("
            SELECT a.*, u.username 
            FROM answers a
            JOIN users u ON a.author_id = u.user_id
            WHERE a.question_id = ? 
            ORDER BY a.is_accepted DESC, a.created_at ASC
        ");
        $stmt->execute([$question_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create a new answer
    public function createAnswer($body, $question_id, $author_id) {
        $stmt = $this->db->prepare("INSERT INTO answers (body, question_id, author_id) VALUES (?, ?, ?)");
        return $stmt->execute([$body, $question_id, $author_id]);
    }
    
    // Update an existing answer
    public function updateAnswer($answer_id, $body) {
        $stmt = $this->db->prepare("UPDATE answers SET body = ? WHERE answer_id = ?");
        return $stmt->execute([$body, $answer_id]);
    }

    // Delete an answer
    public function deleteAnswer($answer_id) {
        $stmt = $this->db->prepare("DELETE FROM answers WHERE answer_id = ?");
        return $stmt->execute([$answer_id]);
    }

    // Get a single answer by its ID
    public function getAnswerById($answer_id) {
        $stmt = $this->db->prepare("
            SELECT a.*, u.username 
            FROM answers a
            JOIN users u ON a.author_id = u.user_id
            WHERE a.answer_id = ?
        ");
        $stmt->execute([$answer_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Accept an answer as the best answer for a question
    public function acceptAnswer($answer_id, $question_id) {
        try {
            $this->db->beginTransaction();
            
            // Reset all answers for the question to not accepted
            $stmt_reset = $this->db->prepare(
                "UPDATE answers SET is_accepted = 0 WHERE question_id = ?"
            );
            $stmt_reset->execute([$question_id]);
            // Set the selected answer as accepted
            $stmt_accept = $this->db->prepare(
                "UPDATE answers SET is_accepted = 1 WHERE answer_id = ?"
            );
            $stmt_accept->execute([$answer_id]);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
?>