<div class="page-content-wrapper">
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="btn-group float-right">
                        <ol class="breadcrumb hide-phone p-0 m-0">
                            <li class="breadcrumb-item"><a href="#">Laundry</a></li>
                            <li class="breadcrumb-item active">Data Pengeluaran</li>
                        </ol>
                    </div>
                    <?php 
                    // Notifikasi sukses/hapus/update
                    include 'admin/controller/alert-data-crud.php'; 
                    ?>
                    <h4 class="page-title">Data Pengeluaran</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="table-responsive">
                            <h4 class="mt-0 header-title">
                                <a href="?page=add-pengeluaran" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Tambah Pengeluaran
                                </a>
                            </h4>
                            <table id="datatable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Tanggal</th>
                                        <th>Catatan</th>
                                        <th>Pengeluaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include 'admin/controller/koneksi.php';
                                    $query = "SELECT * FROM pengeluaran ORDER BY tanggal DESC";
                                    $result = mysqli_query($connection, $query);
                                    $i = 1;
                                    while ($row = mysqli_fetch_assoc($result)) :
                                    ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $row['id']; ?></td>
                                            <td><?= date('d F Y', strtotime($row['tanggal'])); ?></td>
                                            <td><?= $row['deskripsi']; ?></td>
                                            <td>Rp. <?= number_format((float)$row['pengeluaran']); ?></td>
                                            <td>
                                                <a href="?page=add-pengeluaran&action=edit&id=<?= $row['id']; ?>" class="btn btn-warning">
                                                    <i class="fa fa-tags"></i>
                                                </a>
                                                <a href="?page=add-pengeluaran&action=delete&id=<?= $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin hapus?');">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <a href="?page=add-pengeluaran&action=view&id=<?= $row['id']; ?>" class="btn btn-primary">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
