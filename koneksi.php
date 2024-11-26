<?php
$servername = "localhost";
$username = "root"; // default user untuk XAMPP
$password = ""; // default password untuk XAMPP
$dbname = "revisi";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
