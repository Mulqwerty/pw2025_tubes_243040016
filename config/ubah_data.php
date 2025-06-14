<?php
include 'function.php';
$user = query("SELECT * FROM user"); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $newName = $_POST['name'] ?? null;

    // 3. Validasi Sederhana
    if ($id && $newName) {
        try {
            // 4. Query UPDATE
            $stmt = $pdo->prepare("UPDATE user SET name = ? WHERE id = ?");
            $stmt->execute([$newName, $id]);

            // 5. Cek Hasil dan Berikan Feedback
            if ($stmt->rowCount() > 0) {
                echo "Data pengguna berhasil diubah menjadi: " . htmlspecialchars($newName);
            } else {
                echo "Tidak ada perubahan data atau ID pengguna tidak ditemukan.";
            }
        } catch (PDOException $e) {
            echo "Gagal mengubah data: " . $e->getMessage();
        }
    } 
}
?>