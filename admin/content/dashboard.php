<?php
require_once 'admin/controller/koneksi.php';

$idLogin = $_SESSION['id'];
$queryLogin = mysqli_query($connection, "SELECT user.*, level.level_name FROM user 
    LEFT JOIN level ON user.id_level = level.id 
    WHERE user.id = '$idLogin'");
$tampilusers = mysqli_fetch_assoc($queryLogin);

// Total pelanggan
$dataCustomer = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) AS total FROM customer"));
$id_customer = $dataCustomer['total'];

// Total user
$rowUser = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) AS total FROM user"));

// Total transaksi
$dataTransaksi = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) AS total FROM trans_order"));
$insert_trans_order = $dataTransaksi['total'];


?>

<div class="container py-4">
  <!-- Judul dan Breadcrumb -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="fw-bold text-primary">👕 Laundry Dashboard</h2>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Ringkasan -->
  <div class="row g-4">

    <!-- Pelanggan -->
    <div class="col-md-6 col-xl-3">
      <div class="card text-white shadow border-0" style="background: linear-gradient(135deg, #74ebd5, #ACB6E5);">
        <div class="card-body text-center">
          <i class="mdi mdi-account-multiple-plus fs-1 mb-2"></i>
          <h3 class="fw-bold"><?= $id_customer; ?></h3>
          <p class="mb-2">Total Pelanggan</p>
          <a href="?page=pelanggan" class="btn btn-light btn-sm fw-semibold">Lihat Detail</a>
        </div>
      </div>
    </div>

    <!-- User -->
    <?php if ($tampilusers['id_level'] == 1) : ?>
      <div class="col-md-6 col-xl-3">
        <div class="card text-white shadow border-0" style="background: linear-gradient(135deg, #667eea, #764ba2);">
          <div class="card-body text-center">
            <i class="mdi mdi-account-key fs-1 mb-2"></i>
            <h3 class="fw-bold"><?= $rowUser['total']; ?></h3>
            <p class="mb-2">Total User</p>
            <a href="?page=users" class="btn btn-light btn-sm fw-semibold">Lihat Detail</a>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Transaksi -->
    <div class="col-md-6 col-xl-3">
      <div class="card text-white shadow border-0" style="background: linear-gradient(135deg, #11998e, #38ef7d);">
        <div class="card-body text-center">
          <i class="mdi mdi-basket fs-1 mb-2"></i>
          <h3 class="fw-bold"><?= $insert_trans_order; ?></h3>
          <p class="mb-2">Total Transaksi</p>
          <a href="?page=laundry" class="btn btn-light btn-sm fw-semibold">Lihat Detail</a>
        </div>
      </div>
    </div>

    <!-- Pengeluaran -->
    <div class="col-md-6 col-xl-3">
      <div class="card text-white shadow border-0" style="background: linear-gradient(135deg, #f7971e, #ffd200);">
        <div class="card-body text-center">
          <i class="mdi mdi-cash fs-1 mb-2"></i>
          <h4 class="fw-bold">Rp <?= $jmlpengeluaran; ?></h4>
          <p class="mb-2">Total Pengeluaran</p>
          <a href="?page=pengeluaran" class="btn btn-light btn-sm fw-semibold">Lihat Detail</a>
        </div>
      </div>
    </div>

  </div>

  <!-- Info User Login -->
  <div class="card mt-5 shadow border-0">
    <div class="row g-0 align-items-center">
      <div class="col-md-4">
        <img src="admin/img/profile_picture/<?= $tampilusers['profile_picture'] ?: 'default.png'; ?>" class="img-fluid rounded-start" alt="Foto Profil">
      </div>
      <div class="col-md-8">
        <div class="card-body">
          <h5 class="card-title mb-3 fw-semibold">👤 Informasi Login</h5>
          <ul class="list-unstyled mb-2">
            <li><strong>Username:</strong> <?= $tampilusers['username']; ?></li>
            <li><strong>Nama:</strong> <?= $tampilusers['username']; ?></li>
            <li><strong>Jabatan:</strong> <?= $tampilusers['level_name']; ?></li>
          </ul>
          <small class="text-muted">Login pada: <?= $_SESSION['tanggal']; ?></small>
        </div>
      </div>
    </div>
  </div>
</div>