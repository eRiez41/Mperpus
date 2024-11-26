<?php
include '../koneksi.php';

$kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$tahun_ajaran = isset($_GET['tahun_ajaran']) ? $_GET['tahun_ajaran'] : '';

if ($kelas && $tahun_ajaran) {
    $sql = "SELECT kode_buku, judul FROM buku WHERE kelas = ? AND tahun_ajaran = ? AND kategori = 'Pelajaran'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $kelas, $tahun_ajaran);
} else {
    $sql = "SELECT kode_buku, judul FROM buku WHERE kategori = 'Pelajaran'";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
$buku = [];

while ($row = $result->fetch_assoc()) {
    $buku[] = $row;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($buku);
?>
