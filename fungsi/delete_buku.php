<?php
include '../koneksi.php';

if (isset($_POST['kode_buku'])) {
    $kode_buku = mysqli_real_escape_string($conn, $_POST['kode_buku']);
    $sql = "DELETE FROM buku WHERE kode_buku = '$kode_buku'";
    
    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "invalid";
}
?>
