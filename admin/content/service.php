<?php
include 'admin/controller/administrator-validation.php';
$queryData = mysqli_query($connection, "SELECT * FROM type_of_service ORDER BY updated_at DESC");
?>
<div class="card shadow">
    <div class="card-header">
        <h3>Data Service</h3>
    </div>
    <div class="card-body">
        <?php include 'admin/controller/alert-data-crud.php' ?>
        <div align="right" class="button-action">
            <a href="?page=add-service" class="btn btn-primary btn-sm"><i class='bx bx-plus'>Add Service</i></a>
        </div>
        <table class="table table-bordered table-striped table-hover table-responsive mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Service Name</th>
                    <th>Price/Kg</th>
                    <th>Description</th>
                    <th>Action</th>
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
                                    <i class="tf-icon bx bx-edit bx-22px">Edit</i>
                                </button>
                            </a>
                            <a onclick="return confirm ('Apakah anda yakin akan menghapus data ini?')"
                                href="?page=add-service&delete=<?php echo $rowData['id'] ?>">
                                <button class="btn btn-danger btn-sm">
                                    <i class="tf-icon bx bx-trash bx-22px">Delete</i>
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