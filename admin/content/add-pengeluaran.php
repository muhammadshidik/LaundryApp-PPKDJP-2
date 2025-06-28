<?php
include 'admin/controller/koneksi.php';

// Tangkap parameter dari URL
$action = $_GET['action'] ?? 'add';
$id_pengeluaran = $_GET['id'] ?? null;

// Default data form
$data = [
    'id_pengeluaran'   => '',
    'tgl_pengeluaran'  => '',
    'catatan'          => '',
    'pengeluaran'      => ''
];

// Ambil data jika bukan aksi tambah
if ($id_pengeluaran && in_array($action, ['edit', 'view', 'delete'])) {
    $query = mysqli_query($connection, "SELECT * FROM pengeluaran WHERE id_pengeluaran = '$id_pengeluaran'");

    if (!$query) {
        echo "<script>alert('Query error: " . mysqli_error($connection) . "'); window.location='?page=pengeluaran';</script>";
        exit;
    }

    $dataRow = mysqli_fetch_assoc($query);

    if (!$dataRow) {
        echo "<script>alert('Data tidak ditemukan'); window.location='?page=pengeluaran';</script>";
        exit;
    }

    $data = array_merge($data, $dataRow); // merge hasil query ke default
}

// Hapus data jika action = delete
if ($action === 'delete') {
    mysqli_query($connection, "DELETE FROM pengeluaran WHERE id_pengeluaran = '$id_pengeluaran'");
    echo "<script>window.location='?page=pengeluaran&delete=success';</script>";
    exit;
}

// Simpan data jika POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tgl_pengeluaran'];
    $catatan = $_POST['catatan'];
    $jumlah = $_POST['pengeluaran'];

    // Generate ID otomatis hanya untuk tambah
    if ($action === 'add') {
        $q = mysqli_query($connection, "SELECT MAX(RIGHT(id_pengeluaran, 4)) AS kd_max FROM pengeluaran");
        $kd = "0001";
        if (mysqli_num_rows($q) > 0) {
            $result = mysqli_fetch_assoc($q);
            $tmp = ((int)$result['kd_max']) + 1;
            $kd = sprintf("%04s", $tmp);
        }
        $id_pengeluaran = 'PG-' . $kd;
    }

    if ($action === 'edit') {
        $query = "UPDATE pengeluaran SET tgl_pengeluaran='$tanggal', catatan='$catatan', pengeluaran='$jumlah' WHERE id_pengeluaran='$id_pengeluaran'";
    } else {
        $query = "INSERT INTO pengeluaran (id_pengeluaran, tgl_pengeluaran, catatan, pengeluaran) 
                  VALUES ('$id_pengeluaran', '$tanggal', '$catatan', '$jumlah')";
    }

    if (mysqli_query($connection, $query)) {
        $redirect = $action === 'edit' ? 'edit=success' : 'add=success';
        echo "<script>window.location='?page=pengeluaran&$redirect';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data');</script>";
    }
}
?>

<div class="container">
    <div class="card mt-4">
        <div class="card-body">
            <h4><?= ucfirst($action) ?> Data Pengeluaran</h4>

            <?php if ($action !== 'view'): ?>
                <!-- Form Tambah / Edit -->
                <form method="POST">
                    <?php if ($action === 'edit'): ?>
                        <div class="form-group">
                            <label>ID Pengeluaran</label>
                            <input type="text" name="id_pengeluaran" class="form-control" value="<?= $id_pengeluaran; ?>" readonly>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label>Tanggal Pengeluaran</label>
                        <input type="date" name="tgl_pengeluaran" class="form-control"
                               value="<?= htmlspecialchars($data['tgl_pengeluaran']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea name="catatan" class="form-control"><?= htmlspecialchars($data['catatan']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Pengeluaran</label>
                        <input type="number" name="pengeluaran" class="form-control"
                               value="<?= htmlspecialchars($data['pengeluaran']); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-success"><?= $action === 'edit' ? 'Update' : 'Simpan'; ?></button>
                    <a href="?page=pengeluaran" class="btn btn-secondary">Kembali</a>
                </form>

            <?php else: ?>
                <!-- Mode View/Detail -->
                <?php if (!empty($data['id_pengeluaran'])): ?>
                    <div class="page-content-wrapper">
                        <div class="container-fluid">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="page-title-box">
                                        <div class="btn-group float-right">
                                            <ol class="breadcrumb hide-phone p-0 m-0">
                                                <li class="breadcrumb-item"><a href="#">Laundry</a></li>
                                                <li class="breadcrumb-item active">Detail Pengeluaran Laundry</li>
                                            </ol>
                                        </div>
                                        <h4 class="page-title">Detail Pengeluaran Laundry</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <p><b>Tanggal Lihat:</b> <?= date('d-m-Y'); ?></p>

                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
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
                                                        <td><?= htmlspecialchars($data['id_pengeluaran']); ?></td>
                                                        <td><?= date('d-m-Y', strtotime($data['tgl_pengeluaran'])); ?></td>
                                                        <td><?= htmlspecialchars($data['catatan']); ?></td>
                                                        <td>Rp. <?= number_format((float)$data['pengeluaran']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="4" class="text-center">TOTAL PENGELUARAN</th>
                                                        <th>Rp. <?= number_format((float)$data['pengeluaran']); ?></th>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <a href="admin/content/cetak_pengeluaran.php?id=<?= $data['id_pengeluaran']; ?>" class="btn btn-primary" target="_blank">Cetak Pengeluaran</a>
                                                <a href="?page=pengeluaran" class="btn btn-secondary">Kembali</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning mt-3">Data tidak ditemukan atau ID tidak valid.</div>
                    <a href="?page=pengeluaran" class="btn btn-secondary">Kembali</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
