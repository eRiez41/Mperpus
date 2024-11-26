<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $id_anggota = $_POST['id_anggota'];
    $keperluan = $_POST['keperluan'];

    $sql = "UPDATE buku_tamu SET id_anggota = '$id_anggota', keperluan = '$keperluan' WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        echo 'success';
    } else {
        echo 'error: ' . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
