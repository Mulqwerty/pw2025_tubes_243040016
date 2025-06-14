<?php
include 'koneksi.php';

$Username = $_POST['Username'];
$Password = $_POST['Password'];
$Email = $_POST['Email'];
$NamaLengkap = $_POST['NamaLengkap'];

$sql = mysqli_query($koneksi, "INSERT INTO user VALUES (null,'$Username', '$Password', '$Email', '$NamaLengkap')");

if ($sql) {
    echo "<script>
    alert('Pendaftaran Berhasil!'); 
    location.href='http://pw2025_tube_24040016.test/LoginPage/index.php';
    </script>";
}

?>