<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Scatti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>
<style>
  .card{
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    align-items: flex-start;
}

.container{
  margin-left: 40px;
  justify-content: center;
}
</style>

<body>
<div class="container-fluid">
  <div class="row flex-nowrap">
    <!-- Sidebar -->
    <div class="col-auto col-md-2 col-xl-1 px-sm-2 px-0 bg-white border-end min-vh-100 sidebar-custom">
      <div class="d-flex flex-column align-items-center align-items-sm-start pt-3">
        <div class="navbrand">
        <a href="#" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-decoration-none ">Scatti D'arte</a>
        </div>
        <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
          <li class="nav-item w-100">
            <a href="#" class="nav-link text-dark active d-flex align-items-center">
              <i class="bi bi-house-door fs-4"></i>
              <span class="ms-2 d-none d-sm-inline">Home</span>
            </a>
          </li>
          <li class="nav-item w-100">
            <a href="#" class="nav-link text-dark d-flex align-items-center">
              <i class="bi bi-search fs-4"></i>
              <span class="ms-2 d-none d-sm-inline">Search</span>
            </a>
          </li>
          <li class="nav-item w-100">
            <a href="#" class="nav-link text-dark d-flex align-items-center">
              <i class="bi bi-plus-square fs-4"></i>
              <span class="ms-2 d-none d-sm-inline">Add</span>
            </a>
          </li>
          <li class="nav-item w-100">
            <a href="#" class="nav-link text-dark d-flex align-items-center position-relative">
              <i class="bi bi-bell fs-4"></i>
              <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle"></span>
              <span class="ms-2 d-none d-sm-inline">Notif</span>
            </a>
          </li>
          <li class="nav-item w-100">
            <a href="#" class="nav-link text-dark d-flex align-items-center">
              <i class="bi bi-chat-dots fs-4"></i>
              <span class="ms-2 d-none d-sm-inline">Chat</span>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col py-0 px-0">
      <!-- Topbar with Search and Profile -->
      <nav class="navbar navbar-light bg-white border-bottom px-4 py-2 d-flex justify-content-between align-items-center">
        <form class="d-flex w-50 mx-auto" role="search">
          <input class="form-control rounded-pill px-4" type="search" placeholder="Cari" aria-label="Search">
        </form>
        <div class="dropdown">
          <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownProfile" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="profile-circle">
            <i class="bi bi-person-circle"></i>
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownProfile">
            <li><a class="dropdown-item" href="profil.php">Profil</a></li>
            <li><a class="dropdown-item" href="#">Pengaturan</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="http://pw2025_tube_24040016.test/LoginPage/">Keluar</a></li>
          </ul>
        </div>
      </nav>
      <!-- Konten utama di sini -->
       

      <section class="container"> 
      <div class="p-4">
      <div class="container mt-3">
    <div class="row g-2"> <div class="col-md-3">
            <div class="card">
                <img src="http://pw2025_tube_24040016.test/CSS/img/img1.jpg" class="card-img-top" alt="Gambar Album">
                <div class="card-footer text-center">
                    <a href="#" class="me-2 text-decoration-none text-dark"><i class="bi bi-heart"></i> 10 suka</a>
                    <a href="#" class="text-decoration-none text-dark"><i class="bi bi-chat-dots"></i> 3 komentar</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <img src="http://pw2025_tube_24040016.test/CSS/img/img2.jpg" class="card-img-top" alt="Gambar Album">
                <div class="card-footer text-center">
                    <a href="#" class="me-2 text-decoration-none text-dark"><i class="bi bi-heart"></i> 5 suka</a>
                    <a href="#" class="text-decoration-none text-dark"><i class="bi bi-chat-dots"></i> 1 komentar</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <img src="http://pw2025_tube_24040016.test/CSS/img/img3.jpg" class="card-img-top" alt="Gambar Album">
                <div class="card-footer text-center">
                    <a href="#" class="me-2 text-decoration-none text-dark"><i class="bi bi-heart"></i> 12 suka</a>
                    <a href="#" class="text-decoration-none text-dark"><i class="bi bi-chat-dots"></i> 4 komentar</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <img src="http://pw2025_tube_24040016.test/CSS/img/img4.jpg" class="card-img-top" alt="Gambar Album">
                <div class="card-footer text-center">
                    <a href="#" class="me-2 text-decoration-none text-dark"><i class="bi bi-heart"></i> 8 suka</a>
                    <a href="#" class="text-decoration-none text-dark"><i class="bi bi-chat-dots"></i> 2 komentar</a>
                </div>
            </div>
        </div>
    </div>
</div>

        </div>
        </section>
        <!-- Konten grid gambar, dsb -->
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS & Icons -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
