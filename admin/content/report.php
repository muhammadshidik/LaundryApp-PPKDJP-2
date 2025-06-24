<?php
require_once 'admin/controller/koneksi.php';
include 'admin/controller/pimpinan-validation.php';

$order_date_start = isset($_GET['order_date_start']) ? $_GET['order_date_start'] : '';
$order_date_end = isset($_GET['order_date_end']) ? $_GET['order_date_end'] : '';
$order_status = isset($_GET['order_status']) ? $_GET['order_status'] : '';

$sql = "SELECT trans_order.*, customer.customer_name, trans_laundry_pickup.pickup_date, trans_laundry_pickup.pickup_date 
FROM trans_order 
LEFT JOIN customer ON trans_order.id_customer = customer.id 
LEFT JOIN trans_laundry_pickup ON trans_order.id = trans_laundry_pickup.id_order
WHERE trans_order.id >= 0";

if ($order_status != '') {
    $sql .= " AND trans_order.order_status = '$order_status'";
}
if ($order_date_start != '') {
    $sql .= " AND trans_order.order_date >= '$order_date_start'";
}
if ($order_date_end != '') {
    $sql .= " AND trans_order.order_date <= '$order_date_end'";
}


$sql .= " ORDER BY trans_order.id DESC";
$queryData = mysqli_query($connection, $sql);


if (isset($_GET['clear'])) {
    header("Location: ?page=report");
}
?>
<div class="card shadow">
    <div class="card-header">
        <h3>Data Report</h3>
    </div>
    <div class="card-body">
        <form method="get">
            <div class="row">
                <div class="col-sm-3">
                    <label class="form-label">Order Date Start</label>
                    <input type="date" class="form-control" name="order_date_start"
                        value="<?= isset($_GET['order_date_start']) ? $_GET['order_date_start'] : '' ?>">
                </div>
                <div class="col-sm-3">
                    <label class="form-label">Order Date End</label>
                    <input type="date" class="form-control" name="order_date_end"
                        value="<?= isset($_GET['order_date_end']) ? $_GET['order_date_end'] : '' ?>">
                </div>
                <div class="col-sm-3">
                    <label class="form-label">Order Status</label>
                    <select name="order_status" id="" class="form-control">
                        <option value=""> All </option>
                        <option value="0"
                            <?= isset($_GET['order_status']) && ($_GET['order_status'] == 0) ? 'selected' : '' ?>>New
                        </option>
                        <option value="1"
                            <?= isset($_GET['order_status']) && ($_GET['order_status'] == 1) ? 'selected' : '' ?>>Picked
                            Up
                        </option>
                    </select>
                </div>
                <input type="hidden" name="page" value="report">
                <div class="col-sm-3 mt-auto">
                    <button class="btn btn-primary" name="fiter">Filter</button>
                    <button class="btn btn-secondary" name="clear">Clear</button>
                </div>
            </div>
        </form>
        <hr>
        <table class="table table-bordered table-striped table-hover table-responsive mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Order Code</th>
                    <th>Customer Name</th>
                    <th>Order Start Date</th>
                    <th>Order End Date</th>
                    <th>Pickup Date </th>
                    <th>Order Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($rowData = mysqli_fetch_assoc($queryData)) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= isset($rowData['order_code']) ? $rowData['order_code'] : '-' ?></td>
                        <td><?= isset($rowData['customer_name']) ? $rowData['customer_name'] : '-' ?></td>
                        <td><?= isset($rowData['order_date']) ? $rowData['order_date'] : '-' ?></td>
                        <td><?= isset($rowData['order_end_date']) ? $rowData['order_end_date'] : '-' ?></td>
                        <td><?= isset($rowData['pickup_date']) ? $rowData['pickup_date'] : '-' ?></td>
                        <?php $statusOrder = getOrderStatus($rowData['order_status']) ?>
                        <td><?= $statusOrder ?></td>
                        <td align="right">
                            <?php if ($rowData['order_status'] == 1): ?>
                                <a href="admin/content/misc/print.php?order=<?php echo $rowData['id'] ?>" target="_blank">
                                    <button class="btn btn-secondary btn-sm">
                                        <i class="tf-icon bx bx-printer bx-22px">Print</i>
                                    </button>
                                </a>
                            <?php endif ?>
                            <a href="?page=add-report&view=<?php echo $rowData['id'] ?>">
                                <button class="btn btn-secondary btn-sm">
                                    <i class="tf-icon bx bx-show bx-22px">View</i>
                                </button>
                            </a>
                            <a onclick="return confirm ('Apakah anda yakin akan menghapus data ini?')"
                                href="?page=add-report&delete=<?php echo $rowData['id'] ?>">
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