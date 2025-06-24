<?php
include 'controller/connection.php';

$id = isset($_GET['id']) ? $_GET['id'] : '';
$queryDetail = mysqli_query($connection, "SELECT trans_order.id, type_of_service.service_name, type_of_service.price, trans_order_detail.* FROM trans_order_detail LEFT JOIN trans_order ON trans_order.id = trans_order_detail.id_order LEFT JOIN type_of_service ON type_of_service.id = trans_order_detail.id_service WHERE trans_order_detail.id_order = '$id'");

$row = [];
while ($rowDetail = mysqli_fetch_assoc($queryDetail)) {
    $row[] = $rowDetail;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Transaksi</title>
    <style>
        body {
            margin: 20px;
        }

        .struct {
            width: 80mm;
            max-width: 100%;
            border: solid 1px #000;
            padding: 10px;
            margin: 0 auto;
        }

        .struct-header,
        .struct-footer {
            text-align: center;
            margin-botttom: 10px;
        }

        .struct-body h1 {
            font-size: 18px;
            margin: 0;
        }

        .struct-body {
            margin-bottom: 10px;
            border-bottom: 1px solid #000;

        }

        .struct-body table {
            border-collapse: collapse;
            width: 100%;
        }

        .struct-body table th,
        .struct-body table td {
            padding: 5px;
            text-align: left;
        }

        .struct-body table th {
            border-bottom: 1px solid #000;
        }

        .total,
        .payment,
        .change {
            display: flex;
            justify-content: space-evenly;
            padding: 5px 0;
            font-weight: bold;
        }

        .total {
            margin-top: 10px;
            border-top: 1px solid #000;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .struct {
                width: auto;
                border: none;
                margin: 0;
                padding: 0;
            }

            .struct-header h1,
            .struct-footer {
                font-size: 14px;
            }

            .total,
            .payment,
            .change {
                padding: 2px 0;
            }
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="col-sm-6">
        </div>
        <div class="col-sm-6"></div>
    </div>
    <div class="struct">
        <div class="struct-header">
            <div class="align-item-center">
                <span><img src="img/logo/logo.png" alt="" width="20px"></span>
                </span><span>Laundry Faith</span>
            </div>
            <p>Jl. Doang Jadian Kagak</p>
            <p>081808180818</p>
        </div>
        <br>
        <div class="struct-body">
            <!-- <p style="font-size: 12px;">Kode Transaksi: <?= $row['order_code'] ?></p> -->
            <table>
                <thead>
                    <th>Service</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Sub Total</th>
                </thead>
                <tbody>
                    <?php foreach ($row as $key => $rowDetail) : ?>
                        <tr>
                            <td><?= $rowDetail['service_name'] ?></td>
                            <td><?= $rowDetail['qty'] ?></td>
                            <td><?= "Rp. " . number_format($rowDetail['price'], 2) ?></td>
                            <td><?= "Rp. " . number_format($rowDetail['subtotal'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- <div class="total">
                <span>Total :</span>
                <span><?= "Rp. " . number_format($rowPembayaran['total_harga'], 2) ?></span>
            </div>
            <div class="payment">
                <span>Bayar :</span>
                <span><?= "Rp. " . number_format($rowPembayaran['nominal_bayar'], 2) ?></span>
            </div>
            <div class="change">
                <span>Kembali :</span>
                <span><?= "Rp. " . number_format($rowPembayaran['kembalian'], 2) ?></span>
            </div> -->
        </div>
        <br>
        <div class="struct-footer">
            <p>Terima Kasih Atas Kunjungan Anda!</p>
            <p><i>"Empat Sehat Lima Sekarat"</i></p>
        </div>
    </div>

    <script>
        window.onload = function printPage() {
            window.print();
        }
    </script>
</body>

</html>