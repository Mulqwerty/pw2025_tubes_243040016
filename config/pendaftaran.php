<?php
session_start();
include 'koneksi.php';

function login($koneksi, $username, $password) {
    // Prepared statement untuk keamanan
    $stmt = $koneksi->prepare("SELECT * FROM user WHERE Username = ? AND Password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah user ditemukan
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Set session
        $_SESSION['Username'] = $user['Username'];
        $_SESSION['NamaLengkap'] = $user['NamaLengkap'];
        $_SESSION['status'] = 'login';
        return true;
    } else {
        return false;
    }
}

// Contoh pemakaian fungsi login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['Username'];
    $password = $_POST['Password'];

    if (login($koneksi, $username, $password)) {
        echo "<script>
                alert('Login Berhasil!');
                location.href = 'http://pw2025_tube_24040016.test/admin/admin.php';
              </script>";
    } else {
        echo "<script>
                alert('Login Gagal! Username atau Password salah.');
                location.href = '../LoginPage/index.php';
              </script>";
    }
}
?>
