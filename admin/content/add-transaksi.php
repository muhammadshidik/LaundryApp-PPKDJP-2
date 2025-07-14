<?php
require_once 'admin/controller/koneksi.php';
include 'admin/controller/operator-validation.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
// (queryService ini tidak lagi perlu diulang jika data sudah diambil ke $servicesData)
// $queryService = mysqli_query($connection, "SELECT * FROM type_of_service");

// Ambil semua data customer dan simpan dalam array untuk JS
$queryCustomerForJs = mysqli_query($connection, "SELECT id, customer_name, phone, address FROM customer");
$customersData = [];
while ($row = mysqli_fetch_assoc($queryCustomerForJs)) {
    $customersData[] = $row;
}
// Reset pointer queryCustomer jika Anda masih akan menggunakannya untuk HTML SELECT di bawah
// Jika tidak, Anda bisa langsung pakai $customersData di loop HTML
$queryCustomer = mysqli_query($connection, "SELECT * FROM customer"); // Ambil ulang atau pastikan pointer di reset

// --- PENAMBAHAN PHP: Ambil semua data service dan simpan dalam array untuk JS ---
$queryServiceData = mysqli_query($connection, "SELECT id, service_name, price FROM type_of_service");
$servicesData = [];
while ($row = mysqli_fetch_assoc($queryServiceData)) {
    $servicesData[] = $row;
}
// --- AKHIR PENAMBAHAN PHP ---
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
            <h1>🧺 Laundry Om Ruben</h1>
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

                <form id="transactionForm">
                    <div class="form-group">
                        <label for="customerName">Nama Pelanggan</label>
                        <select name="id_customer" id="customerName" class="form-control" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            <?php mysqli_data_seek($queryCustomer, 0); // Reset pointer for customer dropdown ?>
                            <?php while ($rowCustomer = mysqli_fetch_assoc($queryCustomer)) : ?>
                                <option value="<?= $rowCustomer['id'] ?>"><?= $rowCustomer['customer_name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="customerPhone">No. Telepon</label>
                            <input type="tel" id="customerPhone" required readonly> </div>
                        <div class="form-group">
                            <label for="customerAddress">Alamat</label>
                            <input type="text" id="customerAddress" readonly> </div>
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
                    <div class="transaction-item">
                        <h4>TRX-001 - John Doe</h4>
                        <p>📞 0812-3456-7890</p>
                        <p>🛍️ Cuci Setrika - 2.5kg</p>
                        <p>💰 Rp 17.500</p>
                        <p>📅 13 Juli 2025, 14:30</p>
                        <span class="status-badge status-process">Proses</span>
                    </div>
                    <div class="transaction-item">
                        <h4>TRX-002 - Jane Smith</h4>
                        <p>📞 0813-7654-3210</p>
                        <p>🛍️ Cuci Kering - 3kg</p>
                        <p>💰 Rp 15.000</p>
                        <p>📅 13 Juli 2025, 13:15</p>
                        <span class="status-badge status-ready">Siap</span>
                    </div>
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
            <span class="close" onclick="closeModal()">&times;</span>
            <div id="modalContent"></div>
        </div>
    </div>

    <script>
        // Data pelanggan yang diambil dari PHP
        const CUSTOMERS_DATA = <?= json_encode($customersData) ?>;
        
        // --- PENAMBAHAN JAVASCRIPT: Deklarasi data layanan dari PHP ---
        const SERVICES_DATA = <?= json_encode($servicesData) ?>;
        // --- AKHIR PENAMBAHAN JAVASCRIPT ---

        let cart = [];
        let transactions = JSON.parse(localStorage.getItem('laundryTransactions')) || [];
        let transactionCounter = transactions.length + 1;

        // Fungsi-fungsi yang dipanggil langsung oleh elemen HTML (onclick)
        // Ditempatkan di bagian awal agar sudah tersedia saat HTML dimuat
        function addService(serviceName, price) { // Parameter `price` bisa dihapus jika tidak lagi digunakan secara langsung
            const serviceTypeSelect = document.getElementById('serviceType');
            const serviceWeightInput = document.getElementById('serviceWeight');
            
            // Mencari layanan berdasarkan nama dari data yang sudah dimuat
            const service = SERVICES_DATA.find(s => s.service_name === serviceName);
            if (service) {
                serviceTypeSelect.value = service.service_name; // Mengisi dropdown dengan nama layanan
            }
            serviceWeightInput.focus();
        }

        function parseDecimal(value) {
            // Menangani koma dan titik sebagai pemisah desimal
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

            // --- REVISI JAVASCRIPT: Ambil harga dari SERVICES_DATA ---
            const selectedService = SERVICES_DATA.find(s => s.service_name === serviceType);
            if (!selectedService) {
                alert('Layanan tidak ditemukan!'); // Seharusnya tidak terjadi jika dropdown diisi dinamis
                return;
            }
            const price = selectedService.price; // Ambil harga dari objek layanan
            const subtotal = price * weight;

            const item = {
                id: Date.now(),
                service_id: selectedService.id, // Menyimpan ID layanan dari database
                service: selectedService.service_name, // Menggunakan nama layanan dari data
                weight: weight,
                price: price,
                subtotal: subtotal,
                notes: notes
            };
            // --- AKHIR REVISI JAVASCRIPT ---

            cart.push(item);
            updateCartDisplay();

            // Bersihkan form setelah ditambahkan ke keranjang
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

                // Format berat untuk ditampilkan dengan desimal (koma)
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
            // --- PENAMBAHAN JAVASCRIPT: Mengosongkan input pelanggan setelah clear cart ---
            document.getElementById('customerName').value = '';
            document.getElementById('customerPhone').value = '';
            document.getElementById('customerAddress').value = '';
            // --- AKHIR PENAMBAHAN JAVASCRIPT ---
        }

        function processTransaction() {
            // Ambil ID pelanggan dari dropdown
            const customerId = document.getElementById('customerName').value;
            // Dapatkan objek pelanggan dari CUSTOMERS_DATA
            const selectedCustomer = CUSTOMERS_DATA.find(c => c.id == customerId);


            if (!customerId || !selectedCustomer || cart.length === 0) {
                alert('Mohon lengkapi data pelanggan dan pastikan ada item di keranjang!');
                return;
            }

            const total = cart.reduce((sum, item) => sum + item.subtotal, 0);

            const transaction = {
                id: `LND-${transactionCounter.toString().padStart(3, '0')}`,
                customer: {
                    id: selectedCustomer.id, // Simpan ID pelanggan
                    name: selectedCustomer.customer_name,
                    phone: selectedCustomer.phone,
                    address: selectedCustomer.address
                },
                items: [...cart],
                total: total,
                date: new Date().toISOString(),
                status: 'pending'
            };

            transactions.push(transaction);
            localStorage.setItem('laundryTransactions', JSON.stringify(transactions));

            transactionCounter++;

            // Tampilkan struk
            showReceipt(transaction);

            // Bersihkan form dan keranjang
            clearCart();
            updateTransactionHistory();
            updateStats();
        }

        // ... (Your existing JavaScript code before showReceipt) ...
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
                ${transaction.items.map(item => `
                    <div class="receipt-item">
                        <span>${item.service} (${item.weight} ${item.service.includes('Sepatu') ? 'pasang' : item.service.includes('Karpet') ? 'm²' : 'kg'})</span>
                        <span>Rp ${item.subtotal.toLocaleString()}</span>
                    </div>
                `).join('')}
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
            <button class="btn btn-success" onclick="closeModal()">✅ Selesai</button>
        </div>
    `;

    document.getElementById('modalContent').innerHTML = receiptHtml;
    document.getElementById('transactionModal').style.display = 'block';
}

// --- REVISI FUNGSI printReceipt ---
function printReceipt() {
    const originalBodyHtml = document.body.innerHTML; // Simpan isi body asli
    const receiptContent = document.getElementById('printableReceipt').outerHTML; // Ambil HTML receipt

    // Ganti isi body dengan hanya konten receipt
    document.body.innerHTML = receiptContent;

    // Tambahkan sedikit CSS untuk print jika diperlukan (misalnya menghilangkan margin/padding default browser)
    const printStyles = `
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .receipt {
                width: 100%;
                margin: 0;
                box-shadow: none; /* Remove shadow for print */
                border: none; /* Remove border for print if not desired */
                padding: 10mm; /* Add some padding for printout */
            }
        }
    `;
    const styleElement = document.createElement('style');
    styleElement.innerHTML = printStyles;
    document.head.appendChild(styleElement);


    window.print(); // Cetak hanya isi yang ada di body

    // Setelah mencetak, kembalikan isi body ke kondisi semula
    document.body.innerHTML = originalBodyHtml;

    // Hapus style tambahan untuk print
    document.head.removeChild(styleElement);

    // Pastikan modal tertutup atau tampilkan kembali jika perlu
    // closeModal(); // Anda mungkin ingin modal tetap terbuka setelah cetak, atau menutupnya otomatis.
                   // Jika ingin menutup, uncomment baris ini.
}
// --- AKHIR REVISI FUNGSI printReceipt ---

// ... (Your existing JavaScript code after printReceipt) ...

        function updateTransactionHistory() {
            const historyContainer = document.getElementById('transactionHistory');
            // Menampilkan 5 transaksi terbaru
            const recentTransactions = transactions.slice(-5).reverse();

            const html = recentTransactions.map(transaction => `
                <div class="transaction-item">
                    <h4>${transaction.id} - ${transaction.customer.name}</h4>
                    <p>📞 ${transaction.customer.phone}</p>
                    <p>🛍️ ${transaction.items.map(item => `${item.service} - ${item.weight}${item.service.includes('Sepatu') ? 'pasang' : item.service.includes('Karpet') ? 'm²' : 'kg'}`).join(', ')}</p>
                    <p>💰 Rp ${transaction.total.toLocaleString()}</p>
                    <p>📅 ${new Date(transaction.date).toLocaleString('id-ID')}</p>
                    <span class="status-badge status-${transaction.status}">${getStatusText(transaction.status)}</span>
                </div>
            `).join('');

            historyContainer.innerHTML = html || '<p>Belum ada transaksi</p>';
        }

        function getStatusText(status) {
            const statusMap = {
                'pending': 'Menunggu',
                'process': 'Proses',
                'ready': 'Siap',
                'delivered': 'Selesai'
            };
            return statusMap[status] || status;
        }

        function updateStats() {
            const totalTransactions = transactions.length;
            const totalRevenue = transactions.reduce((sum, t) => sum + t.total, 0);
            const activeOrders = transactions.filter(t => t.status !== 'delivered').length;
            const completedOrders = transactions.filter(t => t.status === 'delivered').length;

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
                            <p>🛍️ ${transaction.items.map(item => `${item.service} - ${item.weight}${item.service.includes('Sepatu') ? 'pasang' : item.service.includes('Karpet') ? 'm²' : 'kg'}`).join(', ')}</p>
                            <p>💰 Rp ${transaction.total.toLocaleString()}</p>
                            <p>📅 ${new Date(transaction.date).toLocaleString('id-ID')}</p>
                            <span class="status-badge status-${transaction.status}">${getStatusText(transaction.status)}</span>
                            <button class="btn btn-primary" onclick="updateTransactionStatus('${transaction.id}')" style="margin-top: 10px; padding: 5px 15px; font-size: 12px;">
                                📝 Update Status
                            </button>
                        </div>
                    `).join('')}
                </div>
            `;

            document.getElementById('modalContent').innerHTML = allTransactionsHtml;
            document.getElementById('transactionModal').style.display = 'block';
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
            // --- REVISI JAVASCRIPT: Tampilkan layanan dari SERVICES_DATA ---
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
            // --- AKHIR REVISI JAVASCRIPT ---

            document.getElementById('modalContent').innerHTML = servicesHtml;
            document.getElementById('transactionModal').style.display = 'block';
        }

        function updateTransactionStatus(transactionId) {
            const transaction = transactions.find(t => t.id === transactionId);
            if (!transaction) return;

            const statusOptions = [{
                    value: 'pending',
                    text: 'Menunggu'
                },
                {
                    value: 'process',
                    text: 'Sedang Proses'
                },
                {
                    value: 'ready',
                    text: 'Siap Diambil'
                },
                {
                    value: 'delivered',
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
                    <button class="btn btn-success" onclick="saveStatusUpdate('${transactionId}')">
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

        function saveStatusUpdate(transactionId) {
            const newStatus = document.getElementById('newStatus').value;
            const transactionIndex = transactions.findIndex(t => t.id === transactionId);

            if (transactionIndex !== -1) {
                transactions[transactionIndex].status = newStatus;
                localStorage.setItem('laundryTransactions', JSON.stringify(transactions));
                updateTransactionHistory();
                updateStats();
                closeModal();
                alert('Status berhasil diupdate!');
            }
        }

        function closeModal() {
            document.getElementById('transactionModal').style.display = 'none';
        }

        function formatNumber(input) {
            // Ganti koma dengan titik untuk pemisah desimal
            let value = input.value.replace(',', '.');

            // Pastikan hanya angka desimal yang valid
            if (!/^\d*\.?\d*$/.test(value)) {
                value = value.slice(0, -1);
            }

            // Perbarui nilai input
            input.value = value;
        }

        // Fungsi untuk menambahkan data contoh (hanya jika belum ada transaksi)
        function addSampleData() {
            const sampleTransactions = [{
                    id: 'TRX-001',
                    customer: {
                        name: 'John Doe',
                        phone: '0812-3456-7890',
                        address: 'Jl. Merdeka 123'
                    },
                    items: [{
                        service: 'Cuci Setrika',
                        weight: 2.5,
                        price: 7000,
                        subtotal: 17500
                    }],
                    total: 17500,
                    date: new Date().toISOString(),
                    status: 'process'
                },
                {
                    id: 'TRX-002',
                    customer: {
                        name: 'Jane Smith',
                        phone: '0813-7654-3210',
                        address: 'Jl. Sudirman 456'
                    },
                    items: [{
                        service: 'Cuci Kering',
                        weight: 3,
                        price: 5000,
                        subtotal: 15000
                    }],
                    total: 15000,
                    date: new Date(Date.now() - 3600000).toISOString(),
                    status: 'ready'
                }
            ];

            if (transactions.length === 0) {
                transactions = sampleTransactions;
                localStorage.setItem('laundryTransactions', JSON.stringify(transactions));
                transactionCounter = transactions.length + 1;
            }
        }

        // Inisialisasi aplikasi setelah seluruh DOM dimuat
        document.addEventListener('DOMContentLoaded', function() {
            addSampleData(); // Pastikan data contoh dimuat terlebih dahulu
            updateTransactionHistory();
            updateStats();

            // Tambahkan event listener untuk input berat agar menangani desimal dengan koma
            const weightInput = document.getElementById('serviceWeight');
            weightInput.addEventListener('input', function() {
                formatNumber(this);
            });

            // Tutup modal saat mengklik di luar area modal
            window.onclick = function(event) {
                const modal = document.getElementById('transactionModal');
                if (event.target === modal) {
                    closeModal();
                }
            };

            // Fungsi untuk auto-fill pelanggan
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

            // --- PENAMBAHAN JAVASCRIPT: Logika untuk mengisi layanan dari database ---
            const servicesGridContainer = document.getElementById('servicesGridContainer');
            const serviceTypeSelect = document.getElementById('serviceType');

            function populateServices() {
                let buttonsHtml = '';
                let optionsHtml = '<option value="">Pilih Layanan</option>'; // Opsi default

                SERVICES_DATA.forEach(service => {
                    const unitText = service.service_name.includes('Sepatu') ? 'pasang' :
                                     service.service_name.includes('Karpet') ? 'm²' : 'kg';
                    const formattedPrice = service.price.toLocaleString('id-ID'); // Format harga ke lokal Indonesia

                    // Tombol Layanan (di services-grid)
                    buttonsHtml += `
                        <button type="button" class="service-card" onclick="addService('${service.service_name}', ${service.price})">
                            <h3>${service.service_name}</h3>
                            <div class="price">Rp ${formattedPrice}/${unitText}</div>
                        </button>
                    `;

                    // Opsi Dropdown Layanan (di select#serviceType)
                    optionsHtml += `
                        <option value="${service.service_name}">${service.service_name} (Rp ${formattedPrice}/${unitText})</option>
                    `;
                });

                servicesGridContainer.innerHTML = buttonsHtml; // Masukkan tombol ke container
                serviceTypeSelect.innerHTML = optionsHtml;     // Masukkan opsi ke dropdown
            }

            // Panggil fungsi untuk mengisi layanan saat DOM selesai dimuat
            populateServices();
            // --- AKHIR PENAMBAHAN JAVASCRIPT ---
        });
    </script>
</body>
</html>