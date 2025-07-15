<?php
require_once 'admin/controller/koneksi.php';
include 'admin/controller/operator-validation.php';

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

        $queryInsertPickup = mysqli_query($connection, "INSERT INTO trans_laundry_pickup (id_order, id_customer, pickup_date) VALUES ('$id_order', '$id_customer', '$pickup_date')");

        $order_status = $_POST['order_status'];
        $queryUpdateOrderStatus = mysqli_query($connection, "UPDATE trans_order SET order_status = '$order_status', order_pay='$pickup_pay', order_change='$pickup_change' WHERE id = '$id_order'");

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
                <h4>Data Pemesanan</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-responsive">
                    <tbody>
                        <tr>
                            <th scope="row">Waktu Pemesanan</th>
                            <td><?= isset($rowView['order_date']) ? $rowView['order_date'] : '-' ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Pesanan Selesai</th>
                            <td><?= isset($rowView['order_end_date']) ? $rowView['order_end_date'] : '-' ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Status Pesanan</th>
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
                <h4>Data Pelanggan</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-responsive">
                    <tbody>
                        <tr>
                            <th scope="row">Nama Pelanggan</th>
                            <td><?= isset($rowView['customer_name']) ? $rowView['customer_name'] : '-' ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">No. Telepon</th>
                            <td><?= isset($rowView['phone']) ? $rowView['phone'] : '-' ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Alamat</th>
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
            <h4>List Pemesanan</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Tanggal Pengambilan Barang</label>
                        <input type="date" class="form-control" name="pickup_date"
                            value="<?= $rowView['order_status'] == 1 ? $rowViewPickup['pickup_date'] : '' ?>"
                            <?= $rowView['order_status'] == 1 ? 'readonly' : '' ?> required>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-responsive table-bordered">
                <thead>
                    <tr>
                        <th>Jenis Layanan</th>
                        <th>Harga</th>
                        <th>Berat/kg</th>
                        <th>Total Semua </th>
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
                    <tr>
                        <td colspan="3" align="right"><strong>Tagihan Yang Dibayar</strong></td>
                        <?php if ($rowView['order_status'] == 0): ?>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">Rp</span>
                                    <input type="number" name="pickup_pay" id="pickup_pay" class="form-control"
                                        placeholder="Masukkan Jumlah Uang Yang Dibayar" required>
                                </div>
                            </td>
                        <?php elseif ($rowView['order_status'] == 1) : ?>
                            <td><?= 'Rp. ' . number_format($rowView['order_pay'], 2, ',', '.') ?></td>
                        <?php endif ?>
                    </tr>
                    <tr>
                        <td colspan="3" align="right"><strong>Kembalian</strong></td>
                        <?php if ($rowView['order_status'] == 0): ?>
                            <td>
                                <input type="text" class="form-control" id="pickup_change_formatted"
                                    style="border: none; outline: none;" readonly>
                                <input type="hidden" name="pickup_change" id="pickup_change" readonly>
                            </td>
                        <?php elseif ($rowView['order_status'] == 1) : ?>
                            <td><?= 'Rp ' . number_format($rowView['order_change'], 2, ',', '.') ?></td>
                        <?php endif  ?>
                    </tr>
                </tfoot>
            </table>
            <input type="hidden" name="order_status" value="1">
            <div class="mt-3 gap-3" align="right">
                <a href="?page=add-transaksi" class="btn btn-secondary">Kembali</a>
                <?php if ($rowView['order_status'] == 0): ?>
                    <button class="btn btn-primary" name="pickup" type="submit">Bayar</button>
                <?php elseif ($rowView['order_status'] == 1): ?>
                    <a href="admin/content/misc/print.php?order=<?= $_GET['view'] ?>" target="_blank"
                        class="btn btn-primary">Print</a>
                <?php endif ?>
            </div>
        </div>
    </div>
</form>