<?php
require_once 'admin/controller/koneksi.php';
include 'admin/controller/operator-validation.php';

$queryData = mysqli_query($connection, "SELECT trans_order.*, customer.customer_name, trans_laundry_pickup.pickup_date FROM trans_order LEFT JOIN customer ON trans_order.id_customer = customer.id LEFT JOIN trans_laundry_pickup ON trans_order.id = trans_laundry_pickup.id_order ORDER BY trans_order.order_status DESC, trans_order.updated_at DESC")
?>
<div class="card shadow">
    <div class="card-header">
        <h3>Data Pengambilan Barang</h3>
    </div>
    <div class="card-body">
        <?php include 'admin/controller/alert-data-crud.php' ?>
        <table class="table table-bordered table-striped table-hover table-responsive mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Transaksi</th>
                    <th>Nama Pelanggan</th>
                    <th>Waktu diPesan</th>
                    <th>Pesanan Selesai</th>
                    <th>Waktu Pengambilan Barang</th>
                    <th>Status Pesanan</th>
                    <th>Aksi</th>
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
                            <a href="?page=add-pickup&view=<?php echo $rowData['id'] ?>">
                                <button class="btn btn-secondary btn-sm">
                                    <?php if ($rowData['order_status'] == 0) : ?>
                                       Info Detail & Pembayaran
                                    <?php elseif ($rowData['order_status'] == 1) : ?>
                                        Info Detail
                                    <?php endif ?>
                                </button>
                            </a>
                            <?php if ($rowData['order_status'] == 1) : ?>
                                <a href="admin/content/misc/print.php?order=<?= $rowData['id'] ?>" target="_blank">
                                    <button class="btn btn-secondary btn-sm">
                                      Print
                                    </button>
                                </a>
                            <?php endif ?>
                            <a onclick="return confirm ('Apakah anda yakin akan menghapus data ini?')"
                                href="?page=add-pickup&delete=<?php echo $rowData['id'] ?>">
                                <button class="btn btn-danger btn-sm">
                                   Hapus
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