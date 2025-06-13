
<?php
 include '../config/function.php';

if(isset($_POST['Daftar'])) {
   if(register($_POST) > 0) {
       echo "<script>
               alert('Registrasi Berhasil!');
               document.location.href = '../LoginPage/index.php';
             </script>";
   } else {
       echo "<script>
               alert('Registrasi Gagal!');
             </script>";
   }
}
 ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Scatti d’ <span>Arte</span></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
    </div>
    <a href="RegisterPage/Register.php" class="btn btn-outline-primary m-1">Daftar
    </a>
    <a href="../LoginPage/index.php" class="btn btn-outline-primary m-1">Masuk
    </a>
  </nav>

  <div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
      <div class="card shadow p-4 " data-aos="zoom-in" style="width: 100%; max-width: 410px;">
        <div class="card-body">

          <h4 class="card-title text-center ">Daftar</h4>
          <form action="http://pw2025_tube_24040016.test/config/pendaftaran.php" menthod="POST">
            <div class="mb-3">
              <label for="namalengkap" class="form-label">Nama Lengkap</label>
              <input type="text" class="form-control" id="NamaLengkap" name="NamaLengkap" required >
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="text" class="form-control" id="Email " name="Email" required >
            </div>
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="Username" name="Username" required >
            </div>
            <div class="mb-3">
              <label for="Password" class="form-label">Password</label>
              <input type="Password" class="form-control" id="Password" name="Password" required >
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-2 " name="Daftar">Daftar</button>
          </form>
        </div>
        <div class="card-footer-custom text-center">
          <small>Sudah punya akun? <a href="../LoginPage/index.php">Masuk</a></small>
        </div>
      </div>
    </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
<footer class="footer mt-5 text-center">
  <p>&copy; 2022 Your Website. All Rights Reserved.</p>
</footer>