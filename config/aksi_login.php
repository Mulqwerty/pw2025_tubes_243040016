<?php
session_start();
include 'koneksi.php';

$Username = $_POST['Username'];
$Password = $_POST['Password'];

$sql = mysqli_query($koneksi, "SELECT * FROM user WHERE Username='$Username' AND Password='$Password'");

$row = mysqli_num_rows($sql);

if ($row > 0) {
    $_SESSION['Username'] = $Username;
    $_SESSION['status'] = 'login';
    echo "<script>
            alert('Login Berhasil!');
            location.href = 'http://pw2025_tube_24040016.test/admin/admin.php';
          </script>";
}
else {
    echo "<script>
            alert('Login Gagal! Username atau Password salah.');
            location.href = '../LoginPage/index.php';
          </script>";
}
?>