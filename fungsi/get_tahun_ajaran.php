<?php
include '../koneksi.php';

$sql = "SELECT DISTINCT tahun_ajaran FROM buku_tamu ORDER BY tahun_ajaran";
$result = mysqli_query($conn, $sql);

$tahun_ajaran = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tahun_ajaran[] = $row['tahun_ajaran'];
}

echo json_encode($tahun_ajaran);
?>
