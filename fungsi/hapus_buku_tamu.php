<?php
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Query untuk menghapus buku tamu berdasarkan ID
    $sql = "DELETE FROM buku_tamu WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "failed";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
