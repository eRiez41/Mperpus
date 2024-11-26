<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kd_peminjaman = $_POST['id'];

    // Hapus detail peminjaman terlebih dahulu
    $sql_detail = "DELETE FROM peminjaman_detail WHERE kd_peminjaman = '$kd_peminjaman'";
    if (mysqli_query($conn, $sql_detail)) {
        // Hapus data peminjaman
        $sql = "DELETE FROM peminjaman WHERE kd_peminjaman = '$kd_peminjaman'";
        if (mysqli_query($conn, $sql)) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }

    mysqli_close($conn);
} else {
    echo 'error';
}
?>
