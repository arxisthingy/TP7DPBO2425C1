<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum Diskusi OOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CSS tambahan agar tampilan rapi */
        body { display: flex; flex-direction: column; min-height: 100vh; }
        main { flex: 1; }
        .card { margin-bottom: 1.5rem; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php?page=home">ForumDB</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=questions">Lihat Pertanyaan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=users">Manajemen User</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">