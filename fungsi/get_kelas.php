<?php
include '../koneksi.php';

if (isset($_GET['tahun_ajaran'])) {
    $tahun_ajaran = $_GET['tahun_ajaran'];
    
    $sql = "SELECT DISTINCT kelas FROM anggota WHERE tahun_ajaran = '$tahun_ajaran' ORDER BY kelas ASC";
    $result = mysqli_query($conn, $sql);

    $kelas = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $kelas[] = $row['kelas'];
        }
    }

    echo json_encode($kelas);
} else {
    echo json_encode([]);
}

mysqli_close($conn);
?>
