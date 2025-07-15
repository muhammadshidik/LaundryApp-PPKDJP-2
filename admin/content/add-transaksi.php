<?php
require_once 'admin/controller/koneksi.php';
include 'admin/controller/operator-validation.php';


// Logika saat form disubmit untuk MENAMBAH order
if (isset($_POST['add_order'])) {
    // var_dump($_POST); die(); // (Untuk debug, hapus jika sudah ok)

    $id_customer = $_POST['id_customer'];
    // ***** REVISI PHP: Menerima order_code dari JavaScript, TIDAK menggeneratenya di PHP lagi *****
    $order_code = $_POST['order_code']; // Menggunakan order_code yang dikirim dari JavaScript

    $order_date = $_POST['order_date'];
    $order_end_date = $_POST['order_end_date'];
    $order_status = $_POST['order_status'];
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

    // Mengambil kembali data transaksi yang baru saja disimpan untuk ditampilkan di struk
    // Penting: Menggunakan order_code yang *baru saja disimpan* dari $_POST, bukan berdasarkan ID database terakhir
    $query_new_order = mysqli_query($connection, "SELECT trans_order.*, customer.customer_name, customer.phone, customer.address
                                                FROM trans_order
                                                LEFT JOIN customer ON trans_order.id_customer = customer.id
                                                WHERE trans_order.id = '$trans_order_id'"); // Menggunakan trans_order_id untuk ambil data yang benar-benar baru
    $new_order_data = mysqli_fetch_assoc($query_new_order);

    $query_new_order_details = mysqli_query($connection, "SELECT trans_order_detail.*, type_of_service.service_name, type_of_service.price
                                                          FROM trans_order_detail
                                                          LEFT JOIN type_of_service ON trans_order_detail.id_service = type_of_service.id
                                                          WHERE trans_order_detail.id_order = '$trans_order_id'");
    $new_order_items = [];
    while ($row_detail = mysqli_fetch_assoc($query_new_order_details)) {
        $new_order_items[] = $row_detail;
    }

    // Gabungkan data untuk dikirim kembali ke JavaScript
    $new_transaction_for_js = [
        'id' => $new_order_data['order_code'], // Menggunakan order_code yang DARI DATABASE (yang berasal dari JS)
        'db_id' => $new_order_data['id'], // Tambahkan ID dari database
        'customer' => [
            'name' => $new_order_data['customer_name'],
            'phone' => $new_order_data['phone'],
            'address' => $new_order_data['address']
        ],
        'items' => array_map(function ($item) {
            return [
                'service' => $item['service_name'],
                'weight' => (float)$item['qty'], // Pastikan ini float
                'price' => (float)$item['price'], // Pastikan ini float
                'subtotal' => (float)$item['subtotal'] // Pastikan ini float
            ];
        }, $new_order_items),
        'total' => (float)$new_order_data['total_price'], // Pastikan ini float
        'date' => $new_order_data['order_date'],
        'status' => $new_order_data['order_status']
    ];

    // Simpan data transaksi baru di session atau kirim via JS
    echo "<script>const NEW_TRANSACTION_DATA = " . json_encode($new_transaction_for_js) . ";</script>";
} else if (isset($_POST['update_status'])) { // Logika untuk UPDATE status (dari AJAX)
    $id_order = $_POST['id_order'];
    $new_status = $_POST['new_status'];

    $update_query = mysqli_query($connection, "UPDATE trans_order SET order_status = '$new_status' WHERE id = '$id_order'");

    if ($update_query) {
        echo json_encode(['success' => true, 'message' => 'Status berhasil diupdate.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal mengupdate status.']);
    }
    exit; // Penting: Hentikan eksekusi setelah mengirim JSON response
} else if (isset($_GET['delete'])) { // Logika untuk MENGHAPUS
    $idDelete = $_GET['delete'];
    mysqli_query($connection, "DELETE FROM trans_order WHERE id='$idDelete'");
    // Detail dan pickup akan terhapus otomatis jika Anda set ON DELETE CASCADE di database
    // Jika tidak, query di bawah ini diperlukan
    // mysqli_query($connection, "DELETE FROM trans_order_detail WHERE id_order='$idDelete'");
    // mysqli_query($connection, "DELETE FROM trans_laundry_pickup WHERE id_order = '$idDelete'");
    header("Location:?page=transaksi&delete=success");
    die;
}

// Ambil semua data customer dan simpan dalam array untuk JS
$queryCustomerForJs = mysqli_query($connection, "SELECT id, customer_name, phone, address FROM customer");
$customersData = [];
while ($row = mysqli_fetch_assoc($queryCustomerForJs)) {
    $customersData[] = $row;
}
$queryCustomer = mysqli_query($connection, "SELECT * FROM customer"); // Ambil ulang atau pastikan pointer di reset

// Ambil semua data service dan simpan dalam array untuk JS
$queryServiceData = mysqli_query($connection, "SELECT id, service_name, price FROM type_of_service");
$servicesData = [];
while ($row = mysqli_fetch_assoc($queryServiceData)) {
    $servicesData[] = $row;
}

// Ambil semua data transaksi untuk diinisialisasi di JS
$queryAllTransactions = mysqli_query($connection, "SELECT trans_order.*, customer.customer_name, customer.phone, customer.address
                                                FROM trans_order
                                                LEFT JOIN customer ON trans_order.id_customer = customer.id
                                                ORDER BY trans_order.order_date DESC");
$allTransactionsData = [];
while ($row = mysqli_fetch_assoc($queryAllTransactions)) {
    $order_id = $row['id'];
    $query_order_details = mysqli_query($connection, "SELECT trans_order_detail.*, type_of_service.service_name, type_of_service.price
                                                      FROM trans_order_detail
                                                      LEFT JOIN type_of_service ON trans_order_detail.id_service = type_of_service.id
                                                      WHERE trans_order_detail.id_order = '$order_id'");
    $order_items = [];
    while ($row_detail = mysqli_fetch_assoc($query_order_details)) {
        $order_items[] = [
            'service' => $row_detail['service_name'],
            'weight' => (float)$row_detail['qty'],
            'price' => (float)$row_detail['price'],
            'subtotal' => (float)$row_detail['subtotal']
        ];
    }

    $allTransactionsData[] = [
        'id' => $row['order_code'],
        'db_id' => $row['id'], // Tambahkan ID dari database
        'customer' => [
            'name' => $row['customer_name'],
            'phone' => $row['phone'],
            'address' => $row['address']
        ],
        'items' => $order_items,
        'total' => (float)$row['total_price'],
        'date' => $row['order_date'],
        'status' => $row['order_status']
    ];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Laundry - POS</title>
    <style>
        /* CSS Anda yang sudah ada di sini */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            text-align: center;
            color: #4a5568;
            margin-bottom: 10px;
            font-size: 2.5em;
            font-weight: 700;
        }

        .header .subtitle {
            text-align: center;
            color: #718096;
            font-size: 1.1em;
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card h2 {
            color: #4a5568;
            margin-bottom: 20px;
            font-size: 1.8em;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #4a5568;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245, 101, 101, 0.3);
        }

        .btn-warning {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            color: white;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(237, 137, 54, 0.3);
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .service-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        }

        .service-card h3 {
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .service-card .price {
            font-size: 1.5em;
            font-weight: 700;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .cart-table th,
        .cart-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .cart-table th {
            background: #f7fafc;
            font-weight: 600;
            color: #4a5568;
        }

        .cart-table tr:hover {
            background: #f7fafc;
        }

        .total-section {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-top: 20px;
        }

        .total-section h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .total-amount {
            font-size: 2.5em;
            font-weight: 700;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: #fed7d7;
            color: #c53030;
        }

        .status-process {
            background: #feebc8;
            color: #dd6b20;
        }

        .status-ready {
            background: #c6f6d5;
            color: #2f855a;
        }

        .status-delivered {
            background: #bee3f8;
            color: #2b6cb0;
        }

        .transaction-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .transaction-item {
            background: #f7fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border-left: 4px solid #667eea;
        }

        .transaction-item h4 {
            color: #4a5568;
            margin-bottom: 5px;
        }

        .transaction-item p {
            color: #718096;
            margin-bottom: 5px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
        }

        .close:hover {
            color: #000;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
        }

        .stat-card h3 {
            font-size: 2em;
            margin-bottom: 10px;
        }

        .stat-card p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 2em;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }
        }

        .receipt {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            font-family: 'Courier New', monospace;
        }

        .receipt-header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .receipt-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .receipt-total {
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Laundry App Siddiq</h1>
            <p class="subtitle">Point of Sales System - Kelola Transaksi Laundry dengan Mudah</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3 id="totalTransactions">0</h3>
                <p>Total Transaksi</p>
            </div>
            <div class="stat-card">
                <h3 id="totalRevenue">Rp 0</h3>
                <p>Total Pendapatan</p>
            </div>
            <div class="stat-card">
                <h3 id="activeOrders">0</h3>
                <p>Pesanan Aktif</p>
            </div>
            <div class="stat-card">
                <h3 id="completedOrders">0</h3>
                <p>Pesanan Selesai</p>
            </div>
        </div>

        <div class="main-content">
            <div class="card">
                <h2>🛒 Transaksi Baru</h2>

                <form id="transactionForm" method="POST" action="">
                    <div class="form-group">
                        <label for="customerName">Nama Pelanggan</label>
                        <select name="id_customer" id="customerName" class="form-control" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            <?php mysqli_data_seek($queryCustomer, 0); // Reset pointer for customer dropdown 
                            ?>
                            <?php while ($rowCustomer = mysqli_fetch_assoc($queryCustomer)) : ?>
                                <option value="<?= $rowCustomer['id'] ?>"><?= $rowCustomer['customer_name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="customerPhone">No. Telepon</label>
                            <input type="tel" id="customerPhone" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="customerAddress">Alamat</label>
                            <input type="text" id="customerAddress" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Pilih Layanan</label>
                        <div class="services-grid" id="servicesGridContainer">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="serviceWeight">Berat/Jumlah</label>
                            <input type="number" id="serviceWeight" step="0.1" min="0.1" required>
                        </div>
                        <div class="form-group">
                            <label for="serviceType">Jenis Layanan</label>
                            <select id="serviceType" required>
                                <option value="">Pilih Layanan</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">Catatan</label>
                        <textarea id="notes" rows="3" placeholder="Catatan khusus untuk pesanan..."></textarea>
                    </div>

                    <button type="button" class="btn btn-primary" onclick="addToCart()" style="width: 100%; margin-bottom: 10px;">
                        ➕ Tambah ke Keranjang
                    </button>
                </form>

                <div id="cartSection" style="display: none;">
                    <h3>📋 Keranjang Belanja</h3>
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Layanan</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="cartItems">
                        </tbody>
                    </table>

                    <div class="total-section">
                        <h3>Total Pembayaran</h3>
                        <div class="total-amount" id="totalAmount">Rp 0</div>
                        <button class="btn btn-success" onclick="processTransaction()" style="width: 100%; margin-top: 15px;">
                            💳 Proses Transaksi
                        </button>
                    </div>
                </div>
            </div>

            <div class="card">
                <h2>📊 Riwayat Transaksi</h2>
                <div class="transaction-list" id="transactionHistory">
                </div>

                <button class="btn btn-warning" onclick="showAllTransactions()" style="width: 100%; margin-top: 15px;">
                    📋 Lihat Semua Transaksi
                </button>
            </div>
        </div>

        <div style="text-align: center; margin-top: 20px;">
            <button class="btn btn-primary" onclick="showReports()" style="margin: 0 10px;">
                📈 Laporan Penjualan
            </button>
            <button class="btn btn-warning" onclick="manageServices()" style="margin: 0 10px;">
                ⚙️ Kelola Layanan
            </button>
            <button class="btn btn-danger" onclick="clearCart()" style="margin: 0 10px;">
                🗑️ Bersihkan Keranjang
            </button>
        </div>
    </div>

    <div id="transactionModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal(true)">&times;</span>
            <div id="modalContent"></div>
        </div>
    </div>

    <script>
        // Data pelanggan yang diambil dari PHP
        const CUSTOMERS_DATA = <?= json_encode($customersData) ?>;

        // Data layanan dari PHP
        const SERVICES_DATA = <?= json_encode($servicesData) ?>;

        // Inisialisasi transaksi dari PHP (semua data transaksi dari database)
        let transactions = <?= json_encode($allTransactionsData) ?>;

        let cart = [];
        let transactionCounter = transactions.length + 1; // Untuk generate order_code di frontend

        // Variabel global untuk menyimpan data transaksi yang baru diproses
        let lastProcessedTransaction = null;

        function addService(serviceName, price) {
            const serviceTypeSelect = document.getElementById('serviceType');
            const serviceWeightInput = document.getElementById('serviceWeight');

            const service = SERVICES_DATA.find(s => s.service_name === serviceName);
            if (service) {
                serviceTypeSelect.value = service.service_name;
            }
            serviceWeightInput.focus();
        }

        function parseDecimal(value) {
            return parseFloat(value.toString().replace(',', '.')) || 0;
        }

        function addToCart() {
            const serviceType = document.getElementById('serviceType').value;
            const weightValue = document.getElementById('serviceWeight').value;
            const weight = parseDecimal(weightValue);
            const notes = document.getElementById('notes').value;

            if (!serviceType || !weightValue || weight <= 0) {
                alert('Mohon lengkapi semua field yang diperlukan!');
                return;
            }

            const selectedService = SERVICES_DATA.find(s => s.service_name === serviceType);
            if (!selectedService) {
                alert('Layanan tidak ditemukan!');
                return;
            }
            const price = selectedService.price;
            const subtotal = price * weight;

            const item = {
                id: Date.now(), // ID unik untuk item di keranjang (hanya di frontend)
                service_id: selectedService.id, // Menyimpan ID layanan dari database
                service: selectedService.service_name,
                weight: weight,
                price: price,
                subtotal: subtotal,
                notes: notes
            };

            cart.push(item);
            updateCartDisplay();

            document.getElementById('serviceType').value = '';
            document.getElementById('serviceWeight').value = '';
            document.getElementById('notes').value = '';
        }

        function updateCartDisplay() {
            const cartItems = document.getElementById('cartItems');
            const cartSection = document.getElementById('cartSection');
            const totalAmount = document.getElementById('totalAmount');

            if (cart.length === 0) {
                cartSection.style.display = 'none';
                return;
            }

            cartSection.style.display = 'block';

            let html = '';
            let total = 0;

            cart.forEach(item => {
                const unit = item.service.includes('Sepatu') ? 'pasang' :
                    item.service.includes('Karpet') ? 'm²' : 'kg';

                const formattedWeight = item.weight % 1 === 0 ?
                    item.weight.toString() :
                    item.weight.toFixed(1).replace('.', ',');

                html += `
                    <tr>
                        <td>${item.service}</td>
                        <td>${formattedWeight} ${unit}</td>
                        <td>Rp ${item.price.toLocaleString()}</td>
                        <td>Rp ${item.subtotal.toLocaleString()}</td>
                        <td>
                            <button class="btn btn-danger" onclick="removeFromCart(${item.id})" style="padding: 5px 10px; font-size: 12px;">
                                🗑️
                            </button>
                        </td>
                    </tr>
                `;
                total += item.subtotal;
            });

            cartItems.innerHTML = html;
            totalAmount.textContent = `Rp ${total.toLocaleString()}`;
        }

        function removeFromCart(itemId) {
            cart = cart.filter(item => item.id !== itemId);
            updateCartDisplay();
        }

        function clearCart() {
            cart = [];
            updateCartDisplay();
            document.getElementById('transactionForm').reset();
            document.getElementById('customerName').value = '';
            document.getElementById('customerPhone').value = '';
            document.getElementById('customerAddress').value = '';
        }

        function generateOrderCode() {
            // Generate order code based on current date/time and a random string
            const date = new Date();
            const year = date.getFullYear().toString().slice(2); // YY
            const month = (date.getMonth() + 1).toString().padStart(2, '0'); // MM
            const day = date.getDate().toString().padStart(2, '0'); // DD
            const hours = date.getHours().toString().padStart(2, '0');
            const minutes = date.getMinutes().toString().padStart(2, '0');
            const seconds = date.getSeconds().toString().padStart(2, '0');

            const randomString = Math.random().toString(36).substring(2, 6).toUpperCase(); // 4 random chars

            return `TRX-${year}${month}${day}-${hours}${minutes}${seconds}-${randomString}`;
        }


        function processTransaction() {
            const customerId = document.getElementById('customerName').value;
            const selectedCustomer = CUSTOMERS_DATA.find(c => c.id == customerId);

            if (!customerId || !selectedCustomer || cart.length === 0) {
                alert('Mohon lengkapi data pelanggan dan pastikan ada item di keranjang!');
                return;
            }

            const total = cart.reduce((sum, item) => sum + item.subtotal, 0);

            // Generate order ID for front-end (TRX-YYYYMMDD-HHMMSS-XXXX)
            const transactionId = generateOrderCode();

            const form = document.getElementById('transactionForm');

            // Hapus input hidden lama yang mungkin ada dari submit sebelumnya
            Array.from(form.elements).forEach(element => {
                // Jangan hapus input asli 'id_customer' dari select
                if (element.type === 'hidden' && element.name !== 'id_customer') {
                    form.removeChild(element);
                }
            });

            // Input untuk add_order, agar PHP mengenali ini adalah submit transaksi baru
            const addOrderInput = document.createElement('input');
            addOrderInput.type = 'hidden';
            addOrderInput.name = 'add_order';
            addOrderInput.value = '1';
            form.appendChild(addOrderInput);

            // Input untuk order_code yang DIGENERATED OLEH JAVASCRIPT, dikirim ke PHP
            const orderCodeInput = document.createElement('input');
            orderCodeInput.type = 'hidden';
            orderCodeInput.name = 'order_code';
            orderCodeInput.value = transactionId; // Menggunakan order ID dari JS
            form.appendChild(orderCodeInput);

            // order_date
            const orderDateInput = document.createElement('input');
            orderDateInput.type = 'hidden';
            orderDateInput.name = 'order_date';
            orderDateInput.value = new Date().toISOString().slice(0, 19).replace('T', ' ');
            form.appendChild(orderDateInput);

            // order_end_date (2 hari setelah order_date)
            const endDate = new Date();
            endDate.setDate(endDate.getDate() + 2);
            const orderEndDateInput = document.createElement('input');
            orderEndDateInput.type = 'hidden';
            orderEndDateInput.name = 'order_end_date';
            orderEndDateInput.value = endDate.toISOString().slice(0, 19).replace('T', ' ');
            form.appendChild(orderEndDateInput);

            // order_status
            const orderStatusInput = document.createElement('input');
            orderStatusInput.type = 'hidden';
            orderStatusInput.name = 'order_status';
            orderStatusInput.value = 'pending';
            form.appendChild(orderStatusInput);

            // total_price
            const totalPriceInput = document.createElement('input');
            totalPriceInput.type = 'hidden';
            totalPriceInput.name = 'total_price';
            totalPriceInput.value = total;
            form.appendChild(totalPriceInput);

            // Detail layanan (items di keranjang)
            cart.forEach((item, index) => {
                const serviceIdInput = document.createElement('input');
                serviceIdInput.type = 'hidden';
                serviceIdInput.name = `id_service[${index}]`;
                serviceIdInput.value = item.service_id;
                form.appendChild(serviceIdInput);

                const qtyInput = document.createElement('input');
                qtyInput.type = 'hidden';
                qtyInput.name = `qty[${index}]`;
                qtyInput.value = item.weight;
                form.appendChild(qtyInput);

                const subtotalInput = document.createElement('input');
                subtotalInput.type = 'hidden';
                subtotalInput.name = `subtotal[${index}]`;
                subtotalInput.value = item.subtotal;
                form.appendChild(subtotalInput);
            });

            // Submit form ke PHP
            document.body.appendChild(form); // Pastikan form ada di body sebelum submit
            form.submit();
        }

        function showReceipt(transaction) {
            const receiptHtml = `
                <div class="receipt" id="printableReceipt">
                    <div class="receipt-header">
                        <h2>🧺 LAUNDRY RECEIPT</h2>
                        <p>ID: ${transaction.id}</p>
                        <p>Tanggal: ${new Date(transaction.date).toLocaleString('id-ID')}</p>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <strong>Pelanggan:</strong><br>
                        ${transaction.customer.name}<br>
                        ${transaction.customer.phone}<br>
                        ${transaction.customer.address}
                    </div>

                    <div style="margin-bottom: 20px;">
                        <strong>Detail Pesanan:</strong><br>
                        ${transaction.items.map(item => {
                            const unit = item.service.includes('Sepatu') ? 'pasang' :
                                         item.service.includes('Karpet') ? 'm²' : 'kg';
                            const formattedWeight = item.weight % 1 === 0 ?
                                                    item.weight.toString() :
                                                    item.weight.toFixed(1).replace('.', ',');
                            return `
                            <div class="receipt-item">
                                <span>${item.service} (${formattedWeight} ${unit})</span>
                                <span>Rp ${item.subtotal.toLocaleString()}</span>
                            </div>
                            `;
                        }).join('')}
                    </div>

                    <div class="receipt-total">
                        <div class="receipt-item">
                            <span>TOTAL:</span>
                            <span>Rp ${transaction.total.toLocaleString()}</span>
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 20px;">
                        <p>Terima kasih atas kepercayaan Anda!</p>
                        <p>Barang akan siap dalam 1-2 hari kerja</p>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 20px;">
                    <button class="btn btn-primary" onclick="printReceipt()">🖨️ Cetak Struk</button>
                    <button class="btn btn-success" onclick="closeModal(true)">✅ Selesai</button>
                </div>
            `;

            document.getElementById('modalContent').innerHTML = receiptHtml;
            document.getElementById('transactionModal').style.display = 'block';
        }

        function printReceipt() {
            const originalBodyHtml = document.body.innerHTML;
            const receiptContent = document.getElementById('printableReceipt').outerHTML;

            const printWindow = window.open('', '_blank');
            printWindow.document.open();
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Cetak Struk</title>
                    <style>
                        body { font-family: 'Courier New', monospace; margin: 0; padding: 0; }
                        .receipt { width: 80mm; margin: 10mm auto; padding: 5mm; border: 1px solid #ccc; }
                        .receipt-header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 5px; margin-bottom: 10px; }
                        .receipt-item { display: flex; justify-content: space-between; margin-bottom: 2px; }
                        .receipt-total { border-top: 2px solid #333; padding-top: 5px; margin-top: 5px; font-weight: bold; }
                        @media print {
                            body { margin: 0; padding: 0; }
                            .receipt { width: auto; margin: 0; box-shadow: none; border: none; padding: 10mm; }
                        }
                    </style>
                </head>
                <body>
                    ${receiptContent}
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }

        function updateTransactionHistory() {
            const historyContainer = document.getElementById('transactionHistory');
            // Data transaksi diambil langsung dari variabel 'transactions' yang sudah diinisialisasi dari PHP
            const currentTransactions = transactions;

            const sortedTransactions = [...currentTransactions].sort((a, b) => new Date(b.date) - new Date(a.date));
            const recentTransactions = sortedTransactions.slice(0, 5); // Hanya menampilkan 5 transaksi terbaru

            const html = recentTransactions.map(transaction => `
                <div class="transaction-item">
                    <h4>${transaction.id} - ${transaction.customer.name}</h4>
                    <p>📞 ${transaction.customer.phone}</p>
                    <p>🛍️ ${transaction.items.map(item => {
                        const unit = item.service.includes('Sepatu') ? 'pasang' : item.service.includes('Karpet') ? 'm²' : 'kg';
                        const formattedWeight = item.weight % 1 === 0 ? item.weight.toString() : item.weight.toFixed(1).replace('.', ',');
                        return `${item.service} - ${formattedWeight}${unit}`;
                    }).join(', ')}</p>
                    <p>💰 Rp ${transaction.total.toLocaleString()}</p>
                    <p>📅 ${new Date(transaction.date).toLocaleString('id-ID')}</p>
                    <span class="status-badge status-${transaction.status}">${getStatusText(transaction.status)}</span>
                </div>
            `).join('');

            historyContainer.innerHTML = html || '<p>Belum ada transaksi</p>';
        }

        function getStatusText(status) {
            const statusMap = {
                '0': 'Belum Bayar',
                '1': 'Lunas',
            };
            return statusMap[status] || status;
        }

        function updateStats() {
            const totalTransactions = transactions.length;
            const totalRevenue = transactions.reduce((sum, t) => sum + t.total, 0);
            const activeOrders = transactions.filter(t => t.status !== '1').length;
            const completedOrders = transactions.filter(t => t.status === '1').length;

            document.getElementById('totalTransactions').textContent = totalTransactions;
            document.getElementById('totalRevenue').textContent = `Rp ${totalRevenue.toLocaleString()}`;
            document.getElementById('activeOrders').textContent = activeOrders;
            document.getElementById('completedOrders').textContent = completedOrders;
        }

        function showAllTransactions() {
            const allTransactionsHtml = `
                <h2>📋 Semua Transaksi</h2>
                <div style="max-height: 400px; overflow-y: auto;">
                    ${transactions.map(transaction => `
                        <div class="transaction-item">
                            <h4>${transaction.id} - ${transaction.customer.name}</h4>
                            <p>📞 ${transaction.customer.phone}</p>
                            <p>🛍️ ${transaction.items.map(item => {
                                const unit = item.service.includes('Sepatu') ? 'pasang' : item.service.includes('Karpet') ? 'm²' : 'kg';
                                const formattedWeight = item.weight % 1 === 0 ? item.weight.toString() : item.weight.toFixed(1).replace('.', ',');
                                return `${item.service} - ${formattedWeight}${unit}`;
                            }).join(', ')}</p>
                            <p>💰 Rp ${transaction.total.toLocaleString()}</p>
                            <p>📅 ${new Date(transaction.date).toLocaleString('id-ID')}</p>
                            <span class="status-badge status-${transaction.status}">Status : ${getStatusText(transaction.status)} </span>
                        </div>
                    `).join('')}
                </div>
            `;

            document.getElementById('modalContent').innerHTML = allTransactionsHtml;
            document.getElementById('transactionModal').style.display = 'block';
        }

        // Fungsi deleteTransaction untuk menghapus dari database
        function deleteTransaction(dbId) {
            if (confirm("Apakah Anda yakin ingin menghapus transaksi ini?")) {
                const url = `?page=transaksi&delete=${dbId}`;
                window.location.href = url; // Redirect untuk memproses penghapusan di PHP
            }
        }

        function bayar(orderCode) {
            // Logika pembayaran, bisa mengarahkan ke halaman pembayaran atau memicu modal pembayaran
            alert(`Membuka halaman pembayaran untuk transaksi: ${orderCode}`);
            // Anda bisa menambahkan logika lebih lanjut di sini, seperti:
            // window.location.href = `?page=pembayaran&order_code=${orderCode}`;
        }


        function showReports() {
            const today = new Date();
            const thisMonth = today.getMonth();
            const thisYear = today.getFullYear();

            const monthlyTransactions = transactions.filter(t => {
                const tDate = new Date(t.date);
                return tDate.getMonth() === thisMonth && tDate.getFullYear() === thisYear;
            });

            const monthlyRevenue = monthlyTransactions.reduce((sum, t) => sum + t.total, 0);

            const serviceStats = {};
            transactions.forEach(t => {
                t.items.forEach(item => {
                    if (!serviceStats[item.service]) {
                        serviceStats[item.service] = {
                            count: 0,
                            revenue: 0
                        };
                    }
                    serviceStats[item.service].count++;
                    serviceStats[item.service].revenue += item.subtotal;
                });
            });

            const reportsHtml = `
                <h2>📈 Laporan Penjualan</h2>

                <div class="stats-grid" style="margin-bottom: 20px;">
                    <div class="stat-card">
                        <h3>${transactions.length}</h3>
                        <p>Total Transaksi</p>
                    </div>
                    <div class="stat-card">
                        <h3>${monthlyTransactions.length}</h3>
                        <p>Transaksi Bulan Ini</p>
                    </div>
                    <div class="stat-card">
                        <h3>Rp ${monthlyRevenue.toLocaleString()}</h3>
                        <p>Pendapatan Bulan Ini</p>
                    </div>
                </div>

                <h3>📊 Statistik Layanan</h3>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Layanan</th>
                            <th>Jumlah Order</th>
                            <th>Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${Object.entries(serviceStats).map(([service, stats]) => `
                            <tr>
                                <td>${service}</td>
                                <td>${stats.count}</td>
                                <td>Rp ${stats.revenue.toLocaleString()}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;

            document.getElementById('modalContent').innerHTML = reportsHtml;
            document.getElementById('transactionModal').style.display = 'block';
        }

        function manageServices() {
            const servicesTableHtml = `
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Layanan</th>
                            <th>Harga</th>
                            <th>Satuan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${SERVICES_DATA.map(service => `
                            <tr>
                                <td> ${service.service_name}</td>
                                <td>Rp ${service.price.toLocaleString('id-ID')}</td>
                                <td>${service.service_name.includes('Sepatu') ? 'per pasang' : service.service_name.includes('Karpet') ? 'per m²' : 'per kg'}</td>
                                <td><span class="status-badge status-ready">Aktif</span></td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;

            const servicesHtml = `
                <h2>⚙️ Kelola Layanan</h2>
                <p>Fitur ini memungkinkan Anda mengelola jenis layanan dan harga.</p>
                
                ${servicesTableHtml}
                
                <div style="text-align: center; margin-top: 20px;">
                    <button class="btn btn-primary" onclick="alert('Fitur akan segera tersedia!')">
                        ➕ Tambah Layanan Baru
                    </button>
                </div>
            `;

            document.getElementById('modalContent').innerHTML = servicesHtml;
            document.getElementById('transactionModal').style.display = 'block';
        }

        async function updateTransactionStatus(dbId) {
            const transaction = transactions.find(t => t.db_id == dbId);
            if (!transaction) return;

            const statusOptions = [{
                    value: '0',
                    text: 'Belum Bayar'
                },
                {
                    value: '1',
                    text: 'Selesai'
                }
            ];

            const statusHtml = `
                <h2>📝 Update Status Transaksi</h2>
                <h3>${transaction.id} - ${transaction.customer.name}</h3>
                <p>Status saat ini: <span class="status-badge status-${transaction.status}">${getStatusText(transaction.status)}</span></p>

                <div class="form-group">
                    <label>Pilih Status Baru:</label>
                    <select id="newStatus" style="width: 100%; padding: 10px; margin: 10px 0;">
                        ${statusOptions.map(option => `
                            <option value="${option.value}" ${transaction.status === option.value ? 'selected' : ''}>
                                ${option.text}
                            </option>
                        `).join('')}
                    </select>
                </div>

                <div style="text-align: center; margin-top: 20px;">
                    <button class="btn btn-success" onclick="saveStatusUpdate('${dbId}')">
                        ✅ Simpan Update
                    </button>
                    <button class="btn btn-danger" onclick="closeModal()" style="margin-left: 10px;">
                        ❌ Batal
                    </button>
                </div>
            `;

            document.getElementById('modalContent').innerHTML = statusHtml;
            document.getElementById('transactionModal').style.display = 'block';
        }

        async function saveStatusUpdate(dbId) {
            const newStatus = document.getElementById('newStatus').value;

            try {
                const response = await fetch('', { // Kirim ke file PHP yang sama
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `update_status=1&id_order=${dbId}&new_status=${newStatus}`
                });
                const data = await response.json();

                if (data.success) {
                    // Perbarui array 'transactions' di frontend
                    const transactionIndex = transactions.findIndex(t => t.db_id == dbId);
                    if (transactionIndex !== -1) {
                        transactions[transactionIndex].status = newStatus;
                    }

                    updateTransactionHistory();
                    updateStats();
                    closeModal();
                    alert('Status berhasil diupdate!');
                } else {
                    alert('Gagal mengupdate status: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate status.');
            }
        }

        function closeModal(shouldReload = false) {
            document.getElementById('transactionModal').style.display = 'none';
            if (shouldReload) {
                // Opsional: Reload halaman untuk memastikan data terbaru dari DB
                window.location.href = "?page=transaksi";
            }
        }

        function formatNumber(input) {
            let value = input.value.replace(',', '.');
            if (!/^\d*\.?\d*$/.test(value)) {
                value = value.slice(0, -1);
            }
            input.value = value;
        }


        document.addEventListener('DOMContentLoaded', function() {
            updateTransactionHistory();
            updateStats();

            const weightInput = document.getElementById('serviceWeight');
            weightInput.addEventListener('input', function() {
                formatNumber(this);
            });

            window.onclick = function(event) {
                const modal = document.getElementById('transactionModal');
                if (event.target === modal) {
                    closeModal(); // Tidak reload jika klik di luar modal (hanya tutup)
                }
            };

            const customerNameSelect = document.getElementById('customerName');
            const customerPhoneInput = document.getElementById('customerPhone');
            const customerAddressInput = document.getElementById('customerAddress');

            customerNameSelect.addEventListener('change', function() {
                const selectedCustomerId = this.value;
                const selectedCustomer = CUSTOMERS_DATA.find(customer => customer.id == selectedCustomerId);

                if (selectedCustomer) {
                    customerPhoneInput.value = selectedCustomer.phone;
                    customerAddressInput.value = selectedCustomer.address;
                } else {
                    customerPhoneInput.value = '';
                    customerAddressInput.value = '';
                }
            });

            const servicesGridContainer = document.getElementById('servicesGridContainer');
            const serviceTypeSelect = document.getElementById('serviceType');

            function populateServices() {
                let buttonsHtml = '';
                let optionsHtml = '<option value="">Pilih Layanan</option>';

                SERVICES_DATA.forEach(service => {
                    const unitText = service.service_name.includes('Sepatu') ? 'pasang' :
                        service.service_name.includes('Karpet') ? 'm²' : 'kg';
                    const formattedPrice = service.price.toLocaleString('id-ID');

                    buttonsHtml += `
                        <button type="button" class="service-card" onclick="addService('${service.service_name}', ${service.price})">
                            <h3>${service.service_name}</h3>
                            <div class="price">Rp ${formattedPrice}/${unitText}</div>
                        </button>
                    `;

                    optionsHtml += `
                        <option value="${service.service_name}">${service.service_name} (Rp ${formattedPrice}/${unitText})</option>
                    `;
                });

                servicesGridContainer.innerHTML = buttonsHtml;
                serviceTypeSelect.innerHTML = optionsHtml;
            }

            populateServices();

            // Cek apakah ada data transaksi baru dari PHP setelah submit
            if (typeof NEW_TRANSACTION_DATA !== 'undefined' && NEW_TRANSACTION_DATA) {
                // Tambahkan atau perbarui transaksi di array `transactions` lokal
                const existingTransactionIndex = transactions.findIndex(t => t.db_id === NEW_TRANSACTION_DATA.db_id);
                if (existingTransactionIndex === -1) {
                    transactions.push(NEW_TRANSACTION_DATA);
                } else {
                    transactions[existingTransactionIndex] = NEW_TRANSACTION_DATA;
                }

                // Urutkan ulang transaksi berdasarkan tanggal terbaru agar history selalu menampilkan yang terbaru
                transactions.sort((a, b) => new Date(b.date) - new Date(a.date));

                // Tampilkan struk untuk transaksi yang baru disimpan (yang dari PHP)
                showReceipt(NEW_TRANSACTION_DATA);
                clearCart(); // Bersihkan keranjang setelah transaksi ditampilkan di struk
                updateTransactionHistory(); // Perbarui riwayat
                updateStats(); // Perbarui statistik
            }
        });
    </script>
</body>

</html>