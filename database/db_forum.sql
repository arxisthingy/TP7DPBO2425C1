CREATE DATABASE forum_db;
USE forum_db;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    reputation_points INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE questions (
    question_id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (author_id) 
        REFERENCES users(user_id) 
        ON DELETE CASCADE
);

CREATE TABLE answers (
    answer_id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    author_id INT NOT NULL,
    body TEXT NOT NULL,
    is_accepted BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (question_id) 
        REFERENCES questions(question_id) 
        ON DELETE CASCADE,

    FOREIGN KEY (author_id) 
        REFERENCES users(user_id) 
        ON DELETE CASCADE
);

INSERT INTO users (username, email, password, reputation_points) 
VALUES 
('budi_santoso', 'budi@example.com', 'pass_terenkripsi_123', 10),
('citra_lestari', 'citra@example.com', 'pass_terenkripsi_123', 25),
('doni_wijaya', 'doni@example.com', 'pass_terenkripsi_123', 5);

INSERT INTO questions (author_id, title, body) 
VALUES 
(1, 'Apa itu OOP dalam PHP?', 'Saya baru belajar PHP dan sering dengar istilah OOP. Apa sebenarnya maksudnya dan kenapa penting?'),
(2, 'Bagaimana cara mencegah SQL Injection?', 'Saya diberitahu bahwa kode saya rawan SQL Injection. Bagaimana cara terbaik untuk memperbaikinya menggunakan PHP?');

INSERT INTO answers (question_id, author_id, body, is_accepted) 
VALUES 
(1, 2, 'OOP (Object-Oriented Programming) itu paradigma pemrograman yang fokus ke "objek". Di PHP, kamu bisa buat "class" sebagai blueprint dan "object" sebagai hasilnya.', 1),
(1, 3, 'Menambahkan jawaban Citra, OOP penting karena membuat kode lebih terstruktur, mudah di-maintain, dan reusable (bisa dipakai ulang) dengan konsep seperti inheritance.', 0),
(2, 1, 'Cara terbaik adalah menggunakan "Prepared Statements". Di PHP, kamu bisa pakai PDO atau objek mysqli (bukan fungsi mysqli_query biasa).', 0),
(2, 3, 'Betul kata Budi. Dengan prepared statements, query dan data dikirim terpisah, jadi peretas tidak bisa menyisipkan kode SQL berbahaya lewat input form.', 0);