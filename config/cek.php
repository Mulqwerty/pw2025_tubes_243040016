<?php
// Contoh koneksi
$koneksi = mysqli_connect('localhost', 'root', '', 'nama_database');
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Jalankan query
$sql = "SELECT * FROM user WHERE Username='$Username' AND Password='$Password'";
$result = mysqli_query($koneksi, $sql);

// Cek apakah query berhasil
if (!$result) {
    die("Query Error: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
} else {
    echo "Query berhasil dijalankan.";
}
?>