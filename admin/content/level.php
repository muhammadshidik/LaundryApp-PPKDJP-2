<?php
include 'admin/controller/administrator-validation.php';
$queryData = mysqli_query($connection, "SELECT * FROM level ORDER BY id ASC");
?>
<div class="card shadow">
    <div class="card-header">
        <h5>Data Level</h5>
    </div>
    <div class="card-body">
        <?php include 'admin/controller/alert-data-crud.php' ?>
        <div align="left" class="button-action">
            <a href="?page=add-level" class="btn btn-primary btn-sm">Tambah Level</a>
        </div>
        <table class="table table-bordered table-striped table-hover table-responsive mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Level Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($rowData = mysqli_fetch_assoc($queryData)) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= isset($rowData['level_name']) ? $rowData['level_name'] : '-' ?></td>
                        <td>
                            <a href="?page=add-level&edit=<?php echo $rowData['id'] ?>">
                                <button class="btn btn-secondary btn-sm">Edit
                                </button>
                            </a>
                            <a onclick="return confirm ('Apakah anda yakin akan menghapus data ini?')"
                                href="?page=add-level&delete=<?php echo $rowData['id'] ?>">
                                <button class="btn btn-danger btn-sm">Hapus
                                </button>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; // End While 
                ?>
            </tbody>
        </table>
    </div>
</div>