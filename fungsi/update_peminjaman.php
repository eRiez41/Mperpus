<?php
include '../koneksi.php';

$kd_peminjaman = $_POST['kd_peminjaman'];
$tanggal_pinjam = $_POST['tanggal_pinjam'];
$kategori = $_POST['kategori'];
$tanggal_kembali = isset($_POST['tanggal_kembali']) ? $_POST['tanggal_kembali'] : null;
$buku = $_POST['buku'];
$status_buku = isset($_POST['status_buku']) ? $_POST['status_buku'] : [];

$allReturned = true;
foreach ($buku as $index => $kode_buku) {
    // Check if the current index is in the status_buku array
    if (!in_array((string)$index, $status_buku)) {
        $allReturned = false;
        break;
    }
}

if ($allReturned && $tanggal_kembali) {
    $status = 'dikembalikan';
} else {
    $status = 'dipinjam';
    $tanggal_kembali = null;
}

// Update peminjaman table
$sql = "UPDATE peminjaman SET tanggal_pinjam = '$tanggal_pinjam', status = '$status', kategori = '$kategori', tanggal_pengembalian = " . ($tanggal_kembali ? "'$tanggal_kembali'" : "NULL") . " WHERE kd_peminjaman = '$kd_peminjaman'";
if (mysqli_query($conn, $sql)) {
    // Delete existing peminjaman_detail records for this kd_peminjaman
    $sql_delete_detail = "DELETE FROM peminjaman_detail WHERE kd_peminjaman = '$kd_peminjaman'";
    mysqli_query($conn, $sql_delete_detail);

    // Insert updated peminjaman_detail records
    foreach ($buku as $index => $kode_buku) {
        $status_detail = in_array((string)$index, $status_buku) ? 'dikembalikan' : 'dipinjam';
        $sql_detail = "INSERT INTO peminjaman_detail (kd_peminjaman, kode_buku, status) VALUES ('$kd_peminjaman', '$kode_buku', '$status_detail')";
        mysqli_query($conn, $sql_detail);
    }

    echo "<script>alert('Data peminjaman berhasil diperbarui');window.location='../views/peminjaman_buku.php';</script>";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
