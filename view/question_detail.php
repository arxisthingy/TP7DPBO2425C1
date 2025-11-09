<?php

// Ensure a valid question ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID Pertanyaan tidak valid.</div>";
    return; 
}

// Get the question ID from the URL
$question_id = $_GET['id'];
$pesan = '';
$answer_to_edit = null;

// Handle form submissions for creating, updating, deleting, and accepting answers
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action_post = $_POST['action'] ?? '';
    
    try {
        if ($action_post == 'create_answer') {
            $answer->createAnswer($_POST['body'], $question_id, $_POST['author_id']);
            $pesan = "Jawaban berhasil ditambahkan!";
        
        } else if ($action_post == 'update_answer') {
            $answer->updateAnswer($_POST['answer_id'], $_POST['body']);
            header("Location: index.php?page=question_detail&id=$question_id&pesan=Jawaban berhasil diperbarui!");
            exit;
        }
    } catch (Exception $e) {
        $pesan = "Error: " . $e->getMessage();
    }
}

// Handle actions from GET requests
$action_get = $_GET['action'] ?? '';

try {
    if ($action_get == 'delete_answer' && isset($_GET['answer_id'])) {
        $answer->deleteAnswer($_GET['answer_id']);
        header("Location: index.php?page=question_detail&id=$question_id&pesan=Jawaban dihapus!");
        exit;
    }

    if ($action_get == 'edit_answer' && isset($_GET['answer_id'])) {
        $answer_to_edit = $answer->getAnswerById($_GET['answer_id']);
    }
    
    if ($action_get == 'accept_answer' && isset($_GET['answer_id'])) {
        $answer->acceptAnswer($_GET['answer_id'], $question_id);
        header("Location: index.php?page=question_detail&id=$question_id&pesan=Jawaban terbaik telah dipilih!");
        exit;
    }

} catch (Exception $e) { // Catch any exceptions during actions
    $pesan = "Error: " . $e->getMessage();
}

// Fetch the current question and its answers
$current_question = $question->getQuestionById($question_id);
$answers = $answer->getAnswersByQuestionId($question_id);
$users = $user->getAllUsers();

// If the question does not exist, show an error
if (!$current_question) {
    echo "<div class='alert alert-danger'>Pertanyaan dengan ID $question_id tidak ditemukan.</div>";
    return;
}
?>

<?php if (isset($_GET['pesan'])): ?>
    <div class="alert alert-info"><?php echo htmlspecialchars($_GET['pesan']); ?></div>
<?php endif; ?>
<?php if ($pesan): ?>
    <div class="alert alert-info"><?php echo $pesan; ?></div>
<?php endif; ?>

<div class="card bg-light">
    <div class="card-header">
        <small>Ditanyakan oleh: <?php echo htmlspecialchars($current_question['username']); ?> pada <?php echo date('d M Y', strtotime($current_question['created_at'])); ?></small>
    </div>
    <div class...="card-body">
        <h2 class="card-title"><?php echo htmlspecialchars($current_question['title']); ?></h2>
        <p class="card-text fs-5"><?php echo nl2br(htmlspecialchars($current_question['body'])); ?></p>
    </div>
</div>

<hr>

<h3><?php echo count($answers); ?> Jawaban</h3>
<?php foreach ($answers as $a): ?>

    <?php
    $card_style = $a['is_accepted'] ? 'border-success border-2' : '';
    $card_header = $a['is_accepted'] ? '<div class="card-header bg-success text-white">Jawaban Terbaik</div>' : '';
    ?>

    <div class="card mb-3 <?php echo $card_style; ?>">
        <?php echo $card_header; ?>
        
        <?php ?>
        <?php ?>
        <?php if ($answer_to_edit && $answer_to_edit['answer_id'] == $a['answer_id']): ?>
            
            <div class="card-body">
                <form action="index.php?page=question_detail&id=<?php echo $question_id; ?>" method="POST">
                    <input type="hidden" name="action" value="update_answer">
                    <input type="hidden" name="answer_id" value="<?php echo $a['answer_id']; ?>">
                    <div class="mb-3">
                        <label for="body_edit_<?php echo $a['answer_id']; ?>" class="form-label">Edit Jawaban Anda:</label>
                        <textarea class="form-control" 
                                  id="body_edit_<?php echo $a['answer_id']; ?>" 
                                  name="body" 
                                  rows="4" 
                                  required><?php echo htmlspecialchars($a['body']); ?></textarea>
                    </div>
                    <button type"submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                    <a href="index.php?page=question_detail&id=<?php echo $question_id; ?>" class="btn btn-secondary btn-sm">Batal</a>
                </form>
            </div>

        <?php?>
        <?php else: ?>

            <div class="card-body">
                <p><?php echo nl2br(htmlspecialchars($a['body'])); ?></p>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Dijawab oleh: <?php echo htmlspecialchars($a['username']); ?>
                </small>
                
                <div>
                    <?php?>
                    <?php if (!$a['is_accepted']): ?>
                        <a href="index.php?page=question_detail&id=<?php echo $question_id; ?>&action=accept_answer&answer_id=<?php echo $a['answer_id']; ?>" 
                           class="btn btn-sm btn-success">
                           Jadikan Jawaban Terbaik
                        </a>
                    <?php endif; ?>

                    <a href="index.php?page=question_detail&id=<?php echo $question_id; ?>&action=edit_answer&answer_id=<?php echo $a['answer_id']; ?>" 
                       class="btn btn-sm btn-warning">
                       Edit
                    </a>
                    
                    <a href="index.php?page=question_detail&id=<?php echo $question_id; ?>&action=delete_answer&answer_id=<?php echo $a['answer_id']; ?>" 
                       class="btn btn-sm btn-outline-danger" 
                       onclick="return confirm('Yakin ingin menghapus jawaban ini?');">
                       Hapus
                    </a>
                </div>
            </div>

        <?php endif; ?>
        <?php?>
        
    </div>
<?php endforeach; ?>

<div class="card mt-4">
    <div class="card-header">Beri Jawaban Anda</div>
    <div class="card-body">
        <form action="index.php?page=question_detail&id=<?php echo $question_id; ?>" method="POST">
            <input type="hidden" name="action" value="create_answer">
            <div class="mb-3">
                <label for="body" class="form-label">Jawaban Anda</LabeL>
                <textarea class="form-control" id="body" name="body" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="author_id" class="form-label">Jawab Sebagai</label>
                <select class="form-select" id="author_id" name="author_id" required>
                    <option value="">-- Pilih User --</option>
                    <?php foreach ($users as $u): ?>
                        <option value="<?php echo $u['user_id']; ?>"><?php echo htmlspecialchars($u['username']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Kirim Jawaban</button>
        </form>
    </div>
</div>