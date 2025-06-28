<?php
include __DIR__ . '/../controller/koneksi.php';

$id_pengeluaran = $_GET['id'] ?? '';
$query = "SELECT * FROM pengeluaran WHERE id_pengeluaran = '$id_pengeluaran'";
$result = mysqli_query($connection, $query);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pengeluaran <?= htmlspecialchars($row['id_pengeluaran']); ?></title>

  <!-- Bootstrap 5.3.7 CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

  <style>
    body {
      padding: 30px;
      font-family: 'Segoe UI', sans-serif;
    }
    h3 {
      margin-bottom: 25px;
    }
    table th {
      background-color: #f8f9fa;
    }
  </style>
</head>
<body onload="window.print()">
  <div class="container">

    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>No</th>
          <th>ID</th>
          <th>Tanggal Pengeluaran</th>
          <th>Catatan</th>
          <th>Pengeluaran</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td><?= htmlspecialchars($row['id_pengeluaran']); ?></td>
          <td><?= date('d-m-Y', strtotime($row['tgl_pengeluaran'])); ?></td>
          <td><?= htmlspecialchars($row['catatan']); ?></td>
          <td>Rp. <?= number_format((float)$row['pengeluaran']); ?></td>
        </tr>
        <tr class="fw-bold text-center table-warning">
          <td colspan="4">TOTAL PENGELUARAN</td>
          <td class="text-end">Rp. <?= number_format((float)$row['pengeluaran']); ?></td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Popper & Bootstrap JS (dalam body) -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
</body>
</html>
