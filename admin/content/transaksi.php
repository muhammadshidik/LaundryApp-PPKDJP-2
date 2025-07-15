<?php

// Gunakan __DIR__ untuk path absolut
include __DIR__ . '/../controller/koneksi.php';
include __DIR__ . '/../controller/operator-validation.php';


$queryData = mysqli_query($connection, "SELECT trans_order.*, customer.customer_name FROM trans_order LEFT JOIN customer ON trans_order.id_customer = customer.id ORDER BY trans_order.order_status DESC, trans_order.updated_at DESC")
?>
<div class="card shadow">
    <div class="card-header">
        <h3> Data Transaksi</h3>
    </div>
    <div class="card-body">
        <?php include 'admin/controller/alert-data-crud.php' ?>
        <div align="right" class="button-action">
            <a href="?page=add-transaksi" class="btn btn-primary btn-sm">Tambah Transaksi</a>
        </div>
        <table class="table table-bordered table-striped table-hover table-responsive mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Transaksi</th>
                    <th>Nama Pelanggan</th>
                        <th>Catatan</th>
                    <th>Tanggal Dipesan</th>
                    <th>Status Pemesanan</th>
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
                          <td><?= isset($rowData['deskripsi']) ? $rowData['deskripsi'] : '-' ?></td>
                        <td><?= isset($rowData['order_date']) ? $rowData['order_date'] : '-' ?></td>
                        <?php $statusOrder = getOrderStatus($rowData['order_status']) ?>
                        <td><?= $statusOrder ?></td>
                        <td>
                            <a href="?page=add-order&view=<?php echo $rowData['id'] ?>">
                                <button class="btn btn-secondary btn-sm">
                                    <i class="tf-icon bx bx-show bx-22px">Info Detail</i>
                                </button>
                                <a href="?page=pickup">
                                    <button class="btn btn-secondary btn-sm">
                                        <i class="tf-icon bx bx-show bx-22px">Bayar</i>
                                    </button>
                                    <a onclick="return confirm ('Apakah anda yakin akan menghapus data ini?')"
                                        href="?page=add-order&delete=<?php echo $rowData['id'] ?>">
                                        <button class="btn btn-danger btn-sm">
                                            <i class="tf-icon bx bx-trash bx-22px">Hapus</i>
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