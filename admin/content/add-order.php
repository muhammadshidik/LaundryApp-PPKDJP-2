<?php
require_once 'admin/controller/koneksi.php';
include 'admin/controller/operator-validation.php';

// get order code
$getOrderCodeQuery = mysqli_query($connection, "SELECT id FROM trans_order ORDER BY id DESC LIMIT 1");
$getorderCodeID = mysqli_fetch_assoc($getOrderCodeQuery);
if (mysqli_num_rows($getOrderCodeQuery) > 0) {
    $orderCodeID = $getorderCodeID['id'] + 1;
} else {
    $orderCodeID = 0;
}
$orderCode = "LNDRY-" . date('YmdHis') . $orderCodeID + 1;

if (isset($_POST['add_order'])) {
    $id_customer = $_POST['id_customer'];
    $order_code = $_POST['order_code'];
    $order_date = $_POST['order_date'];
    $order_end_date = $_POST['order_end_date'];
    $order_status = $_POST['order_status'];
    $total_price = $_POST['total_price'];
    $id_service = $_POST['id_service'];

    $insert_trans_order = mysqli_query($connection, "INSERT INTO trans_order (id_customer, order_code, order_date, order_end_date, order_status, total_price) VALUES ('$id_customer', '$order_code', '$order_date', '$order_end_date', '$order_status', '$total_price')");
    $trans_order_id = mysqli_insert_id($connection);

    foreach ($id_service as $key => $value) {
        // $id_service = array_filter($_POST['id_service']);
        // $qty = array_filter($_POST['qty']);
        $id_service = $_POST['id_service'][$key];
        $qty = $_POST['qty'][$key];
        $subtotal = $_POST['subtotal'][$key];

        $insert_trans_order_detail = mysqli_query($connection, "INSERT INTO trans_order_detail (id_order, id_service, qty, subtotal) VALUES ('$trans_order_id', '$id_service', '$qty', '$subtotal')");
    }

    header("Location:?page=order&add=success");
    die;
} else if (isset($_GET['view'])) {
    // trans order data
    $idView = $_GET['view'];
    $queryView = mysqli_query($connection, "SELECT trans_order.*, customer.customer_name, customer.phone, customer.address FROM trans_order LEFT JOIN customer ON trans_order.id_customer = customer.id WHERE trans_order.id = '$idView'");
    $rowView = mysqli_fetch_assoc($queryView);

    // trans order detail data
    $orderViewID = $rowView['id'];
    $queryOrderList = mysqli_query($connection, "SELECT trans_order_detail.*, type_of_service.* FROM trans_order_detail LEFT JOIN type_of_service ON trans_order_detail.id_service = type_of_service.id WHERE trans_order_detail.id_order = '$orderViewID'");
} else if (isset($_GET['delete'])) {
    $idDelete = $_GET['delete'];
    $queryDelete = mysqli_query($connection, "DELETE FROM trans_order WHERE id='$idDelete'");
    $queryDeleteDetail = mysqli_query($connection, "DELETE FROM trans_order_detail WHERE id_order='$idDelete'");
    $queryDeletePickup = mysqli_query($connection, "DELETE FROM trans_laundry_pickup WHERE id_order = '$idDelete'");
    header("Location:?page=order&delete=success");
    die;
}


$queryService = mysqli_query($connection, "SELECT * FROM type_of_service");
$queryCustomer = mysqli_query($connection,  "SELECT * FROM customer");
?>

<?php if (isset($_GET['view'])) : ?>
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
                                <td><?= isset($rowView['customer_name']) ? $rowView['customer_name'] : '-' ?></td>
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
    <div class="card shadow mt-3">
        <div class="card-header">
            <h4>Order List</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped table-responsive table-bordered">
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th>Price</th>
                        <th>Quantity (per gram)</th>
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
                        <td><?= 'Rp ' . number_format($rowView['total_price'],  2, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
            <div class="mt-3 gap-3" align="right">
                <a href="?page=order" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
<?php else : ?>
    <form action="" method="post">
        <div class="card shadow">
            <div class="card-header">
                <h3>Add Order</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label for="" class="form-label">Order Code</label>
                        <input type="text" class="form-control" id="" name="order_code" placeholder="Enter phone number"
                            value="<?= $orderCode ?>" readonly>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label for="" class="form-label">Customer Name</label>
                        <select name="id_customer" id="" class="form-control">
                            <option value="">-- choose customer --</option>
                            <?php while ($rowCustomer = mysqli_fetch_assoc($queryCustomer)) : ?>
                                <option value="<?= $rowCustomer['id'] ?>"><?= $rowCustomer['customer_name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label for="" class="form-label">Order Date</label>
                        <input type="date" class="form-control" name="order_date">
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label for="" class="form-label">Order Date</label>
                        <input type="date" class="form-control" name="order_end_date">
                    </div>

                </div>
            </div>
        </div>

        <div class="card shadow mt-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label for="" class="form-label">Service</label>
                        <select name="id_service" class="form-control" id="selected_service">
                            <option value="">-- choose service --</option>
                            <?php while ($rowService = mysqli_fetch_assoc($queryService)): ?>
                                <option value="<?= $rowService['id'] ?>"><?= $rowService['service_name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </div>
                    <input type="hidden" id="price">
                    <div class="col-sm-6 mb-3">
                        <label for="" class="form-label">Quantity (per gram)</label>
                        <input type="number" class="form-control" name="qty" placeholder="Enter quantity" id="selected_qty">
                    </div>
                </div>
                <hr>
                <div class="mb-3" align="right">
                    <button class="btn btn-secondary" id="add_row_order">
                        <i class="bx bx-plus"></i>
                    </button>
                </div>
                <table class="table table-responsive table-bordered table-striped mb-3">
                    <thead>
                        <tr>
                            <th>Service Name</th>
                            <th>Price</th>
                            <th>Quantity (per gram)</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="order_table">
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" align="right"><strong>Total Price</strong></td>
                            <td>
                                <input type="text" id="total_price_formatted" style="border: none; outline: none;"
                                    class="form-control" readonly>
                                <input type="hidden" name="total_price" id="total_price" readonly>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <input type="hidden" name="order_status" value="0">
                <div align="right">
                    <a href="?page=order" class="btn btn-secondary">Back</a>
                    <button class="btn btn-primary" type="submit" name="add_order">Add</button>
                </div>
            </div>
        </div>
    </form>
<?php endif ?>