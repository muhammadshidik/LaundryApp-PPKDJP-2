<?php
require_once 'admin/controller/connection.php';
include 'admin/controller/admin-validation.php';
include 'admin/controller/admin-operations.php';

if (isset($_GET['pickup'])) {
    $idPickup = $_GET['pickup'];
    $sql = "SELECT customer.customer_name, customer.phone, customer.address, trans_order.order_code, trans_order.order_date, trans_order.order_status, trans_order.id_customer, type_of_service.service_name, type_of_service.price, trans_order_detail.* 
    FROM trans_order_detail 
    LEFT JOIN type_of_service ON type_of_service.id = trans_order_detail.id_service 
    LEFT JOIN trans_order ON trans_order.id = trans_order_detail.id_order 
    LEFT JOIN customer ON trans_order.id_customer = customer.id 
    WHERE trans_order_detail.id_order ='$idPickup'";
    $queryDetail = mysqli_query($connection, $sql);
    $rowDetail = [];
    while ($data = mysqli_fetch_assoc($queryDetail)) {
        $rowDetail[] = $data;
    }
}

$queryCustomer = mysqli_query($connection, "SELECT * FROM customer WHERE deleted_at=0");
$currentDate = date("l, d-m-Y");
$queryService = mysqli_query($connection, "SELECT * FROM type_of_service WHERE deleted_at=0");
$order_code = dataTransactionGetNoInvoice();

if (isset($_POST['submit_transaction'])) {
    $id_order = $_POST['id_order'];
    $id_customer = $_POST['id_customer'];
    $pickup_pay = $_POST['pickup_pay'];
    $pickup_change = $_POST['pickup_change'];
    $pickup_date = date("Y-m-d");

    $insertPickup = mysqli_query($connection, "INSERT INTO trans_laundry_pickup(id_order, id_customer, pickup_date, pickup_pay, pickup_change) VALUES ('$id_order', '$id_customer', '$pickup_date', '$pickup_pay', '$pickup_change')");

    // ubah status order menjadi 1
    $updateStatus = mysqli_query($connection, "UPDATE trans_order SET order_status = 1 WHERE id = '$id_order'");
    header("location: ?pg=data-transaction&add=success");
    die;
}

$idTransPickup = $_GET['pickup'];
$queryTransPickup = mysqli_query($connection, "SELECT * FROM trans_laundry_pickup WHERE id_order='$idTransPickup'");
$rowTransPickup = mysqli_fetch_assoc($queryTransPickup);

?>

<?php if (isset($_GET['pickup'])) : ?>
    <div class="wrapper flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-sm-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 align-item-center">
                                <h5>Laundry Pickup <?= $rowDetail[0]['customer_name'] ?></h5>
                            </div>
                            <div class="col-sm-6" align="right">
                                <a class="btn btn-secondary" href="?pg=data-transaction">Back</a>
                                <a class="btn btn-success" href="print.php?id=<?= $rowTransPickup['id_order'] ?>">Print</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Transaction Data</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-responsive">
                            <tr>
                                <th>Order Code</th>
                                <td><?= $rowDetail[0]['order_code'] ?></td>
                            </tr>
                            <tr>
                                <th>Tanggal Laundry</th>
                                <td><?= $rowDetail[0]['order_date'] ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <?php
                                include 'helper.php';
                                $status = changeStatus($rowDetail[0]['order_status'])
                                ?>
                                <td><?= $status ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Customer Detail</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-responsive">
                            <tr>
                                <th>Name</th>
                                <td><?= $rowDetail[0]['customer_name'] ?></td>
                            </tr>
                            <tr>
                                <th>Phone Number</th>
                                <td><?= $rowDetail[0]['phone'] ?></td>
                            </tr>
                            <tr>
                                <th>Addres</th>
                                <td><?= $rowDetail[0]['address'] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 mt-2">
                <div class="card">
                    <div class="card-header">
                        <h5>Transaction Detail</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <table class="table table-bordered table-striped table-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Service Name</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $total = 0;
                                    foreach ($rowDetail as $key => $value):
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $value['service_name'] ?></td>
                                            <td><?= "Rp. " . number_format($value['price'], 2) ?></td>
                                            <td><?= $value['qty'] ?></td>
                                            <td><?= "Rp. " . number_format($value['subtotal'], 2) ?></td>
                                        </tr>
                                        <?php $total += $value['subtotal']; ?>
                                    <?php endforeach  ?>
                                    <tr>
                                        <td colspan="4" align="right">
                                            <strong>Total Cost</strong>
                                        </td>
                                        <td>
                                            <?= "Rp. " . number_format($total, 2) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" align="right">
                                            <strong>Paid Amount</strong>
                                        </td>
                                        <td>
                                            <?php if (mysqli_num_rows($queryTransPickup) > 0) : ?>
                                                <?= "Rp. " . number_format($rowTransPickup['pickup_pay'], 2) ?>
                                            <?php else : ?>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon1">Rp.</span>
                                                    <input type="number" name="pickup_pay" style="" class="form-control" placeholder="Input Paid Amount" value="<?= isset($_POST['pickup_pay']) ? $_POST['pickup_pay'] : '' ?>">
                                                </div>
                                            <?php endif ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" align="right">
                                            <strong>Change Amount</strong>
                                        </td>
                                        <?php
                                        if (isset($_POST['count'])) {
                                            $pickup_pay = $_POST['pickup_pay'];

                                            $pickup_change = 0;
                                            $pickup_change = $pickup_pay - $total;
                                            if ($pickup_change < 0) {
                                                $pickup_change = 0;
                                            }
                                        }
                                        ?>
                                        <td>
                                            <?php if (mysqli_num_rows($queryTransPickup) > 0) : ?>
                                                <?= "Rp. " . number_format($rowTransPickup['pickup_change'], 2) ?>
                                            <?php else : ?>
                                                <?= isset($pickup_change) ? "Rp. " . number_format($pickup_change, 2) : 'Rp. 0' ?>
                                            <?php endif ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="visually-hidden">
                                <input type="hidden" name="total" value="<?= $total ?>">
                                <input type="hidden" name="id_order" value="<?= $rowDetail[0]['id_order'] ?>">
                                <input type="hidden" name="id_customer" value="<?= $rowDetail[0]['id_customer'] ?>">
                                <input type="hidden" name="pickup_change" value="<?= $pickup_change ?>">
                            </div>
                            <?php if ($rowDetail[0]['order_status'] == 0) : ?>
                                <div class="mt-3" align="right">
                                    <button type="submit" name="count" class="btn btn-warning">Count</button>
                                    <button type="submit" name="submit_transaction" class="btn btn-primary" <?= (!isset($_POST['pickup_change']) && !isset($_POST['pickup_pay'])) ? 'disabled' : '' ?>>Submit</button>
                                </div>
                            <?php endif ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php else : ?>
    <div class="wrapper flex-grow-1 container-p-y">
        <form action="" method="post">
            <div class="row mt-3">
                <div class="col-sm-6">
                    <div class="card shadow">
                        <div class="card-body">
                            <h3 class="card-title">Add Transaction</h3>
                            <div class="row">
                                <div class="col-sm-12 mb-3">
                                    <label for="level" class="form-label">Customer:</label>
                                    <select class="form-control" name="id_customer" id="">
                                        <option value=""> -- choose customer -- </option>
                                        <?php while ($rowCustomer = mysqli_fetch_assoc($queryCustomer)) : ?>
                                            <option value="<?= $rowCustomer['id'] ?>">
                                                <?= $rowCustomer['customer_name'] ?></option>
                                        <?php endwhile ?>
                                    </select>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label for="order_code" class="form-label">Order Code:</label>
                                    <input type="text" class="form-control" id="order_code" name="order_code"
                                        value="<?= $order_code ?>" readonly>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label for="order_date" class="form-label">Order Date:</label>
                                    <input class="form-control" id="order_date" name="order_date" placeholder="Masukkan order date"
                                        value="<?= $currentDate ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card shadow">
                        <div class="card-body">
                            <h3 class="card-title">Detail Transaction</h3>
                            <div class="mb-3 row">
                                <div class="col-sm-3 d-flex align-items-center">
                                    <label for="" class="form-label">Service Package</label>
                                </div>
                                <div class="col-9">
                                    <select class="form-control" name="id_service[]" id="">
                                        <option value=""> -- choose service -- </option>
                                        <?php foreach ($rowService as $key => $value) : ?>
                                            <option value="<?= $value['id'] ?>">
                                                <?= $value['service_name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-sm-3 d-flex align-items-center">
                                    <label for="" class="form-label">Quantity</label>
                                </div>
                                <div class="col-5">
                                    <input type="number" name="qty[]" class="form-control" placeholder="Quantity">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-sm-3 d-flex align-items-center">
                                    <label for="" class="form-label">Service Package</label>
                                </div>
                                <div class="col-9">
                                    <select class="form-control" name="id_service[]" id="">
                                        <option value=""> -- choose service -- </option>
                                        <?php foreach ($rowService as $key => $value) : ?>
                                            <option value="<?= $value['id'] ?>">
                                                <?= $value['service_name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-sm-3 d-flex align-items-center">
                                    <label for="" class="form-label">Quantity</label>
                                </div>
                                <div class="col-5">
                                    <input type="number" name="qty[]" class="form-control" placeholder="Quantity">
                                </div>
                            </div>
                            <div class="">
                                <button type="submit" class="btn btn-primary" name="<?php echo isset($_GET['edit']) ? 'edit' : 'add' ?>">
                                    <?php echo isset($_GET['edit']) ? 'Edit' : 'Add' ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php endif ?>