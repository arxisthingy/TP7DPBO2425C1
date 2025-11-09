# TP7DPBO2425C1
## Janji
Saya Dzaka Musyaffa Hidayat dengan NIM 2404913 mengerjakan Tugas Praktikum 7 dalam mata kuliah Desain Pemrograman Berorientasi Objek untuk keberkahanNya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

## Deskripsi 

Aplikasi ini dibuat menggunakan PHP (Native) dan menggunakan struktur OOP (_Object-Oriented Programming_).

Semua halaman diatur oleh satu file utama, ``index.php``. File ini bertugas sebagai "router" yang akan memuat tampilan halaman yang sesuai (seperti halaman User, Pertanyaan, atau Jawaban) berdasarkan link yang diklik pengguna.

---

## Fitur

Aplikasi ini mengimplementasikan fungsionalitas CRUD (Create, Read, Update, Delete) penuh untuk semua entitas, dengan fitur tambahan untuk manajemen jawaban.

* **Manajemen Pengguna (CRUD)**
    * **Create:** Menambahkan pengguna baru ke database (password di-*hash*).
    * **Read:** Menampilkan daftar semua pengguna.
    * **Update:** Mengubah data pengguna (username dan email).
    * **Delete:** Menghapus pengguna dari database.

* **Manajemen Pertanyaan (CRUD)**
    * **Create:** Memposting pertanyaan baru (terhubung dengan `author_id`).
    * **Read:** Menampilkan daftar semua pertanyaan beserta nama penulisnya.
    * **Update:** Mengubah judul dan isi pertanyaan.
    * **Delete:** Menghapus pertanyaan (dan semua jawaban yang terkait akan ikut terhapus secara otomatis berkat `ON DELETE CASCADE` di database).

* **Manajemen Jawaban (CRUDA)**
    * **Create:** Memposting jawaban baru untuk pertanyaan tertentu.
    * **Read:** Menampilkan semua jawaban di bawah pertanyaan terkait.
    * **Update:** Mengedit teks dari jawaban yang sudah ada.
    * **Delete:** Menghapus sebuah jawaban.
    * **Accept (Fitur Khusus):** Memilih satu jawaban sebagai "Jawaban Terbaik" (`is_accepted`), yang akan secara otomatis me-reset status jawaban terbaik lainnya untuk pertanyaan tersebut.

---

## Alur Program (Front Controller)

Aplikasi ini menggunakan `index.php` sebagai satu-satunya titik masuk (Front Controller) untuk semua navigasi halaman.

1.  Pengguna mengakses `index.php`.
2.  `index.php` memuat file konfigurasi database (`config/db.php`) dan semua definisi *Class* Model (`class/User.php`, `class/Question.php`, `class/Answer.php`).
3.  `index.php` membuat *instance* global untuk setiap *Class* Model (misal: `$user = new User();`).
4.  `index.php` memuat `view/header.php` (yang berisi bagian atas HTML dan navigasi).
5.  Berdasarkan parameter URL `$_GET['page']` (cth: `index.php?page=users`), `index.php` menggunakan `switch` *statement* untuk menentukan dan memuat file *view* yang benar dari folder `view/` (cth: `include 'view/users.php';`).
6.  File *view* yang dimuat (cth: `view/users.php`) berisi semua logika PHP dan HTML untuk halaman tersebut. File ini akan:
    * Menangani input `$_POST` untuk logika Create atau Update.
    * Menangani input `$_GET['action']` untuk logika Edit, Delete, atau Accept.
    * Memanggil *method* dari *class* Model global (cth: `$users = $user->getAllUsers();` atau `$answer->acceptAnswer(...)`).
    * Menampilkan data dan formulir ke pengguna.
7.  Jika file *view* melakukan operasi C/U/D, file tersebut akan mengarahkan (redirect) pengguna kembali ke `index.php` dengan parameter yang sesuai (cth: `header("Location: index.php?page=users&pesan=...");`).
8.  Setelah eksekusi file *view* selesai, `index.php` memuat `view/footer.php` (yang berisi penutup HTML).

---

### Struktur Folder

Struktur file proyek ini memisahkan antara logika (Class), tampilan (View), dan konfigurasi (Config).

```
/nama_proyek_anda/
├── config/
│   └── db.php
│
├── class/
│   ├── User.php
│   ├── Question.php
│   └── Answer.php
│
├── view/
│   ├── header.php
│   ├── footer.php
│   ├── users.php
│   ├── questions.php
│   └── question_detail.php
│
├── index.php
└── db_forum.sql
```

### Desain Class (Model)

* **`Database` (di `config/db.php`):** Bertanggung jawab hanya untuk membuat dan menyediakan koneksi `PDO` ke database.
* **`User` (di `class/User.php`):** Berisi *method* untuk `getAllUsers`, `getUserById`, `createUser`, `updateUser`, dan `deleteUser`.
* **`Question` (di `class/Question.php`):** Berisi *method* untuk `getAllQuestions`, `getQuestionById`, `createQuestion`, `updateQuestion`, dan `deleteQuestion`.
* **`Answer` (di `class/Answer.php`):** Berisi *method* untuk `getAnswersByQuestionId`, `getAnswerById`, `createAnswer`, `updateAnswer`, `deleteAnswer`, dan `acceptAnswer`.

### Skema Database

Database `forum_db` terdiri dari 3 tabel dengan relasi sebagai berikut:

1.  **`users`**
    * `user_id` (Primary Key)
    * `username`
    * `email`
    * `password`
    * `reputation_points`

2.  **`questions`**
    * `question_id` (Primary Key)
    * `author_id` (Foreign Key ke `users.user_id`)
    * `title`
    * `body`

3.  **`answers`**
    * `answer_id` (Primary Key)
    * `question_id` (Foreign Key ke `questions.question_id`)
    * `author_id` (Foreign Key ke `users.user_id`)
    * `body`
    * `is_accepted` (Boolean)

**Relasi:**
* Satu `User` bisa memiliki banyak `Questions` (1-to-N).
* Satu `User` bisa memiliki banyak `Answers` (1-to-N).
* Satu `Question` bisa memiliki banyak `Answers` (1-to-N).
