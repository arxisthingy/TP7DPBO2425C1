<?php

// Qyuestion Management Page
$pesan = '';
$question_to_edit = null;

// Handle form submissions for creating and updating questions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action == 'create') {
            $question->createQuestion($_POST['title'], $_POST['body'], $_POST['author_id']);
            $pesan = "Pertanyaan berhasil diposting!";
        } else if ($action == 'update') {
            $question->updateQuestion($_POST['question_id'], $_POST['title'], $_POST['body']);
            $pesan = "Pertanyaan berhasil diperbarui!";
        }
    } catch (Exception $e) {
        $pesan = "Error: " . $e->getMessage();
    }
}

// Handle actions from GET requests
$action = $_GET['action'] ?? '';
try {
    if ($action == 'delete' && isset($_GET['id'])) {
        $question->deleteQuestion($_GET['id']);
        header("Location: index.php?page=questions&pesan=Pertanyaan berhasil dihapus!");
        exit;
    }
    
    if ($action == 'edit' && isset($_GET['id'])) {
        $question_to_edit = $question->getQuestionById($_GET['id']);
    }
} catch (Exception $e) { // Catch any exceptions during actions
    $pesan = "Error: " . $e->getMessage();
}

// Fetch all questions and users for display
$questions = $question->getAllQuestions();
$users = $user->getAllUsers(); 
?>

<h2>Manajemen Pertanyaan</h2>

<?php if ($pesan): ?>
    <div class="alert alert-info"><?php echo $pesan; ?></div>
<?php endif; ?>
<?php if (isset($_GET['pesan'])): ?>
    <div class="alert alert-info"><?php echo htmlspecialchars($_GET['pesan']); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <?php echo ($question_to_edit) ? 'Edit Pertanyaan' : 'Buat Pertanyaan Baru'; ?>
    </div>
    <div class="card-body">
        <form action="index.php?page=questions" method="POST">
            <?php if ($question_to_edit): ?>
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="question_id" value="<?php echo $question_to_edit['question_id']; ?>">
            <?php else: ?>
                <input type="hidden" name="action" value="create">
            <?php endif; ?>

            <div class="mb-3">
                <label for="title" class="form-label">Judul Pertanyaan</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($question_to_edit['title'] ?? ''); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="body" class="form-label">Isi Pertanyaan</label>
                <textarea class="form-control" id="body" name="body" rows="3" required><?php echo htmlspecialchars($question_to_edit['body'] ?? ''); ?></textarea>
            </div>
            
            <?php if (!$question_to_edit): ?>
            <div class="mb-3">
                <label for="author_id" class="form-label">Penanya</label>
                <select class="form-select" id="author_id" name="author_id" required>
                    <option value="">-- Pilih User --</option>
                    <?php foreach ($users as $u): ?>
                        <option value="<?php echo $u['user_id']; ?>"><?php echo htmlspecialchars($u['username']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <?php if ($question_to_edit): ?>
                <a href="index.php?page=questions" class="btn btn-secondary">Batal Edit</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Daftar Pertanyaan</div>
    <div class="list-group list-group-flush">
        <?php foreach ($questions as $q): ?>
            <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">
                        <a href="index.php?page=question_detail&id=<?php echo $q['question_id']; ?>">
                            <?php echo htmlspecialchars($q['title']); ?>
                        </a>
                    </h5>
                    <small><?php echo date('d M Y', strtotime($q['created_at'])); ?></small>
                </div>
                <p class="mb-1"><?php echo nl2br(htmlspecialchars(substr($q['body'], 0, 150))); ?>...</p>
                <small>Oleh: <?php echo htmlspecialchars($q['username']); ?></small>
                <div class="mt-2">
                    <a href="index.php?page=questions&action=edit&id=<?php echo $q['question_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="index.php?page=questions&action=delete&id=<?php echo $q['question_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pertanyaan ini? (Semua jawaban terkait akan ikut terhapus)');">Delete</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>