<?php
// Mengimpor file koneksi database
require_once 'admin/controller/koneksi.php';

// Mengambil ID user dari session saat ini
$validationID = $_SESSION['id'];

// Melakukan query untuk mengambil data user berdasarkan ID dari session
$validationUserQuery = mysqli_query($connection, "SELECT * FROM user WHERE id = '$validationID'");

// Mengubah hasil query menjadi array asosiatif
$dataValidation = mysqli_fetch_assoc($validationUserQuery);

// Mengecek apakah level user tidak sama dengan 3 (misalnya: level 3 adalah level tertentu, seperti "customer")
if ($dataValidation['id_level'] != 3) {
    // Jika level bukan 3, maka arahkan ke halaman index.php
    header('Location: index.php');
    die; // Menghentikan eksekusi script
}
