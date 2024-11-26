<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_anggota = $_POST['id_anggota'];
    $kelas = $_POST['kelas'];
    $keperluan = $_POST['keperluan'];
    $tanggal_kunjungan = date('Y-m-d H:i:s');

    $sql = "INSERT INTO buku_tamu (id_anggota, keperluan, tanggal_kunjungan) VALUES ('$id_anggota', '$keperluan', '$tanggal_kunjungan')";

    if (mysqli_query($conn, $sql)) {
        echo 'success';
    } else {
        echo 'error: ' . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
