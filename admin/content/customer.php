<?php
include 'admin/controller/administrator-validation.php';
$queryData = mysqli_query($connection, "SELECT * FROM customer ORDER BY updated_at DESC");
?>
<div class="card shadow">
    <div class="card-header">
        <h3>Data Customer</h3>
    </div>
    <div class="card-body">
        <?php include 'admin/controller/alert-data-crud.php' ?>
        <div align="right" class="button-action">
            <a href="?page=add-customer" class="btn btn-primary"><i class='bx bx-plus'>Add Customer</i></a>
        </div>
        <table class="table table-bordered table-striped table-hover table-responsive mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Customer Name</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Action</th>
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
                                <button class="btn btn-secondary btn-sm">
                                    <i class="tf-icon bx bx-edit bx-22px">Edit</i>
                                </button>
                            </a>
                            <a onclick="return confirm ('Apakah anda yakin akan menghapus data ini?')"
                                href="?page=add-customer&delete=<?php echo $rowData['id'] ?>">
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