<?php

// Main index file to route requests and display views

//require all views
require_once 'class/User.php';
require_once 'class/Question.php';
require_once 'class/Answer.php';

// Initialize classes
$user = new User();
$question = new Question();
$answer = new Answer();

// Include header
include 'view/header.php';

// Determine which page to display
$page = $_GET['page'] ?? 'home';
// Start main content
echo '<main>';

// Route to the appropriate page
switch ($page) {
    // Include user management page
    case 'users':
        include 'view/users.php';
        break;
    // Include question management page
    case 'questions':
        include 'view/questions.php';
        break;
    // Include question detail page
    case 'question_detail':
        include 'view/question_detail.php';
        break;
    // Default to home page
    case 'home':
    default:

        echo '<div class="card text-center">';
        echo '  <div class="card-body">';
        echo '    <h1 class="card-title">Selamat Datang di Forum Diskusi</h1>';
        echo '    <p class="card-text">Gunakan navigasi di atas untuk mengelola User atau melihat Pertanyaan.</p>';
        echo '    <a href="index.php?page=questions" class="btn btn-primary">Lihat Pertanyaan</a> ';
        echo '    <a href="index.php?page=users" class="btn btn-secondary">Kelola Pengguna</a>';
        echo '  </div>';
        echo '</div>';
        break;
}
// End main content
echo '</main>';

// Include footer
include 'view/footer.php';
?>