<?php
include '../../controller/koneksi.php';

$id = isset($_GET['order']) ? $_GET['order'] : '';
$queryOrder = mysqli_query($connection, "SELECT trans_order.*, customer.customer_name , trans_laundry_pickup.pickup_date 
FROM trans_order 
LEFT JOIN trans_laundry_pickup ON trans_order.id = trans_laundry_pickup.id_order 
LEFT JOIN customer ON trans_order.id_customer = customer.id
WHERE trans_order.id = '$id'");
$dataOrder = mysqli_fetch_assoc($queryOrder);

$queryOrderDetail = mysqli_query($connection, "SELECT trans_order_detail.*, type_of_service.service_name, type_of_service.price 
FROM trans_order_detail 
LEFT JOIN type_of_service ON trans_order_detail.id_service = type_of_service.id
WHERE id_order = '$id'");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry App</title>
    <link rel="icon" type="image/png" href="../../img/logo/logo3.png">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 100vw;
            /* Lebar penuh layar */
            height: 100vh;
            /* Tinggi penuh layar */
            display: flex;
            justify-content: center;
            /* Pusatkan secara horizontal */
            align-items: center;
            /* Pusatkan secara vertikal */
            background-color: #f5f5f5;
            /* Tambahkan warna latar (opsional) */
        }

        .struct {
            border: 1px dashed #000;
            padding: 10px;
            background-color: #fff;
            /* Tambahkan latar belakang putih */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Tambahkan bayangan (opsional) */
        }


        .struct-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .struct-header img {
            display: block;
            margin: 0 auto 5px;
        }

        .struct-header p {
            margin: 2px 0;
            font-size: 10px;
            /* Perkecil font pada header */
        }

        .order-details {
            text-align: left;
            font-size: 9px;
            /* Perkecil font pada rincian pesanan */
            margin-top: 10px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            font-size: 9px;
            /* Perkecil font pada tabel */
            border-bottom: 1px solid #ddd;
            padding: 3px;
            /* Perkecil padding */
            text-align: left;
        }

        th {
            text-align: center;
            font-weight: bold;
        }

        tfoot td {
            font-size: 9px;
            /* Perkecil font pada bagian footer tabel */
        }

        .struct-footer {
            text-align: center;
            margin-top: 15px;
        }

        .struct-footer p {
            margin: 2px 0;
            font-size: 9px;
            /* Perkecil font pada footer */
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                width: 80mm;
                height: auto;
                display: flex;
                justify-content: center;
                align-items: flex-start;
                background: none;
            }

            .struct {
                border: none;
                margin: 0;
                width: 80mm;
            }

            /* Sembunyikan elemen lain selain .struct */
            body>*:not(.struct) {
                display: none;
            }
        }
    </style>

</head>

<body>
    <div class="struct">
        <div class="struct-header">
            <img src="" alt="" width="50px">
            <p><strong>Laundry App</strong></p>
            <p>Jl.Tb. Simatupang, RT.011, RW.002, Kel. Susukan, Kec. Ciracas, Jakarta Timur</p>
            <p>0818-0818-0818</p>
        </div>
        <br>
        <div class="order-details">
            <p><strong>Customer Name:</strong> <?= $dataOrder['customer_name'] ?></p>
            <p><strong>Order Code:</strong> <?= $dataOrder['order_code'] ?></p>
            <p><strong>Order Date:</strong> <?= $dataOrder['order_date'] ?></p>
            <p><strong>Pickup Date:</strong> <?= $dataOrder['pickup_date'] ?></p>
        </div>
        <br>
        <div class="struct-body">
            <table>
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($dataOrderDetail = mysqli_fetch_assoc($queryOrderDetail)) : ?>
                        <tr>
                            <td><?= $dataOrderDetail['service_name'] ?></td>
                            <td><?= 'Rp ' . number_format($dataOrderDetail['price'], 2, ',', '.') ?></td>
                            <td><?= $dataOrderDetail['qty'] ?></td>
                            <td><?= 'Rp ' . number_format($dataOrderDetail['subtotal'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" align="right"><strong>Total Price</strong></td>
                        <td><?= 'Rp ' . number_format($dataOrder['total_price'], 2, ',', ',') ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" align="right"><strong>Amount Paid</strong></td>
                        <td><?= 'Rp ' . number_format($dataOrder['order_pay'], 2, ',', ',') ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" align="right"><strong>Amount Change</strong></td>
                        <td><?= 'Rp ' . number_format($dataOrder['order_change'], 2, ',', ',') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <br>
        <div class="struct-footer">
            <p><strong>Thank You for Your Visit!</strong></p>
            <p><i>"Want to be clean? Just wash it!"</i></p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>