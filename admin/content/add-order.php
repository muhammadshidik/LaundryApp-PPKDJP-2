<?php
require_once 'admin/controller/koneksi.php';
include 'admin/controller/operator-validation.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// --- LOGIKA PHP UNTUK PROSES DATA (CREATE, VIEW, DELETE) ---

// Logika untuk generate order code
$getOrderCodeQuery = mysqli_query($connection, "SELECT id FROM trans_order ORDER BY id DESC LIMIT 1");
$getorderCodeID = mysqli_fetch_assoc($getOrderCodeQuery);
$orderCodeID = (mysqli_num_rows($getOrderCodeQuery) > 0) ? $getorderCodeID['id'] + 1 : 1;
$orderCode = "LNDRY-" . date('YmdHis') . $orderCodeID;

// Logika saat form disubmit untuk MENAMBAH order
if (isset($_POST['add_order'])) {
    // var_dump($_POST); die(); // (Untuk debug, hapus jika sudah ok)

    $id_customer = $_POST['id_customer'];
    $order_code = $_POST['order_code'];
    $order_date = $_POST['order_date'];
    $order_end_date = $_POST['order_end_date'];
    $order_status = $_POST['order_status']; // <-- Nilai ini sekarang pasti terkirim
    $total_price = $_POST['total_price'];
    
    // Insert ke tabel utama
    $insert_trans_order = mysqli_query($connection, "INSERT INTO trans_order (id_customer, order_code, order_date, order_end_date, order_status, total_price) VALUES ('$id_customer', '$order_code', '$order_date', '$order_end_date', '$order_status', '$total_price')");
    $trans_order_id = mysqli_insert_id($connection);

    // Insert ke tabel detail
    if (isset($_POST['id_service'])) {
        foreach ($_POST['id_service'] as $key => $id_service) {
            $qty = $_POST['qty'][$key];
            $subtotal = $_POST['subtotal'][$key];

            mysqli_query($connection, "INSERT INTO trans_order_detail (id_order, id_service, qty, subtotal) VALUES ('$trans_order_id', '$id_service', '$qty', '$subtotal')");
        }
    }
    
    // Alihkan kembali ke halaman daftar order dengan notifikasi sukses
    header("Location:?page=order&add=success");
    die;

} else if (isset($_GET['view'])) { // Logika untuk MELIHAT detail
    $idView = $_GET['view'];
    $queryView = mysqli_query($connection, "SELECT trans_order.*, customer.customer_name, customer.phone, customer.address FROM trans_order LEFT JOIN customer ON trans_order.id_customer = customer.id WHERE trans_order.id = '$idView'");
    $rowView = mysqli_fetch_assoc($queryView);

    $orderViewID = $rowView['id'];
    $queryOrderList = mysqli_query($connection, "SELECT trans_order_detail.*, type_of_service.* FROM trans_order_detail LEFT JOIN type_of_service ON trans_order_detail.id_service = type_of_service.id WHERE trans_order_detail.id_order = '$orderViewID'");

} else if (isset($_GET['delete'])) { // Logika untuk MENGHAPUS
    $idDelete = $_GET['delete'];
    mysqli_query($connection, "DELETE FROM trans_order WHERE id='$idDelete'");
    // Detail dan pickup akan terhapus otomatis jika Anda set ON DELETE CASCADE di database
    // Jika tidak, query di bawah ini diperlukan
    // mysqli_query($connection, "DELETE FROM trans_order_detail WHERE id_order='$idDelete'");
    // mysqli_query($connection, "DELETE FROM trans_laundry_pickup WHERE id_order = '$idDelete'");
    header("Location:?page=order&delete=success");
    die;
}

// Query untuk mengisi dropdown di form
$queryService = mysqli_query($connection, "SELECT * FROM type_of_service");
$queryCustomer = mysqli_query($connection, "SELECT * FROM customer");

// --- TAMPILAN HTML (VIEW ATAU FORM) ---
?>
<?php if (isset($_GET['view'])) : ?>
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
                                <th scope="row">Kode Transaksi</th>
                                <td><?= isset($rowView['order_code']) ? $rowView['order_code'] : '-' ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Waktu di Pesan</th>
                                <td><?= isset($rowView['order_date']) ? $rowView['order_date'] : '-' ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Pesanan Selesai</th>
                                <td><?= isset($rowView['order_end_date']) ? $rowView['order_end_date'] : '-' ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Status Pemesanan</th>
                                <?php $status = getOrderStatus($rowView['order_status']) ?>
                                <td><?= $status ?></td>
                            </tr>
                              <tr>
                                <th scope="row">Catatan</th>
                                <td><?= isset($rowView['deskripsi']) ? $rowView['deskripsi'] : '-' ?></td>
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
                                <td><?= isset($rowView['customer_name']) ? $rowView['customer_name'] : '-' ?></td>
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

      <div class="card shadow mt-3">
        <div class="card-header">
            <h4>List Pemesanan</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped table-responsive table-bordered">
                <thead>
                    <tr>
                        <th>Jenis Layanan</th>
                        <th>Harga</th>
                        <th>Berat/Kg</th>
                        <th>Total Semua</th>
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
                <a href="?page=transaksi" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
    <?php else : ?>
        
    <form action="" method="post">
        <input type="hidden" name="order_status" value="0">

        <div class="card shadow">
             <div class="card-header"><h3>Tambah Pesanan</h3></div>
             <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label class="form-label">Kode Transaksi</label>
                        <input type="text" class="form-control" name="order_code" value="<?= $orderCode ?>" readonly>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="form-label">Customer Name</label>
                        <select name="id_customer" class="form-control" required>
                            <option value="">-- choose customer --</option>
                            <?php while ($rowCustomer = mysqli_fetch_assoc($queryCustomer)) : ?>
                                <option value="<?= $rowCustomer['id'] ?>"><?= $rowCustomer['customer_name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="form-label">Waktu diPesan</label>
                        <input type="date" class="form-control" name="order_date" required>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="form-label">Pemesanan Selesai</label>
                        <input type="date" class="form-control" name="order_end_date" required>
                    </div>
                </div>
             </div>
        </div>

        <div class="card shadow mt-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label class="form-label">Layanan</label>
                        <select name="id_service" class="form-control" id="selected_service">
                        <option value="">-- Pilih Jenis Layanan --</option>
                        <?php 
                        // Reset pointer query untuk looping lagi jika diperlukan di tempat lain
                        mysqli_data_seek($queryService, 0); 
                        while ($rowService = mysqli_fetch_assoc($queryService)): 
                        ?>
                            <option value="<?= $rowService['id'] ?>" data-price="<?= $rowService['price'] ?>">
                                <?= $rowService['service_name'] ?>
                            </option>
                        <?php endwhile ?>
                    </select>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="form-label">Berat/Kg</label>
                        <input type="number" class="form-control" placeholder="Enter quantity" id="selected_qty">
                    </div>
                </div>
                <hr>
                <div class="mb-3" align="right">
                    <button type="button" class="btn btn-secondary" id="add_row_order">
                        <i class="bx bx-plus"></i> Tambah ke Keranjang
                    </button>
                </div>
                <table class="table table-responsive table-bordered table-striped mb-3">
                    <thead>
                        <tr>
                            <th>Jenis Layanan</th>
                            <th>Harga</th>
                            <th>Berat/Kg</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="order_table">
                        </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" align="right"><strong>Total Semua</strong></td>
                            <td>
                                <input type="text" id="total_price_formatted" class="form-control" style="border: none; background: transparent; font-weight: bold;" readonly>
                                <input type="hidden" name="total_price" id="total_price" readonly>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div align="right">
                    <a href="?page=transaksi" class="btn btn-secondary">Back</a>
                    <button class="btn btn-primary" type="submit" name="add_order">Simpan</button>
                </div>
            </div>
        </div>
    </form>
    <?php endif?>