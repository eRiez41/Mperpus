<?php
include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mengambil detail buku tamu berdasarkan ID
    $sql = "SELECT buku_tamu.*, anggota.nama AS nama_anggota, anggota.kelas
            FROM buku_tamu
            JOIN anggota ON buku_tamu.id_anggota = anggota.id
            WHERE buku_tamu.id = '$id'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Menyusun data dalam bentuk HTML untuk ditampilkan dalam modal detail
        echo "<div class='modal-body' data-id='" . $row['id'] . "'>";
        echo "<p><strong>Nama Anggota:</strong> " . htmlspecialchars($row['nama_anggota']) . "</p>";
        echo "<p><strong>Kelas:</strong> " . htmlspecialchars($row['kelas']) . "</p>";
        echo "<p><strong>Tahun Ajaran:</strong> " . htmlspecialchars($row['tahun_ajaran']) . "</p>";
        echo "<p><strong>Keperluan:</strong> " . htmlspecialchars($row['keperluan']) . "</p>";
        echo "<p><strong>Tanggal Kunjungan:</strong> " . htmlspecialchars($row['tanggal_kunjungan']) . "</p>";
        echo "</div>";
    } else {
        echo "Data buku tamu tidak ditemukan.";
    }

    mysqli_close($conn);
} else {
    echo "ID buku tamu tidak valid.";
}
?>
