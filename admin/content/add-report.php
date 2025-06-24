<?php
require_once 'admin/controller/koneksi.php';
include 'admin/controller/pimpinan-validation.php';

if (isset($_GET['view'])) {
    // trans order data
    $idView = $_GET['view'];
    $queryView = mysqli_query($connection, "SELECT trans_order.*, customer.customer_name, customer.phone, customer.address FROM trans_order LEFT JOIN customer ON trans_order.id_customer = customer.id WHERE trans_order.id = '$idView'");
    $rowView = mysqli_fetch_assoc($queryView);

    // trans order detail data
    $orderViewID = $rowView['id'];
    $queryOrderList = mysqli_query($connection, "SELECT trans_order_detail.*, type_of_service.* FROM trans_order_detail LEFT JOIN type_of_service ON trans_order_detail.id_service = type_of_service.id WHERE trans_order_detail.id_order = '$orderViewID'");

    if ($rowView['order_status'] == 1) {
        $queryViewPickup = mysqli_query($connection, "SELECT * FROM trans_laundry_pickup WHERE id_order = '$orderViewID'");
        $rowViewPickup = mysqli_fetch_assoc($queryViewPickup);
    } else if (isset($_POST['pickup'])) {
        $id_order = $_GET['view'];
        $id_customer = $rowView['id_customer'];
        $pickup_date = $_POST['pickup_date'];
        $pickup_pay = $_POST['pickup_pay'];
        $pickup_change = $_POST['pickup_change'];

        $queryInsertPickup = mysqli_query($connection, "INSERT INTO trans_laundry_pickup (id_order, id_customer, pickup_date, pickup_pay, pickup_change) VALUES ('$id_order', '$id_customer', '$pickup_date', '$pickup_pay', '$pickup_change')");

        $order_status = $_POST['order_status'];
        $queryUpdateOrderStatus = mysqli_query($connection, "UPDATE trans_order SET order_status = '$order_status' WHERE id = '$id_order'");

        header("Location:?page=pickup&pickup=success");
        die;
    }
} else if (isset($_GET['delete'])) {
    $idDelete = $_GET['delete'];
    $queryDelete = mysqli_query($connection, "DELETE FROM trans_order WHERE id='$idDelete'");
    $queryDeleteDetail = mysqli_query($connection, "DELETE FROM trans_order_detail WHERE id_order='$idDelete'");
    $queryDeletePickup = mysqli_query($connection, "DELETE FROM trans_laundry_pickup WHERE id_order = '$idDelete'");
    header("Location:?page=pickup&delete=success");
    die;
}


$queryService = mysqli_query($connection, "SELECT * FROM type_of_service");
$queryCustomer = mysqli_query($connection,  "SELECT * FROM customer");

?>


<div class="row">
    <div class="col-sm-6">
        <div class="card shadow">
            <div class="card-header">
                <h4>Data Order</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-responsive">
                    <tbody>
                        <tr>
                            <th scope="row">Order Code</th>
                            <td><?= isset($rowView['order_code']) ? $rowView['order_code'] : '-' ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Order Start Date</th>
                            <td><?= isset($rowView['order_date']) ? $rowView['order_date'] : '-' ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Order End Date</th>
                            <td><?= isset($rowView['order_end_date']) ? $rowView['order_end_date'] : '-' ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Order Status</th>
                            <?php $status = getOrderStatus($rowView['order_status']) ?>
                            <td><?= $status ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card shadow">
            <div class="card-header">
                <h4>Data Customer</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-responsive">
                    <tbody>
                        <tr>
                            <th scope="row">Customer Name</th>
                            <td><?= isset($rowView['customer_name']) ? $rowView['customer_name'] : '-' ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Phone Number</th>
                            <td><?= isset($rowView['phone']) ? $rowView['phone'] : '-' ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Address</th>
                            <td><?= isset($rowView['address']) ? $rowView['address'] : '-' ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<form action="" method="post">
    <div class="card shadow mt-3">
        <div class="card-header">
            <h4>Order List</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Pickup Date</label>
                        <input type="date" class="form-control" name="pickup_date"
                            value="<?= $rowView['order_status'] == 1 ? $rowViewPickup['pickup_date'] : '' ?>"
                            <?= $rowView['order_status'] == 1 ? 'readonly' : '' ?> required>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-responsive table-bordered">
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($rowOrderList = mysqli_fetch_assoc($queryOrderList)):
                    ?>
                        <tr>
                            <td><?= isset($rowOrderList['service_name']) ? $rowOrderList['service_name'] : '-' ?></td>
                            <td><?= isset($rowOrderList['price']) ? 'Rp ' . number_format($rowOrderList['price'], 2, ',', '.') : '-' ?>
                            </td>
                            <td><?= isset($rowOrderList['qty']) ? $rowOrderList['qty'] : '-' ?></td>
                            <td><?= isset($rowOrderList['subtotal']) ? 'Rp ' . number_format($rowOrderList['subtotal'], 2, ',', '.') : '-' ?>
                            </td>
                        </tr>
                    <?php
                    endwhile;
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" align="right"><strong>Total</strong></td>
                        <td><?= isset($rowView['total_price']) ? 'Rp ' . number_format($rowView['total_price'], 2, ',', '.') : '-' ?>
                        </td>
                        <input type="hidden" id="total_price_pickup" value="<?= $rowView['total_price'] ?>">
                    </tr>
                    <?php if ($rowView['order_status'] == 1): ?>
                        <tr>
                            <td colspan="3" align="right"><strong>Amount Pay</strong></td>
                            <td><?= 'Rp. ' . number_format($rowView['order_pay'], 2, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" align="right"><strong>Amount Change</strong></td>
                            <td><?= 'Rp ' . number_format($rowView['order_change'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endif ?>
                </tfoot>
            </table>
            <input type="hidden" name="order_status" value="1">
            <div class="mt-3 gap-3" align="right">
                <a href="?page=report" class="btn btn-secondary">Back</a>
                <?php if ($rowView['order_status'] == 1): ?>
                    <a href="content/misc/print.php?order=<?= $_GET['view'] ?>" target="_blank"
                        class="btn btn-primary">Print</a>
                <?php endif ?>
            </div>
        </div>
    </div>
</form>