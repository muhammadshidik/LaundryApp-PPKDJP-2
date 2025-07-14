<?php 
// Memulai session agar bisa menggunakan $_SESSION
session_start();

// Membuat session ID baru dan menghapus session ID lama untuk meningkatkan keamanan (mencegah session fixation)
session_regenerate_id();

// Mengaktifkan output buffering (penyimpanan output sementara sebelum dikirim ke browser)
ob_start();

// Membersihkan output buffer, menghapus semua isi buffer (jika ada output sebelumnya)
ob_clean();

// Memanggil file koneksi ke database
require_once 'admin/controller/koneksi.php';

// Memanggil file yang berisi fungsi-fungsi tambahan
require_once 'admin/controller/functions.php';

// Mengecek apakah session 'id' kosong (belum login atau session habis)
if (empty($_SESSION['id'])) {
  // Jika belum login, arahkan pengguna ke halaman logout (biasanya akan diarahkan ke login page lagi)
  header('Location: admin/controller/logout.php');
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>🧺 Laundry Om Ruben</title>
    <link rel="shortcut icon" type="image/png" href="" />
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <?php include 'admin/inc/css.php' ?>
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
       <?php include 'admin/inc/sidebar.php' ?>
      <!-- End Sidebar -->
      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
      <!-- Logo Header -->
           <?php include 'admin/inc/logoHeader.php' ?>
      <!-- End Logo Header -->
          </div>
      <!-- Navbar Header -->
          <?php include 'admin/inc/navbar.php'?>
      <!-- End Navbar -->
        </div>
       <!-- start isi content -->
        <div class="container">
          <div class="page-inner">
            <div
              class=" align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
        <!-- isi disini contentnya -->
          <?php
            // Mengecek apakah parameter 'page' ada di URL (contoh: index.php?page=produk)
            if (isset($_GET['page'])) {

              // Mengecek apakah file yang diminta oleh 'page' benar-benar ada di folder 'admin/content'
              if (file_exists('admin/content/' . $_GET['page'] . '.php')) {

                // Jika file ada, maka sertakan (include) file tersebut ke dalam halaman ini
                include 'admin/content/' . $_GET['page'] . '.php';

              } else {
                // Jika file tidak ditemukan, arahkan ke halaman error
                header("Location: admin/content/misc/error.php");
                die; // Hentikan eksekusi script
              }

            } else {
              // Jika parameter 'page' tidak ada, tampilkan halaman dashboard sebagai default
              include 'admin/content/dashboard.php';
            }

            
            ?>
      <!-- batas sampe sini -->
            </div>
          </div>
        </div>
      <!-- End isi content -->
      <!-- start footer -->
     <?php include 'admin/inc/footer.php' ?>
      </div>

      <!-- Custom template | don't include it in your project! -->
    <?php include 'admin/inc/customTemp.php' ?>
      <!-- End Custom template -->
    </div>
    <?php include 'admin/inc/js.php' ?>
    <?php include 'admin/inc/script.php' ?>
  </body>
</html>
