<?php
include 'admin/controller/administrator-validation.php';
$queryData = mysqli_query($connection, "SELECT * FROM type_of_service ORDER BY updated_at DESC");
?>
<div class="card shadow">
    <div class="card-header">
        <h5>Data Service</h5>
    </div>
    <div class="card-body">
        <?php include 'admin/controller/alert-data-crud.php' ?>
        <div align="right" class="button-action">
            <a href="?page=add-service" class="btn btn-primary btn-sm">Tambah Service</a>
        </div>
        <table class="table table-bordered table-striped table-hover table-responsive mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Service</th>
                    <th>Harga/Kg</th>
                    <th>Deskripsi Service</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($rowData = mysqli_fetch_assoc($queryData)) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= isset($rowData['service_name']) ? $rowData['service_name'] : '-' ?></td>
                        <td><?= isset($rowData['price']) ? 'Rp ' . number_format($rowData['price'], 2, ',', '.') : '-' ?>
                        </td>
                        <td><?= isset($rowData['description']) ? $rowData['description'] : '-' ?></td>
                        <td>
                            <a href="?page=add-service&edit=<?php echo $rowData['id'] ?>">
                                <button class="btn btn-secondary btn-sm">
                                Edit
                                </button>
                            </a>
                            <a onclick="return confirm ('Apakah anda yakin akan menghapus data ini?')"
                                href="?page=add-service&delete=<?php echo $rowData['id'] ?>">
                                <button class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; // End While 
                ?>
            </tbody>
        </table>
        <div class="mt-4" align="right">

        </div>
    </div>
</div>