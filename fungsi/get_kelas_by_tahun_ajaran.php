<?php
include '../koneksi.php';

if (isset($_GET['tahun_ajaran'])) {
    $tahun_ajaran = $_GET['tahun_ajaran'];
    $sql = "SELECT DISTINCT kelas FROM buku_tamu WHERE tahun_ajaran = '$tahun_ajaran' ORDER BY kelas";
    $result = mysqli_query($conn, $sql);

    $kelas = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $kelas[] = $row['kelas'];
    }

    echo json_encode($kelas);
}
?>
