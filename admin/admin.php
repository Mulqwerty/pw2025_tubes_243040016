<?php
// Mulai sesi (jika Anda memiliki sistem login)
session_start();

// --- Konfigurasi Database ---
// Sesuaikan dengan detail database Anda
$db_dsn = "mysql:host=localhost;dbname=galeri_foto;charset=utf8mb4"; // Ganti galeri_foto dengan nama DB Anda
$db_username = "root";   // Ganti dengan username DB Anda
$db_password = "";     // Ganti dengan password DB Anda

$uploadDir = 'uploads/'; // Direktori untuk menyimpan file foto

// Pastikan folder upload ada
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Inisialisasi PDO
$pdo = null;
try {
    $pdo = new PDO($db_dsn, $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Koneksi database berhasil!"; // Pesan ini bisa dihapus setelah testing
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

$message = '';
$messageType = '';
$currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Ambil UserID dari sesi (jika ada sistem login)
// Jika tidak ada sistem login, Anda mungkin perlu mengatur UserID default atau dari input manual
$loggedInUserID = $_SESSION['user_id'] ?? 1; // Ganti dengan ID pengguna yang sebenarnya, atau atur default jika belum ada sistem login

// --- Proses Upload Foto (INSERT ke Database) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upload') {
    $title = trim($_POST['NamaAlbum']);
    $description = trim($_POST['Deskrips']);
    $albumID = $_POST['Album_id'] ?? null; // Anda perlu menambahkan input untuk AlbumID jika dibutuhkan
                                           // Jika tidak ada, bisa diisi NULL atau ID album default

    if (!empty($title) && isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $fileName = time() . '_' . basename($_FILES['photo']['name']);
        $targetPath = $uploadDir . $fileName;

        // Validasi tipe file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = $_FILES['photo']['type'];

        if (in_array($fileType, $allowedTypes)) {
            // Validasi ukuran file (max 5MB)
            if ($_FILES['photo']['size'] <= 5 * 1024 * 1024) {
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
                    // File berhasil diunggah, sekarang simpan ke database
                    $tanggalUnggah = date('Y-m-d H:i:s'); // Format tanggal sesuai kolom TanggalUnggah (DATE) atau DATETIME jika di DB

                    $sql_insert_foto = "INSERT INTO foto (JudulFoto, Deskripsi, TanggalUnggah, LokasiFile, AlbumID, UserID) 
                                        VALUES (:judul, :deskripsi, :tanggal_unggah, :lokasi_file, :album_id, :user_id)";
                    $stmt_insert_foto = $pdo->prepare($sql_insert_foto);

                    $stmt_insert_foto->bindParam(':judul', $title);
                    $stmt_insert_foto->bindParam(':deskripsi', $description);
                    $stmt_insert_foto->bindParam(':tanggal_unggah', $tanggalUnggah);
                    $stmt_insert_foto->bindParam(':lokasi_file', $targetPath); // Simpan path lengkap
                    $stmt_insert_foto->bindParam(':album_id', $albumID, PDO::PARAM_INT); // Bind as INT
                    $stmt_insert_foto->bindParam(':user_id', $loggedInUserID, PDO::PARAM_INT); // Bind as INT

                    if ($stmt_insert_foto->execute()) {
                        $message = 'Foto berhasil diupload dan disimpan ke database!';
                        $messageType = 'success';
                    } else {
                        // Jika gagal menyimpan ke DB, hapus file yang sudah diupload
                        unlink($targetPath);
                        $message = 'Gagal menyimpan data foto ke database!';
                        $messageType = 'danger';
                    }
                } else {
                    $message = 'Gagal mengupload file foto ke server!';
                    $messageType = 'danger';
                }
            } else {
                $message = 'Ukuran file terlalu besar! Maksimal 5MB.';
                $messageType = 'warning';
            }
        } else {
            $message = 'Tipe file tidak didukung! Gunakan JPG, PNG, GIF, atau WebP.';
            $messageType = 'warning';
        }
    } else {
        $message = 'Mohon isi judul dan pilih foto!';
        $messageType = 'warning';
    }
}

// --- Proses Hapus Foto (DELETE dari Database) ---
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
    $deleteFotoID = $_GET['delete'];

    try {
        // Ambil lokasi file sebelum menghapus record dari DB
        $sql_get_file_path = "SELECT LokasiFile FROM foto WHERE FotoID = :foto_id";
        $stmt_get_file_path = $pdo->prepare($sql_get_file_path);
        $stmt_get_file_path->bindParam(':foto_id', $deleteFotoID, PDO::PARAM_INT);
        $stmt_get_file_path->execute();
        $foto_data = $stmt_get_file_path->fetch(PDO::FETCH_ASSOC);

        if ($foto_data) {
            $lokasiFileToDelete = $foto_data['LokasiFile'];

            // Hapus record dari database
            $sql_delete_foto = "DELETE FROM foto WHERE FotoID = :foto_id";
            $stmt_delete_foto = $pdo->prepare($sql_delete_foto);
            $stmt_delete_foto->bindParam(':foto_id', $deleteFotoID, PDO::PARAM_INT);

            if ($stmt_delete_foto->execute()) {
                if ($stmt_delete_foto->rowCount() > 0) {
                    // Hapus file fisik jika ada
                    if (file_exists($lokasiFileToDelete)) {
                        unlink($lokasiFileToDelete);
                    }
                    $message = 'Foto berhasil dihapus!';
                    $messageType = 'success';
                } else {
                    $message = 'Foto tidak ditemukan.';
                    $messageType = 'warning';
                }
            } else {
                $message = 'Gagal menghapus data foto dari database!';
                $messageType = 'danger';
            }
        } else {
            $message = 'Foto tidak ditemukan di database.';
            $messageType = 'warning';
        }
    } catch (PDOException $e) {
        $message = "Error saat menghapus foto: " . $e->getMessage();
        $messageType = 'danger';
    }
}

// --- Ambil Data Foto dari Database (untuk Dashboard dan Galeri) ---
$photos = [];
try {
    $sql_select_photos = "SELECT FotoID, JudulFoto, Deskripsi, TanggalUnggah, LokasiFile, AlbumID, UserID FROM foto ORDER BY TanggalUnggah DESC";
    $stmt_select_photos = $pdo->query($sql_select_photos);
    $photos = $stmt_select_photos->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "Error saat mengambil data foto: " . $e->getMessage();
    $messageType = 'danger';
}

// --- Ambil Data Pengguna dari Database (untuk Manajemen Pengguna) ---
$users = [];
try {
    // Asumsikan ada tabel 'users' dengan kolom UserID, Username, Email, Role
    $sql_select_users = "SELECT UserId, Username, Email FROM user ORDER BY UserId ASC";
    $stmt_select_users = $pdo->query($sql_select_users);
    $users = $stmt_select_users->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message_user_management = "Error saat mengambil data pengguna: " . $e->getMessage();
    $messageType_user_management = 'danger';
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Halaman Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="admin.css"> <style>
        /* CSS tambahan dari instruksi sebelumnya, atau dari admin.css Anda */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            z-index: 1030; /* Pastikan navbar di atas sidebar */
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            padding-top: 70px; /* Sesuaikan dengan tinggi navbar */
            color: #fff;
            transition: all 0.3s;
        }
        .sidebar a {
            padding: 15px 20px;
            text-decoration: none;
            font-size: 1.1em;
            color: #f8f9fa;
            display: block;
            transition: 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #495057;
            color: #fff;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            padding-top: 80px; /* Sesuaikan dengan tinggi navbar */
        }
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .photo-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            background-color: #fff;
        }
        .photo-card img {
            width: 100%;
            height: 200px; /* Ukuran tetap untuk gambar */
            object-fit: cover; /* Menyesuaikan gambar agar tidak terdistorsi */
            display: block;
        }
        .photo-card-body {
            padding: 15px;
        }
        .photo-preview {
            max-width: 100%;
            max-height: 300px;
            object-fit: contain;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
        }
        .upload-area {
            border: 2px dashed #007bff;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            background-color: #e9f5ff;
            transition: background-color 0.3s;
        }
        .upload-area.dragover {
            background-color: #d1e7ff;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="bi bi-camera-fill me-2"></i>Admin Panel</a>
            <form class="d-flex" role="search">
                <input class="form-control form-control-sm me-2" type="search" placeholder="Cari..." aria-label="Search" />
                <button class="btn btn-outline-light btn-sm" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </nav>

    <div class="sidebar">
        <a href="?page=dashboard" class="<?php echo $currentPage === 'dashboard' ? 'active' : ''; ?>">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard
        </a>
        <a href="?page=upload" class="<?php echo $currentPage === 'upload' ? 'active' : ''; ?>">
            <i class="bi bi-cloud-upload me-2"></i>Upload Foto
        </a>
        <a href="?page=gallery" class="<?php echo $currentPage === 'gallery' ? 'active' : ''; ?>">
            <i class="bi bi-images me-2"></i>Galeri Foto
        </a>
        <a href="?page=users" class="<?php echo $currentPage === 'users' ? 'active' : ''; ?>">
            <i class="bi bi-people me-2"></i>Manajemen Pengguna
        </a>
        <hr class="text-white"> 
        <a href="http://pw2025_tube_24040016.test/LoginPage/"><i class="bi bi-box-arrow-right me-2"></i>Logout</a> </div>

    <div class="content">
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                <i class="bi bi-<?php echo $messageType === 'success' ? 'check-circle' : ($messageType === 'danger' ? 'exclamation-triangle' : 'info-circle'); ?> me-2"></i>
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($currentPage === 'dashboard'): ?>
            <div class="row">
                <div class="col-12">
                    <h2><i class="bi bi-speedometer2 me-2"></i>Dashboard</h2>
                    <p class="text-muted">Selamat datang di panel admin! Kelola foto dan pengguna dengan mudah.</p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?php echo count($photos); ?></h4>
                                    <p class="mb-0">Total Foto</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-images" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?php echo count($users); ?></h4> <p class="mb-0">Total User</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-people" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?php
                                        $todayUploads = 0;
                                        foreach ($photos as $photo) {
                                            if (date('Y-m-d', strtotime($photo['TanggalUnggah'])) === date('Y-m-d')) {
                                                $todayUploads++;
                                            }
                                        }
                                        echo $todayUploads;
                                    ?></h4>
                                    <p class="mb-0">Upload Hari Ini</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-calendar-day" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?php
                                        $totalSize = 0;
                                        foreach ($photos as $photo) {
                                            if (file_exists($photo['LokasiFile'])) { // Pastikan file ada
                                                $totalSize += filesize($photo['LokasiFile']);
                                            }
                                        }
                                        echo round($totalSize / 1024 / 1024, 2);
                                    ?> MB</h4>
                                    <p class="mb-0">Total Ukuran Foto</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-hdd" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($photos)): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Foto Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            // Ambil 4 foto terbaru, karena sudah diurutkan DESC saat SELECT
                            $latestPhotos = array_slice($photos, 0, 4);
                            foreach ($latestPhotos as $photo):
                            ?>
                                <div class="col-md-3 mb-3">
                                    <div class="photo-card">
                                        <img src="<?php echo htmlspecialchars($photo['LokasiFile']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($photo['JudulFoto']); ?>">
                                        <div class="photo-card-body">
                                            <h6 class="card-title"><?php echo htmlspecialchars($photo['JudulFoto']); ?></h6>
                                            <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($photo['TanggalUnggah'])); ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        <?php elseif ($currentPage === 'upload'): ?>
            <h2><i class="bi bi-cloud-upload me-2"></i>Upload Foto</h2>
            <p class="text-muted">Upload foto baru ke galeri Anda</p>

            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="upload">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div id="uploadArea" class="upload-area mb-3">
                                    <i class="bi bi-cloud-upload" style="font-size: 3rem; color: #6c757d;"></i>
                                    <h5 class="mt-3">Drag & Drop foto atau klik untuk pilih</h5>
                                    <p class="text-muted">Maksimal 5MB • JPG, PNG, GIF, WebP</p>
                                    <input type="file" id="photo" name="photo" accept="image/*" class="d-none" required>
                                    <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('photo').click()">
                                        <i class="bi bi-folder2-open me-2"></i>Pilih File
                                    </button>
                                </div>
                                
                                <div id="imagePreview" class="text-center mb-3" style="display: none;">
                                    <img id="previewImg" class="photo-preview" alt="Preview">
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearPreview()">
                                            <i class="bi bi-x-circle me-1"></i>Ganti Foto
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul Foto *</label>
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Masukkan judul foto" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Deskripsi foto (opsional)"></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-upload me-2"></i>Upload Foto
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        <?php elseif ($currentPage === 'gallery'): ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="bi bi-images me-2"></i>Galeri Foto</h2>
                    <p class="text-muted">Kelola semua foto yang telah diupload</p>
                </div>
                <a href="?page=upload" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Upload Foto Baru
                </a>
            </div>

            <?php if (empty($photos)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-images" style="font-size: 4rem; color: #dee2e6;"></i>
                    <h4 class="mt-3 text-muted">Belum ada foto</h4>
                    <p class="text-muted">Upload foto pertama Anda untuk memulai galeri</p>
                    <a href="?page=upload" class="btn btn-primary">
                        <i class="bi bi-upload me-2"></i>Upload Foto
                    </a>
                </div>
            <?php else: ?>
                <div class="photo-grid">
                    <?php foreach ($photos as $photo): // $photos sudah diurutkan DESC dari query ?>
                        <div class="photo-card">
                            <img src="<?php echo htmlspecialchars($photo['LokasiFile']); ?>" alt="<?php echo htmlspecialchars($photo['JudulFoto']); ?>">
                            <div class="photo-card-body">
                                <h6 class="card-title"><?php echo htmlspecialchars($photo['JudulFoto']); ?></h6>
                                <?php if ($photo['Deskripsi']): ?>
                                    <p class="card-text text-muted small"><?php echo htmlspecialchars($photo['Deskripsi']); ?></p>
                                <?php endif; ?>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?php echo date('d/m/Y', strtotime($photo['TanggalUnggah'])); ?>
                                    </small>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary btn-sm" title="Lihat" onclick="viewPhoto('<?php echo htmlspecialchars($photo['LokasiFile']); ?>', '<?php echo htmlspecialchars($photo['JudulFoto']); ?>')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <a href="?page=gallery&delete=<?php echo $photo['FotoID']; ?>" class="btn btn-outline-danger btn-sm" title="Hapus" onclick="return confirm('Yakin ingin menghapus foto ini?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php elseif ($currentPage === 'users'): ?>
            <h2><i class="bi bi-people me-2"></i>Manajemen Pengguna</h2>
            <p class="text-muted">Kelola pengguna sistem</p>

            <?php if (isset($message_user_management)): ?>
                <div class="alert alert-<?php echo $messageType_user_management; ?> alert-dismissible fade show" role="alert">
                    <i class="bi bi-<?php echo $messageType_user_management === 'success' ? 'check-circle' : ($messageType_user_management === 'danger' ? 'exclamation-triangle' : 'info-circle'); ?> me-2"></i>
                    <?php echo $message_user_management; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="bi bi-person-plus me-2"></i>Tambah Pengguna Baru
                </button>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Pengguna</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada data pengguna.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($user['UserId']); ?></td>
                                            <td><?php echo htmlspecialchars($user['Username']); ?></td>
                                            <td><?php echo htmlspecialchars($user['Email']); ?></td>
                                            <td>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" title="Edit Pengguna"
                                                        data-bs-toggle="modal" data-bs-target="#editUserModal"
                                                        data-userid="<?php echo $user['UserId']; ?>"
                                                        data-username="<?php echo htmlspecialchars($user['Username']); ?>"
                                                        data-email="<?php echo htmlspecialchars($user['Email']); ?>">
                                                    <i class="bi bi-pencil me-1"></i>Edit
                                                </button>
                                                <a href="aksi_hapus_user.php?id=<?php echo $user['UserId']; ?>"
                                                   class="btn btn-sm btn-danger" title="Hapus Pengguna"
                                                   onclick="return confirm('Yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan!')">
                                                    <i class="bi bi-trash me-1"></i>Hapus
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="aksi_tambah_user.php" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addUserModalLabel">Tambah Pengguna Baru</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="new_username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="new_username" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="new_password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="new_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="new_email" name="email" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Pengguna</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="/config/ubah_data.php" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editUserModalLabel">Edit Pengguna</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="edit_user_id" name="user_id">
                                <div class="mb-3">
                                    <label for="edit_username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="edit_username" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="edit_email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_password" class="form-label">Password Baru (kosongkan jika tidak diubah)</label>
                                    <input type="password" class="form-control" id="edit_password" name="password">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </div>

    <div class="modal fade" id="photoModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoModalTitle">Preview Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" class="img-fluid" alt="Preview">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Preview gambar saat dipilih
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                    document.getElementById('uploadArea').style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        // Drag and drop functionality
        const uploadArea = document.getElementById('uploadArea');
        const photoInput = document.getElementById('photo');

        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                photoInput.files = files;
                photoInput.dispatchEvent(new Event('change'));
            }
        });

        // Clear preview
        function clearPreview() {
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('uploadArea').style.display = 'block';
            document.getElementById('photo').value = '';
        }

        // View photo in modal
        function viewPhoto(src, title) {
            document.getElementById('modalImage').src = src;
            document.getElementById('photoModalTitle').textContent = title;
            new bootstrap.Modal(document.getElementById('photoModal')).show();
        }

        // Click upload area to select file
        uploadArea.addEventListener('click', function() {
            photoInput.click();
        });

        // JavaScript untuk mengisi modal edit pengguna
        const editUserModal = document.getElementById('editUserModal');
        editUserModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Tombol yang memicu modal
            const userId = button.getAttribute('data-userid');
            const username = button.getAttribute('data-username');
            const email = button.getAttribute('data-email');
            const role = button.getAttribute('data-role');

            const modalTitle = editUserModal.querySelector('.modal-title');
            const inputUserId = editUserModal.querySelector('#edit_user_id');
            const inputUsername = editUserModal.querySelector('#edit_username');
            const inputEmail = editUserModal.querySelector('#edit_email');
            const selectRole = editUserModal.querySelector('#edit_role');

            modalTitle.textContent = 'Edit Pengguna: ' + username;
            inputUserId.value = userId;
            inputUsername.value = username;
            inputEmail.value = email;
            selectRole.value = role;
            // Kosongkan field password baru setiap kali modal dibuka
            editUserModal.querySelector('#edit_password').value = '';
        });
    </script>
</body>
</html>