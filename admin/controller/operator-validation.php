<?php
// Mengimpor file koneksi database
include 'admin/controller/koneksi.php';

// Mengambil ID user dari session yang sedang aktif
$validationID = $_SESSION['id'];

// Menjalankan query untuk mengambil data user berdasarkan ID dari session
$validationUserQuery = mysqli_query($connection, "SELECT * FROM user WHERE id = '$validationID'");

// Mengubah hasil query menjadi array asosiatif
$dataValidation = mysqli_fetch_assoc($validationUserQuery);

// Mengecek apakah user yang sedang login bukan dari level 2
// Misalnya: level 2 adalah "admin", maka user selain admin akan diarahkan kembali ke index
if ($dataValidation['id_level'] != 2) {
    // Mengalihkan user yang tidak berhak ke halaman index.php
    header('Location: index.php');
    die; // Menghentikan eksekusi script agar baris setelah ini tidak diproses
}
