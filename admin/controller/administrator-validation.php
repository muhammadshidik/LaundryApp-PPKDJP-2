<?php
// Mengimpor file koneksi ke database
require_once 'admin/controller/koneksi.php';

// Mengambil ID user dari session yang sedang aktif
$validationID = $_SESSION['id'];

// Melakukan query ke database untuk mendapatkan data user berdasarkan ID dari session
$validationUserQuery = mysqli_query($connection, "SELECT * FROM user WHERE id = '$validationID'");

// Mengambil hasil query sebagai array asosiatif (key-nya adalah nama kolom)
$dataValidation = mysqli_fetch_assoc($validationUserQuery);

// Mengecek apakah level user bukan 1 (misalnya: level 1 = super admin)
if ($dataValidation['id_level'] != 1) {
    // Jika user bukan level 1, redirect ke halaman index.php
    header('Location: index.php');
    die; // Menghentikan proses eksekusi script selanjutnya
}
