<?php

//user Management Page
$pesan = '';
$user_to_edit = null;

// handle form submissions for creating and updating users
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action == 'create') {
            $user->createUser($_POST['username'], $_POST['password'], $_POST['email']);
            $pesan = "User berhasil ditambahkan!";
        } else if ($action == 'update') {
            $user->updateUser($_POST['user_id'], $_POST['username'], $_POST['email']);
            $pesan = "User berhasil diperbarui!";
        }
    } catch (Exception $e) {
        $pesan = "Error: " . $e->getMessage();
    }
}

// Handle actions from GET requests
$action = $_GET['action'] ?? '';
try {
    if ($action == 'delete' && isset($_GET['id'])) {
        $user->deleteUser($_GET['id']);
        header("Location: index.php?page=users&pesan=User berhasil dihapus!");
        exit;
    }
    
    if ($action == 'edit' && isset($_GET['id'])) {
        $user_to_edit = $user->getUserById($_GET['id']);
    }
} catch (Exception $e) {
    // Catch any exceptions during actions
    $pesan = "Error: " . $e->getMessage();
}

// Fetch all users for display
$users = $user->getAllUsers();
?>

<h2>Manajemen Pengguna</h2>

<?php if ($pesan): ?>
    <div class="alert alert-info"><?php echo $pesan; ?></div>
<?php endif; ?>
<?php if (isset($_GET['pesan'])): ?>
    <div class="alert alert-info"><?php echo htmlspecialchars($_GET['pesan']); ?></div>
<?php endif; ?>


<div class="card">
    <div class="card-header">
        <?php echo ($user_to_edit) ? 'Edit Pengguna' : 'Tambah Pengguna Baru'; ?>
    </div>
    <div class="card-body">
        <form action="index.php?page=users" method="POST">
            
            <?php if ($user_to_edit): ?>
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="user_id" value="<?php echo $user_to_edit['user_id']; ?>">
            <?php else: ?>
                <input type="hidden" name="action" value="create">
            <?php endif; ?>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user_to_edit['username'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_to_edit['email'] ?? ''); ?>" required>
            </div>
            
            <?php if (!$user_to_edit): ?>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <?php if ($user_to_edit): ?>
                <a href="index.php?page=users" class="btn btn-secondary">Batal Edit</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Daftar Pengguna</div>
    <div class="list-group list-group-flush">
        <?php foreach ($users as $u): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong><?php echo htmlspecialchars($u['username']); ?></strong>
                    <small class="text-muted">(<?php echo htmlspecialchars($u['email']); ?>)</small>
                </div>
                <div>
                    <a href="index.php?page=users&action=edit&id=<?php echo $u['user_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="index.php?page=users&action=delete&id=<?php echo $u['user_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus user ini? Semua pertanyaan dan jawaban user ini akan ikut terhapus.');">Delete</a>
                </div>
            </li>
        <?php endforeach; ?>
    </div>
</div>