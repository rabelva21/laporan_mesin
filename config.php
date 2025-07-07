<?php
// config.php
$servername = "localhost";
$username = "root"; // Sesuaikan dengan username database
$password = "";     // Sesuaikan dengan password database
$dbname = "laporan_kerusakan";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
