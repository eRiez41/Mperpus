<?php
include '../koneksi.php';

$kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$tahun_ajaran = isset($_GET['tahun_ajaran']) ? $_GET['tahun_ajaran'] : '';

if ($kelas && $tahun_ajaran) {
    $sql = "SELECT id, nama, nisn FROM anggota WHERE kelas LIKE ? AND tahun_ajaran = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $kelas, $tahun_ajaran);
} elseif ($kelas) {
    $sql = "SELECT id, nama, nisn FROM anggota WHERE kelas LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $kelas);
} elseif ($tahun_ajaran) {
    $sql = "SELECT id, nama, nisn FROM anggota WHERE tahun_ajaran = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tahun_ajaran);
} else {
    $sql = "SELECT id, nama, nisn FROM anggota";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
$anggota = [];

while ($row = $result->fetch_assoc()) {
    $anggota[] = $row;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($anggota);
?>
