<?php
require_once 'admin/controller/koneksi.php';

if (isset($_POST['id_service'])) {
    $serviceId = $_POST['id_service'];
    $query = mysqli_query($connection, "SELECT price FROM type_of_service WHERE id = '$serviceId'");

    if ($query && mysqli_num_rows($query) > 0) {
        $result = mysqli_fetch_assoc($query);
        echo json_encode(['status' => 'success', 'price' => $result['price']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Service not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
