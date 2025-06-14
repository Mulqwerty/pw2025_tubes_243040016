<?php
session_start(); // Mulai sesi di awal file
require_once 'koneksi.php'; // Sertakan file konfigurasi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['Username']) ? trim($_POST['Username']) : '';
    $password = isset($_POST['Password']) ? $_POST['Password'] : '';

    if (empty($username) || empty($password)) {
        $_SESSION['login_message'] = "Username dan password harus diisi.";
        $_SESSION['login_message_type'] = 'warning';
        header("http://pw2025_tube_24040016.test/LoginPage/"); // Kembali ke halaman login
        exit();
    }

    try {
        // Ambil data pengguna dari database
        $stmt = $pdo->prepare("SELECT UserId, Username, Password, Email FROM user WHERE Username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            // Verifikasi password
            if (password_verify($password, $user['Password'])) {
                // Autentikasi berhasil
                // Hanya izinkan user dengan username 'admin' masuk ke halaman admin
                if ($user['Username'] === 'admin') {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_id'] = $user['UserID'];
                    $_SESSION['username'] = $user['Username'];
                    // $_SESSION['role'] = $user['Role']; // Baris ini dihilangkan karena role tidak digunakan

                    $_SESSION['admin_message'] = "Selamat datang, Admin!";
                    $_SESSION['admin_message_type'] = 'success';
                    header("Location: http://pw2025_tube_24040016.test/admin/admin.php"); // Redirect ke halaman admin
                    exit();
                } else {
                    // Jika username bukan 'admin'
                    $_SESSION['login_message'] = "Anda tidak memiliki izin untuk mengakses halaman admin.";
                    $_SESSION['login_message_type'] = 'danger';
                    header("Location: ../Main.php"); // Kembali ke halaman login
                    exit();
                }
            } else {
                // Password tidak cocok
                $_SESSION['login_message'] = "Username atau password salah.";
                $_SESSION['login_message_type'] = 'danger';
                header("Location: http://pw2025_tube_24040016.test/LoginPage/");
                exit();
            }
        } else {
            // Username tidak ditemukan
            $_SESSION['login_message'] = "Username atau password salah.";
            $_SESSION['login_message_type'] = 'danger';
            header("Location: http://pw2025_tube_24040016.test/LoginPage/");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['login_message'] = "Terjadi kesalahan database: " . $e->getMessage();
        $_SESSION['login_message_type'] = 'danger';
        header("Location: http://pw2025_tube_24040016.test/LoginPage/");
        exit();
    }
} else {
    // Jika diakses langsung tanpa POST request
    $_SESSION['login_message'] = "Akses tidak valid.";
    $_SESSION['login_message_type'] = 'danger';
    header("Location: http://pw2025_tube_24040016.test/LoginPage/");
    exit();
}
?>