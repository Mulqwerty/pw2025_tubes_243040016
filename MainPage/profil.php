<?php
// KONEKSI KE DATABASE
$conn = mysqli_connect('localhost', 'root', '', 'galeri_foto');
// QUERY KE TABEL MAHASISWA
$result = mysqli_query($conn, "SELECT * FROM user");
// UBAH DATA KE DALAM ARRAY
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}
// SIMPAN KE VARIABLE MAHASISWA
$user = $rows;  
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Profil User</title>
    <style>
        body {
            background-color: #fafafa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0; padding: 0;
        }
        .profile-wrapper {
            max-width: 480px;
            background: #fff;
            margin: 60px auto;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgb(0 0 0 / 0.1);
            text-align: center;
        }
        .avatar {
            width: 100px;
            height: 100px;
            background-color: #ffb74d;
            border-radius: 50%;
            font-size: 48px;
            color: white;
            font-weight: 700;
            line-height: 100px;
            margin: 0 auto 20px;
            user-select: none;
        }
        h1 {
            margin: 0 0 8px;
            font-weight: 700;
            color: #333;
        }
        .username {
            color: #666;
            margin-bottom: 24px;
            font-size: 18px;
        }
        .info {
            text-align: left;
            margin-bottom: 16px;
            font-size: 16px;
            color: #444;
        }
        .info label {
            font-weight: 600;
            display: block;
            margin-bottom: 4px;
            color: #888;
        }
        .logout-btn {
            display: inline-block;
            padding: 12px 28px;
            background-color: #e60023;
            color: white;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .logout-btn:hover {
            background-color: #b8001a;
        }
    </style>
</head>
<body>

<div class="profile-wrapper">
    <div class="avatar"></div>
    <h1><?php echo htmlspecialchars($NamaLengkap); ?></h1>
    <div class="username">@<?php echo htmlspecialchars($Username); ?></div>

    <div class="info">
        <label>Email</label>
        <div><?php echo htmlspecialchars($Email); ?></div>
    </div>

    <a href="../index.php" class="logout-btn">Logout</a>
</div>

</body>
</html>
