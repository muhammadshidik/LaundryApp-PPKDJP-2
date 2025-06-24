<?php
// Memulai session PHP agar bisa menggunakan $_SESSION
session_start();

// Memanggil file koneksi ke database
require_once 'admin/controller/koneksi.php';

// Memanggil file yang berisi fungsi-fungsi tambahan (jika ada)
require_once 'admin/controller/functions.php';

// Mengecek apakah form login sudah disubmit (tombol 'login' diklik)
if (isset($_POST['login'])) {

  // Menyimpan inputan dari form ke variabel
  $email    = $_POST['email'];     // Menyimpan email dari form
  $password = $_POST['password'];  // Menyimpan password dari form

  // Melakukan query ke database untuk mencari user dengan email dan password yang sesuai
  $queryLogin = mysqli_query($connection, "SELECT * FROM user WHERE email='$email' AND password='$password'");

  // Mengecek apakah ada baris data yang ditemukan (user cocok)
  // mysqli_num_rows() berfungsi menghitung jumlah baris hasil query
  if (mysqli_num_rows($queryLogin) > 0) {

    // Mengambil data user dalam bentuk array asosiatif
    $rowLogin = mysqli_fetch_assoc($queryLogin);

    // Memastikan password yang dimasukkan sama persis (sebenarnya redundant karena sudah dicek di query)
    if ($password == $rowLogin['password']) {

      // Menyimpan data user ke dalam session (untuk keperluan login)
      $_SESSION['id'] = $rowLogin['id'];         // Menyimpan ID user
      $_SESSION['name'] = $rowLogin['name'];     // Menyimpan nama user

      // Mengarahkan ke halaman menu jika login berhasil
      header("location:menu.php");
      die; // Menghentikan proses script setelah redirect

    } else {
      // Jika password tidak cocok, redirect ke halaman login dengan parameter gagal
      header("location:login.php?login=failed");
      die;
    }

  }
}
?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Flexy Free Bootstrap Admin Template by WrapPixel</title>
  <link rel="shortcut icon" type="image/png" href="tmp/assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="template/assets/css/styles.min.css" />
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="./index.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="tmp/assets/images/logos/logo.svg" alt="">
                </a>
                <?php if (isset($_GET['login']) && $_GET['login'] == 'failed') : ?>
                  <div class="alert alert-danger alert-dismissible" role="alert">
                    Invalid email or password.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                <?php elseif (isset($_GET['register']) && $_GET['register'] == 'success'): ?>
                  <div class="alert alert-success alert-dismissible" role="alert">
                    Your account has registered successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                <?php endif ?>
              <!-- END Alert  -->
                <p class="text-center">Your Social Campaigns</p>
                  <!-- Alert  -->
                <form action="" method="POST">
                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text"
                  class="form-control"
                  id="email"
                  name="email"
                  placeholder="Enter your email"
                  value=""
                  autofocus>
                  </div>
                  <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password"
                    id="password"
                    class="form-control"
                    name="password"
                    placeholder="Enter your password"
                    aria-describedby="password">
                  </div>
                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                      <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
                      <label class="form-check-label text-dark" for="flexCheckChecked">
                        Remeber this Device
                      </label>
                    </div>
                    <a class="text-primary fw-bold" href="./index.html">Forgot Password ?</a>
                  </div>
                  <button href="" name="login" type="submit" class="btn btn-success w-100 py-8 fs-4 mb-4 rounded-2">Login</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="template/assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="template/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>