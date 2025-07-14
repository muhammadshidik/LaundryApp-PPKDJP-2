<?php
include 'admin/controller/administrator-validation.php';
$queryData = mysqli_query($connection, "SELECT * FROM customer ORDER BY updated_at DESC");
?>
<div class="card shadow">
    <div class="card-header">
        <h5>Data Pelanggan</h5>
    </div>
    <div class="card-body">
        <?php include 'admin/controller/alert-data-crud.php' ?>
        <div align="right" class="button-action">
            <a href="?page=add-customer" class="btn btn-primary btn-sm">Tambah Pelanggan</a>
        </div>
        <table class="table table-bordered table-striped table-hover table-responsive mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Customer</th>
                    <th>No. Handphone</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($rowData = mysqli_fetch_assoc($queryData)) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= isset($rowData['customer_name']) ? $rowData['customer_name'] : '-' ?></td>
                        <td><?= isset($rowData['phone']) ? $rowData['phone'] : '-' ?></td>
                        <td><?= isset($rowData['address']) ? $rowData['address'] : '-' ?></td>
                        <td>
                            <a href="?page=add-customer&edit=<?php echo $rowData['id'] ?>">
                                <button class="btn btn-secondary btn-sm">Edit
                                </button>
                            </a>
                            <a onclick="return confirm ('Apakah anda yakin akan menghapus data ini?')"
                                href="?page=add-customer&delete=<?php echo $rowData['id'] ?>">
                                <button class="btn btn-danger btn-sm">Hapus
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