<?php
include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT buku_tamu.*, anggota.nama AS nama_anggota, anggota.kelas 
            FROM buku_tamu 
            JOIN anggota ON buku_tamu.id_anggota = anggota.id 
            WHERE buku_tamu.id = $id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        echo "<p><strong>Nama:</strong> " . htmlspecialchars($row['nama_anggota']) . "</p>";
        echo "<p><strong>Kelas:</strong> " . htmlspecialchars($row['kelas']) . "</p>";
        echo "<p><strong>Tahun Ajaran:</strong> " . htmlspecialchars($row['tahun_ajaran']) . "</p>";
        echo "<p><strong>Keperluan:</strong> " . htmlspecialchars(ucfirst($row['keperluan'])) . "</p>";
        echo "<p><strong>Tanggal Kunjungan:</strong> " . htmlspecialchars($row['tanggal_kunjungan']) . "</p>";
    } else {
        echo "<p>Data tidak ditemukan.</p>";
    }

    mysqli_close($conn);
}
?>
